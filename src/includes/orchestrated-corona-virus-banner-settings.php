<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Orchestrated_Corona_Virus_Banner_Settings {

	private static $_instance = null;
	public $parent = null;
	public $base = '';
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'orchestrated_corona_virus_banner_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		$page = add_options_page( __( 'Corona Virus Banner', 'orchestrated-corona-virus-banner' ) , __( 'Corona Virus Banner', 'orchestrated-corona-virus-banner' ) , 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
	}

	/**
	 * Load settings JS & CSS
	 * @return void
	 */
	public function settings_assets () {
	  	wp_register_script( $this->parent->_token . '-settings-js', $this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js', array( 'farbtastic', 'jquery' ), '1.0.0' );
	  	wp_enqueue_script( $this->parent->_token . '-settings-js' );
	}


	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'orchestrated-corona-virus-banner' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Display notice
	 * @return string Returns HTML to display notice
	 */
	public function get_notice_display ( $preview = false ) {
		$enabled = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_enabled' ) ?: false;
		$message_title = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_message_title' ) ?: "";
		$message_text = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_message_text' ) ?: "";
		$message_alignment = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_message_alignment' ) ?: "center";
		$internal_link = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_internal_link' ) ?: "NONE";
		$external_link = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_external_link' ) ?: "";
		$link_text = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_link_text' ) ?: "More Information";
		$foreground_color = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_foreground_color' ) ?: "#ffffff";
		$background_color = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_background_color' ) ?: "#cc0000";
		$container_css = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_container_css' ) ?: "";
		$container_css_mobile = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_container_css_mobile' ) ?: "";

		$banner_state_class = $enabled ? "ocvb-enabled" : "ocvb-disabled";
		$link_state_class = "ocvb-disabled";
		$link_to_direct_to = "";
		$ready_state = $preview ? "ready-and-display" : "not-ready";

		//	If 
		if ( $internal_link == "EXT" ) {
			//	Link to external URL
			if (filter_var($external_link, FILTER_VALIDATE_URL) === FALSE) {
				//	Reset because URL is invalid: malformed
				update_option( Orchestrated_Corona_Virus_Banner()->_token . '_internal_link', "NONE" );
				update_option( Orchestrated_Corona_Virus_Banner()->_token . '_external_link', "" );
				$link_state_class = "ocvb-disabled";
			} else {
				$link_to_direct_to = $external_link;
				$link_state_class = "ocvb-enabled";
			}
		} else if ( $internal_link == "NONE" ) {
			//	Remove any page/link info
			update_option( Orchestrated_Corona_Virus_Banner()->_token . '_internal_link', "NONE" );
			update_option( Orchestrated_Corona_Virus_Banner()->_token . '_external_link', "" );
			$link_state_class = "ocvb-disabled";
		} else {
			//	Link to internal Page
			if ( 'publish' == get_post_status ( $internal_link ) ) {
				$page_url = get_page_link( $internal_link );
				$link_to_direct_to = $page_url;
				$link_state_class = "ocvb-enabled";
			} else {
				//	Reset because Page is not found
				update_option( Orchestrated_Corona_Virus_Banner()->_token . '_internal_link', "NONE" );
				update_option( Orchestrated_Corona_Virus_Banner()->_token . '_external_link', "" );
				$link_state_class = "ocvb-disabled";
			}
		}

		if ( $preview ) {
			$container_css = "";
			$container_css_mobile = "";
		}

		return <<<HTML
			<style>
				#ocvb-container {
					color: ${foreground_color};
					background-color: ${background_color};
					text-align: ${message_alignment};
					${container_css}
				}
				@media screen and (max-width: 480px) {
					#ocvb-container {
						${container_css_mobile}
					}
				}
				#ocvb-container h4 {
					color: ${foreground_color};
					text-align: ${message_alignment};
				}
				#ocvb-container p {
					color: ${foreground_color};
					text-align: ${message_alignment};
				}
				#ocvb-container a {
					color: ${foreground_color};
					text-align: ${message_alignment};
				}
			</style>
			<div id="ocvb-container" class="${ready_state} ${banner_state_class}">
				<div id="ocvb-container-notice-text">
					<h4>${message_title}</h4>
					<p>${message_text}</p>
					<div id="ocvb-container-notice-link" class="${link_state_class}">
						<a href="${link_to_direct_to}">${link_text}</a>
					</div>
				</div>
			</div>
