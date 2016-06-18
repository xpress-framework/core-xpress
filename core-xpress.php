<?php
/*
Plugin Name: Core-Xpress
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

if ( ! class_exists( 'Core_Xpress' ) ) {
	class Core_Xpress {
		public static $plugin_slug = 'core-xpress';

		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'requires' ), 0 );
		}

		public function requires() {
			$directories = apply_filters( self::$plugin_slug . '/requires', array(
				trailingslashit( plugin_dir_path( __FILE__ ) . 'includes' )
			) );
			foreach ( $directories as $directory ) {
				$this->require_dir( $directory );
			}
		}

		private function require_dir( $directory ) {
			$php_files = glob( $directory . '*.php' );
			if ( ! $php_files ) return false;
			foreach( $php_files as $file ) {
				require_once $file;
			}
		}
	}
	new Core_Xpress();
}
