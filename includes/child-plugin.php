<?php
/**
 * Child Plugin Xpress
 *
 * @package      WordPress
 * @subpackage   Core-Xpress
 * @author       Trasgo Furioso
 * @license      GPLv2
 * @version      0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

if ( ! class_exists( 'Child_Plugin_Xpress' ) ) {
	class Child_Plugin_Xpress {
		/**
		 * Plugin Slug
		 * @var string
		 */
		public $slug;

		/**
		 * Plugin Name
		 * @var string
		 */
		public $name;

		/**
		 * Plugin Path
		 * @var string
		 */
		public $path;

		public function __construct( $plugin ) {
			$this->slug = $plugin['slug'];
			$this->name = $plugin['name'];
			$this->path = $plugin['path'];
			add_filter( 'core-xpress/required_directories', array( $this, 'required_directories' ) );
		}

		public function required_directories( $directories ) {
			return array_merge( $directories, array(
				trailingslashit( $this->path . 'post-types' ),
				trailingslashit( $this->path . 'taxonomies' ),
				trailingslashit( $this->path . 'custom-fields' ),
				trailingslashit( $this->path . 'controllers' ),
				trailingslashit( $this->path . 'components' ),
				trailingslashit( $this->path . 'functions' ),
				trailingslashit( $this->path . 'admin' ),
			) );
		}
	}
}
