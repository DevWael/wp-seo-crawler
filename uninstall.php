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
delete_option( 'wpseoc_options' );
