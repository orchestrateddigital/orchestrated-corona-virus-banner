<?php
/*
 * Plugin Name: Corona Virus (COVID-19) Banner
 * Version: 1.0
 * Description: Display a notice to visitors about how your business/organization will respond to COVID-19
 * Author: Orchestrated
 * Author URI: http://www.orchestrated.ca
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: orchestrated-corona-virus-banner
 *
 * @package WordPress
 * @author Orchestrated
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require("vendor/autoload.php");

// Load plugin class files
require_once( 'includes/orchestrated-corona-virus-banner.php' );
require_once( 'includes/orchestrated-corona-virus-banner-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/orchestrated-corona-virus-banner-admin-api.php' );

/**
 * Returns the main instance of Orchestrated_Corona_Virus_Banner to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Orchestrated_Corona_Virus_Banner
 */
function Orchestrated_Corona_Virus_Banner () {
	$instance = Orchestrated_Corona_Virus_Banner::instance( __FILE__, '1.0.0' );
	if ( is_null( $instance->settings ) ) {
		$instance->settings = Orchestrated_Corona_Virus_Banner_Settings::instance( $instance );
	}

	return $instance;
}

Orchestrated_Corona_Virus_Banner();
