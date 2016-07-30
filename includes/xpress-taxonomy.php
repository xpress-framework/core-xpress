<?php
/**
 * Xpress Taxonomy
 *
 * @package      WordPress
 * @subpackage   Xpress Core
 * @author       Trasgo Furioso
 * @license      GPLv2
 * @version      0.0.1
 */

if ( ! class_exists( 'Xpress_Taxonomy' ) ) {
	class Xpress_Taxonomy {
		private $taxonomy_args;
		private $slug;
		private $singular_name;
		private $plural_name;

		public function __construct( $slug, $singular_name, $plural_name, $taxonomy_args = array() ) {
			$this->slug = $slug;
			$this->singular_name = $singular_name;
			$this->plural_name = $plural_name;
			$this->taxonomy_args = array_merge( $this->default_taxonomy_args(), $taxonomy_args );
			add_action( 'init', array( $this, 'register_taxonomy' ), 0 );

		}

		public function register_taxonomy() {
			register_taxonomy( $this->slug, $this->post_types(), $this->taxonomy_args );
		}

		public function add_capabilities( $role_slug ) {
			$capabilities = array(
				'manage_' . $this->slug,
				'edit_' . $this->slug,
				'delete_' . $this->slug,
				'assign_' . $this->slug,
			);

			$role = get_role( $role_slug );

			if ( ! is_null( $role ) ) {
				foreach ( $capabilities as $capability ) {
					$role->add_cap( $capability );
				}
			}
		}

		private function default_taxonomy_args() {
			return array(
				'labels'                     => $this->labels(),
				'hierarchical'               => true,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => true,
				'capabilities'               => $this->capabilities(),
				'rewrite'                    => $this->rewrite(),
			);
		}

		private function labels() {
			$labels = array(
				'name'                       => $this->plural_name,
				'singular_name'              => $this->singular_name,
				'menu_name'                  => $this->plural_name,
				'all_items'                  => sprintf( _x( 'All %s', 'custom types', 'xpress' ), $this->plural_name ),
				'parent_item'                => sprintf( _x( 'Parent %s', 'custom types', 'xpress' ), $this->singular_name ),
				'parent_item_colon'          => sprintf( _x( 'Parent %s:', 'custom types', 'xpress' ), $this->singular_name ),
				'new_item_name'              => sprintf( _x( 'New %s Name', 'custom types', 'xpress' ), $this->singular_name ),
				'add_new_item'               => sprintf( _x( 'Add New %s', 'custom types', 'xpress' ), $this->singular_name ),
				'edit_item'                  => sprintf( _x( 'Edit %s', 'custom types', 'xpress' ), $this->singular_name ),
				'update_item'                => sprintf( _x( 'Update %s', 'custom types', 'xpress' ), $this->singular_name ),
				'view_item'                  => sprintf( _x( 'View %s', 'custom types', 'xpress' ), $this->singular_name ),
				'separate_items_with_commas' => sprintf( _x( 'Separate %s with commas', 'custom types', 'xpress' ), $this->plural_name ),
				'add_or_remove_items'        => sprintf( _x( 'Add or remove %s', 'custom types', 'xpress' ), $this->plural_name ),
				'choose_from_most_used'      => sprintf( _x( 'Choose from the most used %s', 'custom types', 'xpress' ), $this->plural_name ),
				'popular_items'              => sprintf( _x( 'Popular %s', 'custom types', 'xpress' ), $this->plural_name ),
				'search_items'               => sprintf( _x( 'Search %s', 'custom types', 'xpress' ), $this->plural_name ),
				'not_found'                  => _x( 'Not found', 'custom types', 'xpress' ),
			);
			return $labels;
		}

		private function capabilities() {
			$capabilities = array(
				'manage_terms'  => 'manage_' . $this->slug,
				'edit_terms'    => 'edit_' . $this->slug,
				'delete_terms'  => 'delete_' . $this->slug,
				'assign_terms'  => 'assign_' . $this->slug,
			);
			return $capabilities;
		}

		private function rewrite() {
			$rewrite = array(
				'slug' => $this->slug,
			);
			return $rewrite;
		}

		private function post_types() {
			return apply_filters( 'xpress/taxonomies/' . $this->slug .'/post-types', array() );
		}
	}
}
