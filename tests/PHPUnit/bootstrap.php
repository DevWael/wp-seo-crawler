<?php

putenv( 'TESTS_PATH=' . __DIR__ );
putenv( 'LIBRARY_PATH=' . dirname( __DIR__ ) );
$vendor = dirname( __FILE__, 3 ) . '/vendor/';
if ( ! realpath( $vendor ) ) {
	die( 'Please install via Composer before running tests.' );
}
if ( ! defined( 'PHPUNIT_COMPOSER_INSTALL' ) ) {
	define( 'PHPUNIT_COMPOSER_INSTALL', $vendor . 'autoload.php' );
}

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', '/var/www/html/' );
}

if ( ! class_exists( WP_CLI_Command::class ) ) {
	class WP_CLI_Command {
	}
}

if ( ! class_exists( WP_List_Table::class ) ) {
	class WP_List_Table {
		public function __construct( array $args = array() ) {
		}
	}
}

require_once $vendor . '/antecedent/patchwork/Patchwork.php';
require_once $vendor . 'autoload.php';
unset( $vendor );
