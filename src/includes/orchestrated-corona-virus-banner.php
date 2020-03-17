<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Orchestrated_Corona_Virus_Banner {

	private static $_instance = null;
	public $settings = null;
	public $_version;
	public $_token;
	public $file;
	public $dir;
	public $assets_dir;
	public $assets_url;
	public $script_suffix;

	/**
	 * Load all of the dependencies
	 *
	 */
	public function __construct( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'orchestrated_corona_virus_banner';

		//* Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );
		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		//* Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		//* Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		//* Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new Orchestrated_Corona_Virus_Banner_API();
		}

		$this->settings = new Orchestrated_Corona_Virus_Banner_Settings( $this );

		//* Handle Localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'wp_footer', array( $this, 'page_code' ) );
	}


	/**
	 * Display code on page for notice
	 */
	public function page_code() {
		$enabled = get_option( Orchestrated_Corona_Virus_Banner()->_token . '_enabled' ) ?: false;
		$html = "";
		if ( $enabled ) {
			$notice_html = $this->settings->get_notice_display();
			$html .= $notice_html;
		}
		$html .= <<<HTML
		<script>
			jQuery( function () { window.ocvb.init(); });
		</script>
HTML;
		echo $html;
	}

	/**
	 * Display admin notices for plugin
	 */
	public function admin_notice() {

	}

	/**
	 * Get Pages from WordPress.
	 *
	 * Loop through the Pages available on the website
	 * and return them.
	 *
	 */
	public function get_pages() {
		$pages = get_pages();
		$pages_select = [];
		foreach( $pages as $page ) {
			if( !empty( $page->post_title ) ) {
				$pages_select[$page->ID] = $page->post_title;
			}
		}
		return $pages_select;
	}

	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	} 


	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_scripts () {
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-frontend' );
	}


	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	}


	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );
	}


	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation() {
		load_plugin_textdomain( 'orchestrated-corona-virus-banner', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}


	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
	    $domain = 'orchestrated-corona-virus-banner';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}


	/**
	 * Main Orchestrated_Corona_Virus_Banner Instance
	 *
	 * Ensures only one instance of Orchestrated_Corona_Virus_Banner is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Orchestrated_Corona_Virus_Banner()
	 * @return Main Orchestrated_Corona_Virus_Banner instance
	 */
	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		
		return self::$_instance;
	}


	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */

	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	}


	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */

	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	}


	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */

	public function install () {
		$this->_log_version_number();
	}


	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */

	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	}
}