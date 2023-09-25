<?php

/**
 * The shortcode functionality of the plugin.
 *
 * @package    Hsdl_Mdt_Search
 * @subpackage Hsdl_Mdt_Search/public
 * @author     Anusha Priyamal <anusha@eight25media.com>
 *
 */

class Hsdl_Mdt_Search_Shortcodes
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $hsdl_mdt_search The ID of this plugin.
     */
    private $hsdl_mdt_search;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;


    /**
     * plugin public function
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $plugin_public;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $hsdl_mdt_search The name of the plugin.
     * @param string $version The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct($hsdl_mdt_search, $version)
    {
        $this->plugin_public = new Hsdl_Mdt_Search_Public($this->hsdl_mdt_search, $this->version);
        $this->hsdl_mdt_search = $hsdl_mdt_search;
        $this->version = $version;
        add_shortcode('mdt_search_bar', array($this, 'shortcode_mdt_search_bar'));
        add_shortcode('mdt_collections', array($this, 'shortcode_mdt_collections'));
        add_shortcode('mdt_infocus', array($this, 'shortcode_mdt_infocus'));
    }


    /**
     * Shortcode function for returning a search bar module.
     *
     * @since 2.3.5
     */
    public function shortcode_mdt_search_bar($attributes)
    {
        return $this->plugin_public->display_mdt_search_bar_shortcode($attributes);
    }

    /**
     * Shortcode function for returning a mdt collections module.
     *
     * @since 2.3.5
     */
    public function shortcode_mdt_collections($attributes)
    {
        $attributes = shortcode_atts(
            array(
                'facets' => '',
            ),
            $attributes,
            'mdt_collections'
        );
        return $this->plugin_public->display_mdt_collections_shortcode($attributes);
    }

    /**
     * Shortcode function for returning a mdt in focus module.
     *
     * @since 2.3.5
     */
    public function shortcode_mdt_infocus($attributes)
    {
        $attributes = shortcode_atts(
            array(
                'facets' => '',
            ),
            $attributes,
            'mdt_infocus'
        );
        return $this->plugin_public->display_mdt_infocus_shortcode($attributes);
    }
}