HTML;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {
		$enabled = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_enabled' ) ?: false;
		$message_title = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_message_title' ) ?: "";
		$message_text = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_message_text' ) ?: "";
		$message_alignment = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_message_alignment' ) ?: "center";
		$internal_link = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_internal_link' ) ?: "NONE";
		$external_link = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_external_link' ) ?: "";
		$link_text = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_link_text' ) ?: "More Information";
		$foreground_color = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_foreground_color' ) ?: "#ffffff";
		$background_color = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_background_color' ) ?: "#cc0000";
		$container_css = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_container_css' ) ?: "";
		$container_css_mobile = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_container_css_mobile' ) ?: "";
		
		$pages = $this->parent->get_pages();
		$pages["––"] = "––––––––––––––";
		$pages["NONE"] = "No Link";
		$pages["EXT"] = "Link to external URL";

		$settings['settings'] = array(
			'title'						=> __( 'Corona Virus Banner – Settings', 'orchestrated-corona-virus-banner' ),
			'description'				=> __( 'Configure what is displayed in the banner.', 'orchestrated-corona-virus-banner' ),
			'fields'					=> array(
				array(
					'id'					=> 'enabled',
					'label'					=> __( 'Enabled' , 'orchestrated-corona-virus-banner' ),
					'description'			=> __( 'Show banner on top of website?', 'orchestrated-corona-virus-banner' ),
					'type'					=> 'checkbox',
					'default'				=> false,
				),
				array(
					'id'					=> 'message_title',
					'label'					=> __( 'Title' , 'orchestrated-corona-virus-banner' ),
					'description'			=> __( 'The headline text to be displayed.', 'orchestrated-corona-virus-banner' ),
					'type'					=> 'text',
					'default'				=> '',
					'placeholder'		=> __( 'Enter your notice title.', 'orchestrated-corona-virus-banner' )
				),
				array(
					'id'					=> 'message_text',
					'label'					=> __( 'Message' , 'orchestrated-corona-virus-banner' ),
					'description'			=> __( 'The message you would like to display.', 'orchestrated-corona-virus-banner' ),
					'type'					=> 'textarea',
					'default'				=> '',
					'placeholder'		=> __( 'Enter your notice text.', 'orchestrated-corona-virus-banner' )
				),
				array(
					'id'					=> 'message_alignment',
					'label'					=> __( 'Alignment' , 'orchestrated-corona-virus-banner' ),
					'description'			=> __( 'How would you like the notice to be displayed?', 'orchestrated-corona-virus-banner' ),
					'type'					=> 'select',
					'options'				=> array(
						'center' => 'Center',
						'left' => 'Left',
						'right' => 'Right',
						'justify' => 'Justified',
						'inherit' => 'Default',
					),
				),
				array(
					'id'					=> 'internal_link',
					'label'					=> __( 'More Information Page' , 'orchestrated-corona-virus-banner' ),
					'description'			=> __( 'Which page would you like to direct the user to for more information?', 'orchestrated-corona-virus-banner' ),
					'type'					=> 'select',
					'options'				=> $pages,
				),
				array(
					'id'					=> 'external_link',
					'label'					=> __( 'URL' , 'orchestrated-corona-virus-banner' ),
					'description'			=> __( 'Enter the full URL.', 'orchestrated-corona-virus-banner' ),
					'type'					=> 'text',
					'placeholder'			=> __( 'http://www.host.com', 'orchestrated-mailchimp-queue-posts' ),
				),
				array(
					'id'					=> 'link_text',
					'label'					=> __( 'Link Text' , 'orchestrated-corona-virus-banner' ),
					'description'			=> __( '', 'orchestrated-corona-virus-banner' ),
					'type'					=> 'text',
					'placeholder'			=> __( 'More Information', 'orchestrated-mailchimp-queue-posts' ),
				),
				array(
					'id'					=> 'foreground_color',
					'label'					=> __( 'Foreground Color' , 'orchestrated-corona-virus-banner' ),
					'description'			=> __( 'What foreground color would you like to use?', 'orchestrated-corona-virus-banner' ),
					'type'					=> 'color',
					'default'				=> '#ffffff'
				),
				array(
					'id'					=> 'background_color',
					'label'					=> __( 'Background Color' , 'orchestrated-corona-virus-banner' ),
					'description'			=> __( 'What background color would you like to use?', 'orchestrated-corona-virus-banner' ),
					'type'					=> 'color',
					'default'				=> '#cc0000'
				),
				array(
					'id'					=> 'container_css',
					'label'					=> __( 'CSS' , 'orchestrated-corona-virus-banner' ),
					'description'			=> __( 'Is there CSS you want to apply to the banner container?', 'orchestrated-corona-virus-banner' ),
					'type'					=> 'textarea',
					'placeholder'			=> __( 'e.g. margin-top: 20px;', 'orchestrated-corona-virus-banner' )
				),
				array(
					'id'					=> 'container_css_mobile',
					'label'					=> __( 'CSS (Mobile)' , 'orchestrated-corona-virus-banner' ),
					'description'			=> __( 'Is there CSS you want to apply to the banner container on mobile?', 'orchestrated-corona-virus-banner' ),
					'type'					=> 'textarea',
					'placeholder'			=> __( 'e.g. margin-top: 20px;', 'orchestrated-corona-virus-banner' )
				),
			)
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}


	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			foreach ( $this->settings as $section => $data ) {

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				if ( $data['fields'] ) {
					foreach ( $data['fields'] as $field ) {

						// Validation callback for field
						$validation = '';
						if ( isset( $field['callback'] ) ) {
							$validation = $field['callback'];
						}

						// Register field
						$option_name = $this->base . $field['id'];
						register_setting( $this->parent->_token . '_settings', $option_name, $validation );

						// Add field to page
						add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
					}
				}
			}
		}
	}


	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}


	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {
		$html = "";
		$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";
			ob_start();
			settings_fields( $this->parent->_token . '_settings' );
			do_settings_sections( $this->parent->_token . '_settings' );
			$html .= ob_get_clean();

			$html .= '<div class="ocvb-preview-container">';
				$html .= '<h3>Preview</h3>';
				$html .= $this->get_notice_display(true);
			$html .= '</div>';

			$html .= '<p class="submit">' . "\n";
				$html .= '<input name="Submit" type="submit" class="button-primary" value="' . sanitize_text_field( __( 'Save Settings' , 'orchestrated-corona-virus-banner' ) ) . '" />' . "\n";
			$html .= '</p>' . "\n";
		$html .= '</form>' . "\n";

		echo $html;
	}


	/**
	 * Main Orchestrated_Corona_Virus_Banner Instance
	 *
	 * Ensures only one instance of Orchestrated_Corona_Virus_Banner is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Orchestrated_Corona_Virus_Banner
	 * @return Main Orchestrated_Corona_Virus_Banner instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} 


	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	}


	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	}
}
