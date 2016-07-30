<?php
/*
Plugin Name: Core-Xpress
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

if ( ! class_exists( 'Core_Xpress' ) ) {
	class Core_Xpress {
		/**
		 * Plugin Slug
		 * @var string
		 */
		public static $plugin_slug = 'xpress';

		/**
		 * Required directories
		 * @var array
		 */
		private $required_directories;

		public function __construct() {
			$this->require_dir( trailingslashit( plugin_dir_path( __FILE__ ) . 'includes' ) );
			require_once( plugin_dir_path( __FILE__ ) . 'includes/titan-framework/titan-framework-embedder.php' );
			add_action( 'tf_create_options', array( $this, 'admin_options' ) );
			add_action( 'plugins_loaded', array( $this, 'register_child_plugins' ), 0 );
			add_action( 'plugins_loaded', array( $this, 'requires' ), 5 );
		}

		public function register_child_plugins() {
			$plugins = apply_filters( self::$plugin_slug . '/child-plugins', array() );
			foreach ( $plugins as $plugin ) {
				new Child_Plugin_Xpress( $plugin );
			}
		}

		public function requires() {
			$directories = apply_filters( self::$plugin_slug . '/required_directories', array() );
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

		public function admin_options() {
			do_action( 'xpress_create_options' );
		}
	}
	new Core_Xpress();
}
