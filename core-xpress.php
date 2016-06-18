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
		public static $plugin_slug = 'core-xpress';

		/**
		 * Required directories
		 * @var array
		 */
		private $required_directories;

		public function __construct() {
			$this->required_directories = array(
				trailingslashit( plugin_dir_path( __FILE__ ) . 'includes' )
			);
			add_action( 'plugins_loaded', array( $this, 'register_child_plugins' ), 0 );
			add_action( 'plugins_loaded', array( $this, 'requires' ), 5 );
		}

		public function register_child_plugins() {
			$plugins = apply_filters( self::$plugin_slug . '/child-plugins', array() );
			foreach ( $plugins as $plugin ) {
				$this->required_directories = array_merge( $this->required_directories, $this->require_child_plugin_directories( $plugin ) );
			}
		}

		public function require_child_plugin_directories( $plugin ) {
			return array(
				trailingslashit( $plugin['path'] . 'post-types' ),
				trailingslashit( $plugin['path'] . 'taxonomies' ),
				trailingslashit( $plugin['path'] . 'custom-fields' ),
				trailingslashit( $plugin['path'] . 'controllers' ),
				trailingslashit( $plugin['path'] . 'components' ),
				trailingslashit( $plugin['path'] . 'functions' ),
				trailingslashit( $plugin['path'] . 'admin' ),
			);
		}
		public function requires() {
			$directories = apply_filters( self::$plugin_slug . '/required_directories', $this->required_directories );
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
