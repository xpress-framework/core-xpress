<?php
/**
 * Xpress Custom Field Group
 *
 * @package      WordPress
 * @subpackage   Xpress Core
 * @author       Trasgo Furioso
 * @license      GPLv2
 * @version      0.0.1
 */

if ( ! class_exists( 'Xpress_Custom_Field_Group' ) ) {
	class Xpress_Custom_Field_Group {
		private $args = null;

		public function __construct( $args ) {
			$this->args = $args;
			add_action( 'init', array( $this, 'register_custom_field' ), 10 );
		}

		public function register_custom_field() {
			if ( function_exists( 'register_field_group' ) ) {
				$locations = $this->locations();
				if ( count( $locations ) === 0 ) {
					return false;
				}
				$this->args['location'] = $locations;
				register_field_group( $this->args );
			}
		}

		private function locations() {
			$locations = array();
			$post_types = apply_filters( 'xpress/custom-fields/' . $this->args['id'] .'/post-types', array() );
			foreach ( $post_types as $post_type ) {
				$locations[] = array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => $post_type,
						'order_no' => 0,
						'group_no' => 0,
					),
				);
			}
			return $locations;
		}
	} // Xpress_Custom_Field_Group
} // class_exists
