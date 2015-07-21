<?php

class Sparql_Admin
{
	private $plugins_root;
	private $plugins_url;
	private $apps;

	public function __construct( $params )
	{
		$this->plugins_root = $params[ 'plugins_root' ];
		$this->plugins_url = $params[ 'plugins_url' ];
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
		add_action( 'edit_form_after_title', array( $this, 'edit_form_after_title' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'admin_print_footer_scripts' ) );
	}

	public function init()
	{
		$this->register_custom_post_type();
	}

	public function admin_print_footer_scripts( $hook_suffix )
	{
		global $hook_suffix;
		if ( 'post-new.php' === $hook_suffix || 'post.php' === $hook_suffix ) {
			if ( 'sparql-app' === get_post_type() ) {
				?>
				<script type="text/javascript">
					CodeMirror.fromTextArea( document.getElementById( "codemirror-sparql" ), {
						mode: "application/sparql-query",
						matchBrackets: true
					});
					CodeMirror.fromTextArea( document.getElementById( "codemirror-app" ), {
						matchBrackets: true
					});
					(function($){
						$('#sparql-app-app' ).change( function(){
							if ('custom' == $('option:selected', this).val() ) {
								$('#js-editor').show();
							} else {
								$('#js-editor').hide();
							}
						} );
					})(jQuery);
				</script>
				<?php
			}
		}
	}

	public function admin_enqueue_scripts( $hook_suffix )
	{
		if ( 'post-new.php' === $hook_suffix || 'post.php' === $hook_suffix ) {
			if ( 'sparql-app' === get_post_type() ) {
				wp_enqueue_style(
					'codemirror',
					$this->plugins_url . '/lib/codemirror/lib/codemirror.css',
					array(),
					filemtime( $this->plugins_root . '/lib/codemirror/lib/codemirror.css' )
				);

				wp_enqueue_script(
					'codemirror',
					$this->plugins_url . '/lib/codemirror/lib/codemirror.js',
					array(),
					filemtime( $this->plugins_root . '/lib/codemirror/lib/codemirror.js' ),
					false
				);

				wp_enqueue_script(
					'codemirror-matchbrackets',
					$this->plugins_url . '/lib/codemirror/addon/edit/matchbrackets.js',
					array( 'codemirror' ),
					filemtime( $this->plugins_root . '/lib/codemirror/addon/edit/matchbrackets.js' ),
					false
				);

				wp_enqueue_script(
					'codemirror-sparql',
					$this->plugins_url . '/lib/codemirror/mode/sparql/sparql.js',
					array( 'codemirror' ),
					filemtime( $this->plugins_root . '/lib/codemirror/mode/sparql/sparql.js' ),
					false
				);

				wp_enqueue_script(
					'codemirror-javascript',
					$this->plugins_url . '/lib/codemirror/mode/javascript/javascript.js',
					array( 'codemirror' ),
					filemtime( $this->plugins_root . '/lib/codemirror/mode/javascript/javascript.js' ),
					false
				);

				wp_enqueue_style(
					'sparql-app-css',
					$this->plugins_url . '/css/sparql-app.css',
					array( 'codemirror' ),
					filemtime( $this->plugins_root . '/css/sparql-app.css' )
				);
			}
		}
	}

	public function edit_form_after_title( $post )
	{
		if ( 'sparql-app' === get_post_type() ) {
			echo '<div id="sparql-app-shortcode-sample">';
			echo 'Shortcode: <code>[sparql id="'.esc_attr( get_the_ID() ).'"]</code>';
			echo '</div>';
		}
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
			'edit_item'           => __( 'Edit SPARQL App', 'sparql-app' ),
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
		add_meta_box( 'sparql-app-app', 'Application', array( $this, 'meta_app' ), 'sparql-app', 'normal', 'high' );
		add_meta_box( 'sparql-app-preview', 'Preview', array( $this, 'meta_preview' ), 'sparql-app', 'normal', 'high' );
	}

	public function meta_sparql( $post )
	{
		wp_nonce_field( 'sparql-app-sparql', 'sparql-app-sparql-nonce' );

		$value = get_post_meta( $post->ID, '_sparql_app_sparql', true );

		printf(
			'<textarea id="codemirror-sparql" name="%1$s" style="width: 100%%; height: 10em;">%2$s</textarea>',
			'sparql-app-sparql',
			esc_textarea( $value )
		);
	}

	public function meta_app( $post )
	{
		wp_nonce_field( 'sparql-app-app', 'sparql-app-app' );

		$this->load_apps();
		?>
		<p><select id="sparql-app-app" name="sparql-app-app">
		<option>Please select</option>
		<?php foreach ( $this->apps as $app ): ?>
		<?php
			$app_instance = new $app();
			$app_name = $app_instance->get_name();
			echo '<option value="'.esc_attr( $app ).'">'.esc_html( $app_name ).'</option>';
		?>
		<?php endforeach; ?>
		<option value="custom">Custom App</option>
		</select></p>

		<div id="js-editor"><textarea id="codemirror-app" name="sparql-app-custom-app" style="width: 100%%; height: 10em;"><?php echo esc_textarea( get_post_meta( $post->ID, '_sparql_app_custom_app', true ) ); ?></textarea></div>
		<?php
	}

	public function meta_preview( $post )
	{
		echo '<div id="sparql-app-preview" style="width: 100%;">';
		echo '<iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d6550.800124028384!2d135.5239112!3d34.8210326!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sja!2sjp!4v1437512871120" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>';
		echo '</div>';
	}

	private function load_apps()
	{
		$dir = $this->get_apps_dir();

		if ( is_dir( $dir ) ){
			if ( $dh = opendir( $dir ) ){
				while ( ( $file = readdir( $dh ) ) !== false ){
					if ( preg_match( '/\.php$/', $file ) ) {
						$this->apps[] = basename( $file, '.php' );
						require_once( untrailingslashit( $dir ) . '/' . $file );
					}
				}
				closedir( $dh );
			}
		}
	}

	private function get_apps_dir()
	{
		return apply_filters( 'sparql_app_apps_dir', $this->plugins_root . '/apps' );
	}
}
