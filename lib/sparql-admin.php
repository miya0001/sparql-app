<?php

class Sparql_Admin
{
	public function __construct()
	{

	}

	public function register()
	{
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	public function plugins_loaded()
	{
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	public function init()
	{
		$this->register_custom_post_type();
	}

	public function admin_enqueue_scripts()
	{

	}

	private function register_custom_post_type()
	{
		$labels = array(
			'name'                => _x( 'SPARQL Apps', 'SPARQL Apps', 'sparql-app' ),
			'singular_name'       => _x( 'SPARQL Apps', 'SPARQL App', 'sparql-app' ),
			'menu_name'           => __( 'SPARQL Apps', 'sparql-app' ),
			'name_admin_bar'      => __( 'SPARQL Apps', 'sparql-app' ),
			'parent_item_colon'   => __( 'Parent Item:', 'sparql-app' ),
			'all_items'           => __( 'All Items', 'sparql-app' ),
			'add_new_item'        => __( 'Add New SPARQL App', 'sparql-app' ),
			'add_new'             => __( 'Add New', 'sparql-app' ),
			'new_item'            => __( 'New Item', 'sparql-app' ),
			'edit_item'           => __( 'Edit Item', 'sparql-app' ),
			'update_item'         => __( 'Update Item', 'sparql-app' ),
			'view_item'           => __( 'View Item', 'sparql-app' ),
			'search_items'        => __( 'Search Item', 'sparql-app' ),
			'not_found'           => __( 'Not found', 'sparql-app' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'sparql-app' ),
		);

		$args = array(
			'label'               => __( 'SPARQL Apps', 'sparql-app' ),
			'description'         => __( 'Generate SPARQL App', 'sparql-app' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'revisions' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
		);

		register_post_type( 'sparql-app', $args );
	}

	public function add_meta_boxes()
	{
		add_meta_box( 'sparql-app-sparql', 'SPARQL', array( $this, 'meta_sparql' ), 'sparql-app', 'normal', 'high' );
	}

	public function meta_sparql( $post )
	{
		wp_nonce_field( 'sparql-app-sparql', 'sparql-app-sparql-nonce' );

		$value = get_post_meta( $post->ID, '_sparql_app_sparql', true );

		printf(
			'<textarea id="%1$s" name="%1$s" style="width: 100%%; height: 10em;">%2$s</textarea>',
			'sparql-app-sparql',
			esc_textarea( $value )
		);
	}
}
