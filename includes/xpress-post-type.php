<?php
/**
 * Xpress Post Type
 *
 * @package      WordPress
 * @subpackage   Xpress Framework
 * @author       Trasgo Furioso
 * @license      GPLv2
 * @version      0.0.1
 */

if ( ! class_exists( 'Xpress_Post_Type' ) ) {
	class Xpress_Post_Type {
		private $post_type_args;
		private $slug;
		private $singular_name;
		private $plural_name;

		public function __construct( $slug, $singular_name, $plural_name, $post_type_args=array() ) {
			$this->slug = $slug;
			$this->singular_name = $singular_name;
			$this->plural_name = $plural_name;
			$this->post_type_args = array_merge( $this->default_post_type_args(), $post_type_args );
			add_action( 'init', array( $this, 'register_post_type' ), 0 );
		}

		public function register_post_type() {
			register_post_type( $this->slug, $this->post_type_args );
		}

		public function custom_field_group( $group ) {
			add_filter( 'xpress/custom-fields/' . $group . '/post-types', array( $this, 'filter_post_type' ) );
		}

		public function taxonomy( $taxonomy ) {
			add_filter( 'xpress/taxonomies/' . $taxonomy . '/post-types', array( $this, 'filter_post_type' ) );
		}

		public function filter_post_type( $post_types ) {
			$post_types[] = $this->slug;
			return $post_types;
		}

		public function add_capabilities( $role_slug ) {
			$capabilities = array(
				'publish_' . $this->slug,
				'edit_' . $this->slug,
				'edit_published_' . $this->slug,
				'edit_others_' . $this->slug,
				'delete_' . $this->slug,
				'delete_published_' . $this->slug,
				'delete_others_' . $this->slug,
				'read_' . $this->slug,
				'read_private_' . $this->slug,
			);

			$role = get_role( $role_slug );

			if ( ! is_null( $role ) ) {
				foreach ( $capabilities as $capability ) {
					$role->add_cap( $capability );
				}
			}
		}

		private function default_post_type_args() {
			return array(
				'label'               => $this->slug,
				'labels'              => $this->labels(),
				'supports'            => array( 'title', 'thumbnail', 'editor' ),
				'hierarchical'        => true,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 25,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
				'capabilities'        => $this->capabilities(),
				'rewrite'             => $this->rewrite(),
			);
		}

		private function labels() {
			$labels = array(
				'name'                => $this->plural_name,
				'singular_name'       => $this->singular_name,
				'menu_name'           => $this->plural_name,
				'name_admin_bar'      => $this->plural_name,
				'parent_item_colon'   => sprintf( _x( 'Parent %s:', 'custom types', 'xpress' ), $this->singular_name ),
				'all_items'           => sprintf( _x( 'All %s', 'custom types', 'xpress' ), $this->plural_name ),
				'add_new_item'        => sprintf( _x( 'Add New %s', 'custom types', 'xpress' ), $this->singular_name ),
				'add_new'             => _x( 'Add New', 'custom types', 'xpress' ),
				'new_item'            => sprintf( _x( 'New %s', 'custom types', 'xpress' ), $this->singular_name ),
				'edit_item'           => sprintf( _x( 'Edit %s', 'custom types', 'xpress' ), $this->singular_name ),
				'update_item'         => sprintf( _x( 'Update %s', 'custom types', 'xpress' ), $this->singular_name ),
				'view_item'           => sprintf( _x( 'View %s', 'custom types', 'xpress' ), $this->singular_name ),
				'search_items'        => sprintf( _x( 'Search %s', 'custom types', 'xpress' ), $this->plural_name ),
				'not_found'           => _x( 'Not found', 'custom types', 'xpress' ),
				'not_found_in_trash'  => _x( 'Not found in Trash', 'custom types', 'xpress' ),
			);
			return $labels;
		}

		private function capabilities() {
			$capabilities = array(
				'publish_posts'       => 'publish_' . $this->slug,
				'edit_posts'          => 'edit_' . $this->slug,
				'edit_others_posts'   => 'edit_others_' . $this->slug,
				'delete_posts'        => 'delete_' . $this->slug,
				'delete_others_posts' => 'delete_others_' . $this->slug,
				'read_private_posts'  => 'read_private_' . $this->slug,
				'edit_post'           => 'edit_' . $this->slug,
				'delete_post'         => 'delete_' . $this->slug,
				'read_post'           => 'read_' . $this->slug,
			);
			return $capabilities;
		}

		private function rewrite() {
			$rewrite = array(
				'slug' => $this->slug,
			);
			return $rewrite;
		}
	} // Xpress_Post_Type
} // class_exists
