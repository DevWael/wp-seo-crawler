<?php
/**
 * Plugin Uninstall
 *
 * Deleting options and clean database.
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/**
 * Delete options
 */
global $wpdb;
// delete all options starting with wpseoc_.
// phpcs:disable
$wpdb->delete( $wpdb->options, [ 'option_name' => 'wpseoc_%' ], [ '%s' ] );
// phpcs:enable
