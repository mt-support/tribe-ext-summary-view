<?php
/**
 * Plugin Name:       The Events Calendar Extension: Summary View
 * Plugin URI:
 * GitHub Plugin URI: https://github.com/mt-support/tribe-ext-summary-view
 * Description:       This extension adds a "Summary", or "Agenda" styled view to the calendar view options.
 * Version:           1.0.0
 * Author:            Modern Tribe, Inc.
 * Author URI:        http://m.tri.be/1971
 * License:           GPL version 3 or any later version
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       tribe-ext-summary-view
 *
 *     This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *     GNU General Public License for more details.
 */

/**
 * Define the base file that loaded the plugin for determining plugin path and other variables.
 *
 * @since 1.0.0
 *
 * @var string Base file that loaded the plugin.
 */
define( 'TRIBE_EXTENSION_SUMMARY_VIEW_FILE', __FILE__ );

/**
 * Register and load the service provider for loading the extension.
 *
 * @since 1.0.0
 */
function tribe_extension_summary_view() {
	// When we don't have autoloader from common we bail.
	if ( ! class_exists( 'Tribe__Autoloader' ) ) {
		return;
	}

	// Register the namespace so we can the plugin on the service provider registration.
	Tribe__Autoloader::instance()->register_prefix(
		'\\Tribe\\Extensions\\Summary_View\\',
		__DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Tribe',
		'summary-view'
	);

	// Deactivates the plugin in case of the main class didn't autoload.
	if ( ! class_exists( '\Tribe\Extensions\Summary_View\Plugin' ) ) {
		tribe_transient_notice(
			'summary-view',
			'<p>' . esc_html__( 'Couldn\'t properly load "The Events Calendar Extension: Summary View" the extension was deactivated.', 'tribe-ext-summary-view' ) . '</p>',
			[],
			// 1 second after that make sure the transient is removed.
			1
		);

		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		deactivate_plugins( __FILE__, true );
		return;
	}

	tribe_register_provider( '\Tribe\Extensions\Summary_View\Plugin' );
}

// Loads after common is already properly loaded.
add_action( 'tribe_common_loaded', 'tribe_extension_summary_view' );

register_activation_hook( __FILE__, 'tribe_extension_summary_view_activation' );

/**
 * Enables the view on plugin activation.
 *
 * @since 1.0.0
 */
function tribe_extension_summary_view_activation() {
	$enabled_views = tribe_get_option( 'tribeEnableViews' );

	if ( in_array( 'summary', $enabled_views ) ) {
		return;
	}

	$enabled_views[] = 'summary';

	tribe_update_option( 'tribeEnableViews', $enabled_views );

	// Fixes "ugly permalinks" on activation.
	// Check seems silly, but might as well not do it if it's not needed.
	if ( get_option( 'permalink_structure' ) ) {
		set_transient( '_tribe_events_delayed_flush_rewrite_rules', 'yes', 0 );
	}
}
