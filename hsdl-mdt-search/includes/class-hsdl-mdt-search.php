<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hsdl_Mdt_Search
 * @subpackage Hsdl_Mdt_Search/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Hsdl_Mdt_Search
 * @subpackage Hsdl_Mdt_Search/includes
 * @author     Anusha Priyamal <anusha@eight25media.com>
 */
class Hsdl_Mdt_Search
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Hsdl_Mdt_Search_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $hsdl_mdt_search The string used to uniquely identify this plugin.
     */
    protected $hsdl_mdt_search;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('HSDL_MDT_SEARCH_VERSION')) {
            $this->version = HSDL_MDT_SEARCH_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->hsdl_mdt_search = 'hsdl-mdt-search';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Hsdl_Mdt_Search_Loader. Orchestrates the hooks of the plugin.
     * - Hsdl_Mdt_Search_i18n. Defines internationalization functionality.
     * - Hsdl_Mdt_Search_Admin. Defines all hooks for the admin area.
     * - Hsdl_Mdt_Search_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hsdl-mdt-search-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hsdl-mdt-search-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-hsdl-mdt-search-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-hsdl-mdt-search-public.php';

        /**
         * The class responsible for defining all shortcodes
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-hsdl-mdt-search-shortcodes.php';


        $this->loader = new Hsdl_Mdt_Search_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Hsdl_Mdt_Search_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Hsdl_Mdt_Search_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Hsdl_Mdt_Search_Admin($this->get_hsdl_mdt_search(), $this->get_version());
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_menu');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Hsdl_Mdt_Search_Public($this->get_hsdl_mdt_search(), $this->get_version());
        $plugin_shortcodes = new Hsdl_Mdt_Search_Shortcodes($this->get_hsdl_mdt_search(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_enqueue_styles', $plugin_public, 'enqueue_styles');
        $this->loader->add_filter('generate_rewrite_rules', $plugin_public, 'generate_rewrite_rules');
        $this->loader->add_filter('query_vars', $plugin_public, 'set_query_vars');
        $this->loader->add_action('template_redirect', $plugin_public, 'display_results');
        $this->loader->add_action('template_redirect', $plugin_public, 'details_view');
        $this->loader->add_action('template_redirect', $plugin_public, 'download_view');
        $this->loader->add_action('template_redirect', $plugin_public, 'display_critical_releases');
        $this->loader->add_action('template_redirect', $plugin_public, 'alerts_and_subscriptions');
        $this->loader->add_action('template_redirect', $plugin_public, 'display_previous_releases');
        $this->loader->add_action('template_redirect', $plugin_public, 'download_search_results');
        $this->loader->add_filter('body_class', $plugin_public, 'custom_class');
        $this->loader->add_filter('wp_title', $plugin_public, 'overwrite_title');

        $this->loader->add_action('wp_ajax_display_results_ajx', $plugin_public, 'display_results_ajx');
        $this->loader->add_action('wp_ajax_nopriv_display_results_ajx', $plugin_public, 'display_results_ajx');
        $this->loader->add_action('wp_ajax_get_previous_releases_result_ajx', $plugin_public, 'get_previous_releases_result_ajx');
        $this->loader->add_action('wp_ajax_nopriv_get_previous_releases_result_ajx', $plugin_public, 'get_previous_releases_result_ajx');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_hsdl_mdt_search()
    {
        return $this->hsdl_mdt_search;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Hsdl_Mdt_Search_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Error Logger
     * @param $log
     */
    public static function write_log($log)
    {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

}
