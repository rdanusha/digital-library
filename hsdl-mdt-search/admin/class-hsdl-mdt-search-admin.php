<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hsdl_Mdt_Search
 * @subpackage Hsdl_Mdt_Search/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hsdl_Mdt_Search
 * @subpackage Hsdl_Mdt_Search/admin
 * @author     Anusha Priyamal <anusha@eight25media.com>
 */
class Hsdl_Mdt_Search_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hsdl_mdt_search    The ID of this plugin.
	 */
	private $hsdl_mdt_search;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $hsdl_mdt_search       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $hsdl_mdt_search, $version ) {

		$this->hsdl_mdt_search = $hsdl_mdt_search;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hsdl_Mdt_Search_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hsdl_Mdt_Search_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->hsdl_mdt_search, plugin_dir_url( __FILE__ ) . 'css/hsdl-mdt-search-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hsdl_Mdt_Search_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hsdl_Mdt_Search_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->hsdl_mdt_search, plugin_dir_url( __FILE__ ) . 'js/hsdl-mdt-search-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the settings page
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menu() {
		add_options_page( 'HSDL MDT Search',
			__( 'HSDL MDT Search Settings', 'hsdl-mdt-search' ),
			'manage_options',
			'hsdl-mdt-search-admin', array( $this, 'create_admin_interface' ) );
	}

	/**
	 * Callback function for the admin settings page.
	 *
	 * @since    1.0.0
	 */
	public function create_admin_interface() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/hsdl-mdt-search-admin-display.php';

	}


}
