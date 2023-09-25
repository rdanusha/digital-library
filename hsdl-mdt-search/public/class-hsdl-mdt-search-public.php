<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hsdl_Mdt_Search
 * @subpackage Hsdl_Mdt_Search/public
 */

use function Env\env;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Hsdl_Mdt_Search
 * @subpackage Hsdl_Mdt_Search/public
 * @author     Anusha Priyamal <anusha@eight25media.com>
 */
class Hsdl_Mdt_Search_Public
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

    public $api_end_point;

    public $release_flags;

    public $languages_map;

    public $collections;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $hsdl_mdt_search The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($hsdl_mdt_search, $version)
    {
        $this->hsdl_mdt_search = $hsdl_mdt_search;
        $this->api_end_point = env('API_ENDPOINT') ?: 'http://localhost:3000/api/v1';
        $this->version = $version;
        $this->release_flags = array(
            0 => 'Show to End User',
            1 => 'Show to End User',
            10 => 'Needs Review',
            11 => 'Needs Review',
            20 => 'Broken Link',
            21 => 'Broken Link',
            30 => 'Do Not Collect',
            31 => 'Do Not Collect',
            50 => 'Duplicate',
            51 => 'Duplicate');
        $this->languages_map = array(
            'af' => 'Afrikaans',
            'sq' => 'Albanian',
            'ar' => 'Arabic',
            'eu' => 'Basque',
            'br' => 'Breton',
            'bg' => 'Bulgarian',
            'be' => 'Byelorussian',
            'ca' => 'Catalan',
            'zh' => 'Chinese',
            'zh-simplified' => 'Chinese (simp)',
            'zh-traditional' => 'Chinese (trad)',
            'hr' => 'Croatian',
            'cs' => 'Czech',
            'da' => 'Danish',
            'nl' => 'Dutch',
            'en' => 'English',
            'eo' => 'Esperanto',
            'et' => 'Estonian',
            'fo' => 'Faeroese',
            'fa' => 'Farsi',
            'fi' => 'Finnish',
            'fr' => 'French',
            'fy' => 'Frisian',
            'gl' => 'Galician',
            'de' => 'German',
            'el' => 'Greek',
            'he' => 'Hebrew',
            'iw' => 'Hebrew',
            'hu' => 'Hungarian',
            'is' => 'Icelandic',
            'id' => 'Indonesian',
            'ga' => 'Irish Gaelic',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'kr' => 'Korean',
            'la' => 'Latin',
            'lv' => 'Latvian',
            'lt' => 'Lithuanian',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'no' => 'Norwegian', 'nb' => 'Norwegian (bokmaal)', 'nn' => 'Norwegian (nynorsk)',
            'pl' => 'Polish',
            'pt' => 'Portuguese',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'sr' => 'Serbian',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'es' => 'Spanish',
            'sw' => 'Swahili',
            'sv' => 'Swedish',
            'th' => 'Thai',
            'tr' => 'Turkish',
            'uk' => 'Ukrainian',
            'vi' => 'Vietnamese',
            'cy' => 'Welsh',
            'yi' => 'Yiddish',
        );
        $this->collections = array(
            0 => 'Public',
            1 => 'Restricted',
            10 => 'Public',
            11 => 'Restricted',
            20 => 'Public',
            21 => 'Restricted',
            30 => 'Public',
            31 => 'Restricted',
            50 => 'Public',
            51 => 'Restricted'
        );


    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->hsdl_mdt_search . '_select2', plugin_dir_url(__FILE__) . 'css/select2.min.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->hsdl_mdt_search . '_select2', plugin_dir_url(__FILE__) . 'js/select2.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->hsdl_mdt_search, plugin_dir_url(__FILE__) . 'js/hsdl-mdt-search-public.js', array('jquery'), $this->version, true);
        $options = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('hsdl_ajax_submit'),
            'api_endpoint' => API_ENDPOINT,
            'token' => (isset($_SESSION['jwt']) && !empty($_SESSION['jwt'])) ? $_SESSION['jwt'] : ''
        );
        wp_localize_script($this->hsdl_mdt_search, 'hsdl_mdt_ajax', $options);
    }

    /**
     * Return mdt_search_bar shortcode ouptput
     * @param $attributes
     */
    public function display_mdt_search_bar_shortcode($attributes)
    {
        ob_start();
        $search_in_values = $this->get_search_in_dropdwon_values();
        $staff_settings = '';
        if (is_mdt_user()) {
            $staff_settings = $this->get_staff_search_settings_values();
            $fast_subjects_list = $this->get_fast_subjects_list();
        }

        require plugin_dir_path(dirname(__FILE__)) . 'public/partials/hsdl-mdt-search-public-mdt-search-bar-display.php';
        $post_output = ob_get_contents();
        ob_end_clean();
        return $post_output;
    }

    /**
     * Return mdt_collection shortcode ouptput
     * @param $attributes
     * @return false|string
     */
    public function display_mdt_collections_shortcode($attributes)
    {
        ob_start();
        require plugin_dir_path(dirname(__FILE__)) . 'public/partials/hsdl-mdt-search-public-mdt-collections-display.php';
        $post_output = ob_get_contents();
        ob_end_clean();
        return $post_output;
    }

    /**
     * Return mdt_infocus shortcode ouptput
     * @param $attributes
     * @return false|string
     */
    public function display_mdt_infocus_shortcode($attributes)
    {
        ob_start();
        require plugin_dir_path(dirname(__FILE__)) . 'public/partials/hsdl-mdt-search-public-mdt-infocus-display.php';
        $post_output = ob_get_contents();
        ob_end_clean();
        return $post_output;
    }

    /**
     * add rewrite rule to set virtual page
     * @param $wp_rewrite
     */
    public function generate_rewrite_rules($wp_rewrite)
    {
        $wp_rewrite->rules = array_merge(
            ['search-result/?$' => 'index.php?search=1',
                'abstract/?$' => 'index.php?details=1',
                'view/?$' => 'index.php?d=1',
                'download-results/?$' => 'index.php?download=1',
                'critical-releases/?$' => 'index.php?cr=1',
                'previous-critical-releases/?$' => 'index.php?pcr=1',
                'alerts-subscriptions/?$' => 'index.php?d=1',
            ],
            $wp_rewrite->rules
        );
    }

    /**
     * add custom query variable
     * @param $query_vars
     * @return mixed
     */
    public function set_query_vars($query_vars)
    {
        $query_vars[] = 'search';
        $query_vars[] = 'type';
        $query_vars[] = 'searchterm';
        $query_vars[] = 'start';
        $query_vars[] = 'rows';
        $query_vars[] = 'sort';
        $query_vars[] = 'subjects';
        $query_vars[] = 'creator';
        $query_vars[] = 'series';
        $query_vars[] = 'lists';
        $query_vars[] = 'format';
        $query_vars[] = 'language';
        $query_vars[] = 'publisher';
        $query_vars[] = 'tabsection';
        $query_vars[] = 'col-focus';

        $query_vars[] = 'advanced';
        $query_vars[] = 'all';
        $query_vars[] = 'any';
        $query_vars[] = 'exact';
        $query_vars[] = 'without';
        $query_vars[] = 'from';
        $query_vars[] = 'to';
        $query_vars[] = 'search-in';

        $query_vars[] = 'did';
        $query_vars[] = 'details';
        $query_vars[] = 'd';
        $query_vars[] = 'mdt';

        //staff search
        $query_vars[] = 'releaseFlag';
        $query_vars[] = 'docID';
        $query_vars[] = 'url';
        $query_vars[] = 'source';
        $query_vars[] = 'notes';
        $query_vars[] = 'country';
        $query_vars[] = 'state';
        $query_vars[] = 'modifiedBy';
        $query_vars[] = 'modifiedFrom';
        $query_vars[] = 'modifiedTo';
        $query_vars[] = 'createdBy';
        $query_vars[] = 'createdFrom';
        $query_vars[] = 'createdTo';
        $query_vars[] = 'fastSubject';

        //critical-releases-page
        $query_vars[] = 'cr';
        $query_vars[] = 'nominate';
        $query_vars[] = 'remove';
        $query_vars[] = 'id';
        $query_vars[] = 'action';
        $query_vars[] = 'year';
        $query_vars[] = 'month';
        $query_vars[] = 'pcr';
        $query_vars[] = 'crid';
        $query_vars[] = 'restricted';
        $query_vars[] = 'page-name';
        $query_vars[] = 'message';

        $query_vars[] = 'file';
        $query_vars[] = 'download';
        $query_vars[] = 'numfound';

        return $query_vars;
    }

    /**
     * get current url
     * @return string
     */
    public function get_current_url()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * return virtual page output (critical-releases-page)
     */
    public function display_critical_releases()
    {
        $page = intval(get_query_var('cr'));
        $critical_release_id = intval(get_query_var('id'));
        $action = get_query_var('action');
        $records_count = 0;
        $close_publish_tag_data = [];

        $url = $this->get_current_url();

        if (!empty($action) && $action == 'close') {
            $this->close_critical_releases_list($critical_release_id);
            $redirect_url = remove_query_arg(array('action'), $url);
            header("Location: $redirect_url");
            exit;
        }

        if (!empty($action) && $action == 'publish') {
            $this->publish_critical_releases_list($critical_release_id);
            $redirect_url = remove_query_arg(array('action'), $url);
            header("Location: $redirect_url");
            exit;
        }

        if ($critical_release_id) {
            $critical_release = $this->get_critical_release($critical_release_id);

            if ($critical_release) {

                $records_count = (isset($critical_release->response->numFound)) ? $critical_release->response->numFound : 0;
                $documents = $critical_release->response->docs;
                $release_details = $critical_release->nominate;

                if (is_user_allow_to_manage_critical_release()) {
                    $close_publish_tag_data = $this->get_critical_release_close_publish_tag_data($critical_release_id);
                }
            }
        } else {
            //display latest critical releases
            $critical_release = $this->get_latest_critical_release();
            if ($critical_release) {
                $records_count = (isset($critical_release->response->numFound)) ? $critical_release->response->numFound : 0;
                $documents = $critical_release->response->docs;
                $release_details = $critical_release->nominate;
            }
            if (is_user_allow_to_manage_critical_release()) {
                $close_publish_tag_data = $this->get_critical_release_close_publish_tag_data(0);
            }
        }
        $this_class = $this;

        if ($page) {
            include plugin_dir_path(dirname(__FILE__)) . 'public/partials/hsdl-mdt-search-public-critical-releases-display.php';
            die();
        }
    }

    /**
     * return virtual page output (result detail page)
     */
    public function display_results()
    {
        $search = intval(get_query_var('search'));
        $type = get_query_var('type') ?: 'all';
        $start = intval(get_query_var('start')) ?: 0;
        $rows = intval(get_query_var('rows')) ?: 30;
        $search_term = get_query_var('searchterm');
        $sort = get_query_var('sort');

        //facet values
        $subjects = get_query_var('subjects') ?: '';
        $creator = get_query_var('creator') ?: '';
        $series = get_query_var('series') ?: '';
        $lists = get_query_var('lists') ?: '';
        $format = get_query_var('format') ?: '';
        $language = get_query_var('language') ?: '';
        $publisher = get_query_var('publisher') ?: '';
        $tabsection = get_query_var('tabsection') ?: '';

        $advanced = get_query_var('advanced') ?: 'false';
        $all = get_query_var('all') ?: '';
        $any = get_query_var('any') ?: '';
        $exact = get_query_var('exact') ?: '';
        $without = get_query_var('without') ?: '';
        $from = get_query_var('from') ?: '';
        $to = get_query_var('to') ?: '';
        $search_in = get_query_var('search-in') ?: '';
        $mdt = (isset($_SESSION['mdt'])) ? $_SESSION['mdt'] : 0;

        //staff
        $release_flag = get_query_var('releaseFlag') ?: '';
        $doc_id = get_query_var('docID') ?: '';
        $url = get_query_var('url') ?: '';
        $source = get_query_var('source') ?: '';
        $notes = get_query_var('notes') ?: '';
        $country = get_query_var('country') ?: '';
        $state = get_query_var('state') ?: '';
        $modified_by = get_query_var('modifiedBy') ?: '';
        $modified_from = get_query_var('modifiedFrom') ?: '';
        $modified_to = get_query_var('modifiedTo') ?: '';
        $created_by = get_query_var('createdBy') ?: '';
        $created_from = get_query_var('createdFrom') ?: '';
        $created_to = get_query_var('createdTo') ?: '';
        $fast_subject = get_query_var('fastSubject') ?: '';
        $page_name = get_query_var('page-name') ?: '';
        $restricted = get_query_var('restricted') ?: '';
        $action = get_query_var('action') ?: '';
        $message = get_query_var('message') ?: '';
        $numfound = get_query_var('numfound') ?: '';


        $is_restricted_collections = false;
        if (($page_name == "restricted" && $restricted == 'true') && is_hsdl_restricted_participant() || is_ip_user()) {
            $is_restricted_collections = true;
        }

        if ($advanced == 'true' && !empty($all)) {
            $search_term = $all;
        }
        if ($advanced == 'true' && !empty($search_in)) {
            $tabsection = $search_in;
        }

        $filter_bar_text = $type;

        if ($type == 'all') {
            $filter_bar_text = 'full text and any metadata';
        }

        $args = array(
            'type' => $type,
            'start' => $start,
            'rows' => $rows,
            'sort' => $sort,
            'searchterm' => $search_term,
            'subjects' => $subjects,
            'creator' => $creator,
            'series' => $series,
            'lists' => $lists,
            'format' => $format,
            'language' => $language,
            'publisher' => $publisher,
            'tabsection' => $tabsection,
            'advanced' => $advanced,
            'all' => $all,
            'any' => $any,
            'exact' => $exact,
            'without' => $without,
            'from' => $from,
            'to' => $to,
            'mdt' => $mdt,
            'releaseFlag' => $release_flag,
            'docID' => $doc_id,
            'url' => $url,
            'source' => $source,
            'notes' => $notes,
            'country' => $country,
            'state' => $state,
            'modifiedBy' => $modified_by,
            'modifiedFrom' => $modified_from,
            'modifiedTo' => $modified_to,
            'createdBy' => $created_by,
            'createdFrom' => $created_from,
            'createdTo' => $created_to,
            'fastSubject' => $fast_subject,
            'restricted' => $is_restricted_collections
        );

        if ($action == 'download') {
            $args['rows'] = $numfound;
            $response = $this->search_results_download_request($args);
            $download_notice_page_id = 962;
            $download_notice_page_url = get_permalink($download_notice_page_id);
            if ($response) {
                header("Location: $download_notice_page_url");
                exit;
            }
        }

        //generate download link
        $url = $this->get_current_url();
        $result_download_url = $url . '&action=download';

        $decode_response = $this->search_api_call($args);

        $documents = [];
        $num_found = 0;
        $is_load_more = false;
        $facets_array = [];
        $facets = '';
        $facet_title = 'Refine Results';
        if ($decode_response) {
            $documents = $decode_response->data->response->docs;
            $num_found = $decode_response->data->response->numFound;
            $start = $decode_response->data->response->start;
            $facets_array = (isset($decode_response->data->facet)) ? $decode_response->data->facet : [];
            $is_load_more = $num_found > ($rows + $start);
            $this_class = $this;
            $release_flags = $this->release_flags;
        }

        if ($search) {
            include plugin_dir_path(dirname(__FILE__)) . 'public/partials/hsdl-mdt-search-public-result-display.php';
            die();
        }
    }

    public function search_results_download_request($params)
    {
        $url = $this->api_end_point;
        $query_string = http_build_query($params);
        $url .= "/search/download?$query_string";
        $args = array('method' => 'POST');

        return $this->send_api_request($url, $args);
    }

    /**
     * return abstract view output
     */
    public function details_view()
    {
        $details = intval(get_query_var('details'));
        $nominate = intval(get_query_var('nominate'));
        $critical_release = intval(get_query_var('cr'));
        $remove = intval(get_query_var('remove'));
        $document_id = get_query_var('did');
        $page_name = get_query_var('page-name');
        $restricted = get_query_var('restricted');

        $decode_response = $this->get_document_details($document_id);
        $release_flags = $this->release_flags;

        $collections = $this->collections;
        $languages_map = $this->languages_map;

        $start = 0;
        $rows = 30;
        if ($decode_response) {

            $download_url = $this->get_document_download_url($document_id);
            $document = $decode_response->data;
            if (!empty($document)) {
                $format = '';
                $media_type = '';
                if (isset($document->selectedFormats) && !empty($document->selectedFormats) && is_array($document->selectedFormats)) {
                    $selected_formats_arr = explode(')(', $document->selectedFormats[0]->ControlledName);
                    if (!empty($selected_formats_arr) && is_array($selected_formats_arr)) {
                        $media_type = str_replace(array('(', ')'), '', end($selected_formats_arr));
                        $format_arr = explode('/', $media_type);
                        if (is_array($format_arr) && !empty($format_arr)) {
                            $format = $format_arr[1];
                        }
                    }
                }
                $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $current_url = urlencode($current_url);
                $display_flag = false;

                if (isset($document->collection) && array_key_exists($document->collection, $release_flags)) {
                    if (!in_array($document->collection, array(0, 1))) { //not in show to enduser
                        $display_flag = true;
                    }
                }
                $nomination_tags = $this->get_nomination_tags($document_id);
            }
        }
        $bottom_tag_list = $this->get_details_page_listed_on_tags($document_id);

        if ($details == 1 && !empty($document_id)) {
            include plugin_dir_path(dirname(__FILE__)) . 'public/partials/hsdl-mdt-search-details-display.php';
            die();
        }
    }

    /**
     * download document page output
     */
    public function download_view()
    {
        $download = intval(get_query_var('d'));
        $document_id = get_query_var('did');
        $download_location = '';

        if ($download && !empty($document_id)) {
            $has_permission = $this->check_download_permission($document_id);
            if ($has_permission) {
                $decode_response = $this->get_document_details($document_id);
                if ($decode_response) {
                    $document = $decode_response->data;
                    $document_url = $document->URL;
                    if (!empty($document_url)) {
                        $file_info = pathinfo($document_url);
                        $file_name = $file_info['basename'];
                        $file_ext = $file_info['extension'];
                        if (!filter_var($document_url, FILTER_VALIDATE_URL)) {
                            $base_path = env('DOCUMENT_BASE_PATH') ?: '/var/www/html';
                            $download_location = $base_path . $document_url;
                        }
                    }
                }
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream', true, 200);
                header('Content-Disposition: attachment; filename="' . $file_name . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($download_location)); //Absolute URL
                ob_clean();
                flush();
                readfile($download_location); //Absolute URL
            }
            exit();
        }
    }

    /**
     * Get nominations from given document id
     * @param $document_id
     * @return false|mixed
     */
    public function get_nomination_tags($document_id)
    {
        if (!empty($document_id)) {
            $url = $this->api_end_point;
            $url .= "/nomination/tags/$document_id";
            $args = array('method' => 'GET');

            $response = $this->send_api_request($url, $args);

            if ($response) {
                return $response->data;
            }
        }
    }

    /**
     * Set auth header
     * @param $args
     * @return mixed
     */
    public function set_header($args)
    {
        $token = (isset($_SESSION['jwt']) && !empty($_SESSION['jwt'])) ? $_SESSION['jwt'] : '';
        if ($token) {
            $args['headers'] = array(
                'Authorization' => 'Bearer ' . $token
            );
        }
        return $args;
    }

    /**
     * add document to critical release nomination list
     * @param $document_id
     * @param $nomination_id
     * @return bool
     */
    public function add_nomination($document_id, $nomination_id)
    {
        if (!empty($document_id) && !empty($nomination_id)) {
            $url = $this->api_end_point;
            $url .= '/nomination/';
            $args = array('method' => 'POST',
                'body' => array('docID' => $document_id, 'nominationID' => $nomination_id)
            );

            $response = $this->send_api_request($url, $args);
            if ($response) {
                return true;
            }
        }
        return false;
    }

    /**
     * remove document from critical release nomination list
     * @param $document_id
     * @param $nomination_id
     * @return bool
     */
    public function remove_nomination($document_id, $nomination_id)
    {
        if (!empty($document_id) && !empty($nomination_id)) {
            $url = $this->api_end_point;
            $url .= '/nomination/';
            $args = array('method' => 'DELETE',
                'body' => array('docID' => $document_id, 'nominationID' => $nomination_id)
            );

            $response = $this->send_api_request($url, $args);

            if ($response) {
                return true;
            }
        }
        return false;
    }

    /**
     * get critical releases for given id
     * @param $critical_release_id
     * @return mixed
     */
    public function get_critical_release($critical_release_id)
    {
        if (!empty($critical_release_id)) {
            $url = $this->api_end_point;
            $url .= "/critical-release/$critical_release_id";
            $args = array('method' => 'GET');

            $response = $this->send_api_request($url, $args);

            if ($response) {
                return $response->data;
            }
        }
    }

    /**
     * get all critical releases
     * @return mixed
     */
    public function get_latest_critical_release()
    {
        $url = $this->api_end_point;
        $url .= "/critical-release/latest/release";
        $args = array('method' => 'GET');
        $response = $this->send_api_request($url, $args);

        if ($response) {
            return $response->data;
        }
    }


    /**
     * Get critical releases publish and close information
     * @param $critical_release_id
     * @return mixed
     */
    public function get_critical_release_close_publish_tag_data($critical_release_id)
    {
        if (!empty($critical_release_id)) {
            $url = $this->api_end_point;
            $url .= "/critical-release/nomination-tags/$critical_release_id";
            $args = array('method' => 'GET');

            $response = $this->send_api_request($url, $args);

            if ($response) {
                return $response->data;
            }
        }
    }

    public function publish_critical_releases_list($critical_release_id)
    {
        if (!empty($critical_release_id)) {

            $url = $this->api_end_point;
            $url .= '/critical-release/publish-list';
            $args = array('method' => 'POST',
                'body' => array('nominationID' => $critical_release_id)
            );

            $response = $this->send_api_request($url, $args);

            if ($response) {
                return true;
            }
        }
        return false;
    }

    public function close_critical_releases_list($critical_release_id)
    {
        if (!empty($critical_release_id)) {
            $url = $this->api_end_point;
            $url .= '/critical-release/close-list';
            $args = array('method' => 'POST',
                'body' => array('nominationID' => $critical_release_id)
            );

            $response = $this->send_api_request($url, $args);

            if ($response) {
                return true;
            }
        }
        return false;
    }

    /**
     * get download document url
     * @param $document_id
     * @return string|void
     */
    public function get_document_download_url($document_id)
    {
        $response = $this->get_document_details($document_id);
        $download_url = '';
        if ($response) {
            $document = $response->data;
            $document_url = $document->URL;
            if (filter_var($document_url, FILTER_VALIDATE_URL)) {
                $download_url = $document_url;
            } else {
                $download_url = home_url("/view?d=1&did={$document->docID}");
            }
        }
        return $download_url;
    }

    /**
     * Get document info by given doc id
     * @param $document_id
     * @return false|mixed
     */
    public function get_document_details($document_id)
    {
        if (!empty($document_id)) {
            $url = $this->api_end_point;
            $url .= "/document/$document_id";
            $args = array('method' => 'GET');

            return $this->send_api_request($url, $args);
        }
    }

    /**
     * Search result load more ajax callback function
     */
    public function display_results_ajx()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], "load_more_data")) {
            exit("No naughty business please");
        }

        $type = (isset($_POST['type'])) ? $_POST['type'] : 'all';
        $start = (isset($_POST['start'])) ? $_POST['start'] : 0;
        $rows = (isset($_POST['rows'])) ? $_POST['rows'] : 30;
        $search_term = (isset($_POST['search_term'])) ? $_POST['search_term'] : 30;
        $sort = (isset($_POST['sort'])) ? $_POST['sort'] : 'date';

        $subjects = (isset($_POST['subjects'])) ? $_POST['subjects'] : '';
        $creator = (isset($_POST['creator'])) ? $_POST['creator'] : '';
        $series = (isset($_POST['series'])) ? $_POST['series'] : '';
        $lists = (isset($_POST['lists'])) ? $_POST['lists'] : '';
        $format = (isset($_POST['format'])) ? $_POST['format'] : '';
        $language = (isset($_POST['language'])) ? $_POST['language'] : '';
        $publisher = (isset($_POST['publisher'])) ? $_POST['publisher'] : '';
        $tabsection = (isset($_POST['tabsection'])) ? $_POST['tabsection'] : '';

        $advanced = (isset($_POST['advanced'])) ? $_POST['advanced'] : '';
        $all = (isset($_POST['all'])) ? $_POST['all'] : '';
        $any = (isset($_POST['any'])) ? $_POST['any'] : '';
        $exact = (isset($_POST['exact'])) ? $_POST['exact'] : '';
        $without = (isset($_POST['without'])) ? $_POST['without'] : '';
        $from = (isset($_POST['from'])) ? $_POST['from'] : '';
        $to = (isset($_POST['to'])) ? $_POST['to'] : '';
        $mdt = (isset($_SESSION['mdt'])) ? $_SESSION['mdt'] : 0;

        //staff
        $release_flag = (isset($_POST['releaseFlag'])) ? $_POST['releaseFlag'] : '';
        $doc_id = (isset($_POST['docID'])) ? $_POST['docID'] : '';
        $url = (isset($_POST['url'])) ? $_POST['url'] : '';
        $source = (isset($_POST['source'])) ? $_POST['source'] : '';
        $notes = (isset($_POST['notes'])) ? $_POST['notes'] : '';
        $country = (isset($_POST['country'])) ? $_POST['country'] : '';
        $state = (isset($_POST['state'])) ? $_POST['state'] : '';
        $modified_by = (isset($_POST['modifiedBy'])) ? $_POST['modifiedBy'] : '';
        $modified_from = (isset($_POST['modifiedFrom'])) ? $_POST['modifiedFrom'] : '';
        $modified_to = (isset($_POST['modifiedTo'])) ? $_POST['modifiedTo'] : '';
        $created_by = (isset($_POST['createdBy'])) ? $_POST['createdBy'] : '';
        $created_from = (isset($_POST['createdFrom'])) ? $_POST['createdFrom'] : '';
        $created_to = (isset($_POST['createdTo'])) ? $_POST['createdTo'] : '';
        $fast_subject = (isset($_POST['fastSubject'])) ? $_POST['fastSubject'] : '';
        $page_name = (isset($_POST['pageName'])) ? $_POST['pageName'] : '';
        $restricted = (isset($_POST['restricted'])) ? $_POST['restricted'] : '';

        $is_restricted_collections = 0;
        if (($page_name == "restricted" && $restricted == 'true') && is_hsdl_restricted_participant() || is_ip_user()) {
            $is_restricted_collections = 1;
        }

        $args = array(
            'type' => $type,
            'start' => $start,
            'rows' => $rows,
            'sort' => $sort,
            'searchterm' => $search_term,
            'subjects' => $subjects,
            'creator' => $creator,
            'series' => $series,
            'lists' => $lists,
            'format' => $format,
            'language' => $language,
            'publisher' => $publisher,
            'tabsection' => $tabsection,
            'advanced' => $advanced,
            'all' => $all,
            'any' => $any,
            'exact' => $exact,
            'without' => $without,
            'from' => $from,
            'to' => $to,
            'mdt' => $mdt,
            'releaseFlag' => $release_flag,
            'docID' => $doc_id,
            'url' => $url,
            'source' => $source,
            'notes' => $notes,
            'country' => $country,
            'state' => $state,
            'modifiedBy' => $modified_by,
            'modifiedFrom' => $modified_from,
            'modifiedTo' => $modified_to,
            'createdBy' => $created_by,
            'createdFrom' => $created_from,
            'createdTo' => $created_to,
            'fastSubject' => $fast_subject,
            'restricted' => $is_restricted_collections,
        );

        $decode_response = $this->search_api_call($args);

        $documents = [];
        $num_found = 0;
        $is_load_more = false;

        if ($decode_response) {
            $documents = $decode_response->data->response->docs;
            $num_found = $decode_response->data->response->numFound;
            $start = $decode_response->data->response->start;
            $is_load_more = $num_found > ($rows + $start);
            $this_class = $this;
            $release_flags = $this->release_flags;
        }

        ob_start();
        include plugin_dir_path(dirname(__FILE__)) . 'public/partials/hsdl-mdt-search-public-search-result-common-display.php';
        $load_more_output = ob_get_contents();
        ob_end_clean();
        $data = array(
            'record_start' => $start,
            'result_html' => $load_more_output,
            'is_load_more' => $is_load_more,
            'num_found' => $num_found,
        );

        $result = json_encode($data);
        echo $result;
        die();
    }

    /**
     * Search API Call function
     * @param $params
     * @return mixed
     */
    public function search_api_call($params)
    {
        $url = $this->api_end_point;
        $query_string = http_build_query($params);
        $url .= "/search?$query_string";
        $args = array('method' => 'POST');

        return $this->send_api_request($url, $args);
    }

    /**
     * handle api requests
     * @param $url
     * @param $args
     * @return false|mixed
     */
    public function send_api_request($url, $args)
    {
        $args = $this->set_header($args);
        $response = wp_remote_request($url, $args);

        if (is_wp_error($response) || $response['response']['code'] != 200) {
            Hsdl_Mdt_Search::write_log($response);
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $decode_response = json_decode($body);

        if ($decode_response->status != 'success' || $decode_response->message->code != 200) {
            Hsdl_Mdt_Search::write_log($decode_response);
            return false;
        }
        return $decode_response;
    }

    /**
     * Add custom class to body tag
     * @param $classes
     * @return mixed
     */
    public function custom_class($classes)
    {
        $search = intval(get_query_var('search'));
        $details = intval(get_query_var('details'));
        $cr = intval(get_query_var('cr'));
        if ($search) {
            $classes[] = 'page-template-search-results';
        }
        if ($details) {
            $classes[] = 'page-template-search-results-detail';
        }
        if ($cr) {
            $classes[] = 'page-template-critical-release';
        }
        return $classes;
    }

    /**
     * Overwrite title tag text
     * @param $title
     * @return string
     */
    public function overwrite_title($title)
    {
        $divider = '';
        $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri_segments = explode('/', $uri_path);

        if ($uri_segments[1] == 'search-result') {
            $title = "Search Result"; // Return empty
            $divider = '|';
        }
        if ($uri_segments[1] == 'abstract') {
            $title = "Abstract"; // Return empty
            $divider = '|';
        }
        if ($uri_segments[1] == 'critical-releases') {
            $title = "Critical Releases"; // Return empty
            $divider = '|';
        }
        if ($uri_segments[1] == 'previous-critical-releases') {
            $title = "Previous Critical Releases"; // Return empty
            $divider = '|';
        }
        if ($uri_segments[1] == 'alerts-subscriptions') {
            $title = "Alerts and Subscriptions"; // Return empty
            $divider = '|';
        }
        // Return my custom title
        return sprintf("%s $divider %s", $title, get_bloginfo('name'));
    }

    /**
     * Get advanced search search in dropdown field values
     * @return false|mixed
     */
    public function get_search_in_dropdwon_values()
    {
        $url = $this->api_end_point;
        $url .= '/search/meta';
        $args = array('method' => 'GET');
        $response = $this->send_api_request($url, $args);
        if ($response) {
            return $response->data;
        }
    }

    /**
     * Get staff search dropdown values
     * @return mixed
     */
    public function get_staff_search_settings_values()
    {
        $url = $this->api_end_point . '/search/staff-search-options';
        $args = array('method' => 'GET');
        $response = $this->send_api_request($url, $args);
        if ($response) {
            return $response->data;
        }
    }

    /**
     * get fast subject field option values
     * @return mixed
     */
    public function get_fast_subjects_list()
    {
        $url = $this->api_end_point . '/search/fast-subject';
        $args = array('method' => 'GET');
        $response = $this->send_api_request($url, $args);
        if ($response) {
            return $response->data;
        }
    }

    /**
     * check download permissions
     * @param $document_id
     * @return mixed
     */
    public function check_download_permission($document_id)
    {
        if (!empty($document_id)) {
            $url = $this->api_end_point;
            $params = array('docID' => $document_id);
            $query_string = http_build_query($params);
            $url .= "/document/downloadPermission?$query_string";
            $args = array('method' => 'POST');

            $response = $this->send_api_request($url, $args);

            if ($response) {
                return $response->data->allowDownload;
            }
        }
    }

    /**
     * get listed on tag list for given document id
     * @param $document_id
     * @return mixed
     */
    public function get_details_page_listed_on_tags($document_id)
    {
        if (!empty($document_id)) {
            $url = $this->api_end_point;
            $url .= "/document/listed-on/$document_id";
            $args = array('method' => 'GET');
            $response = $this->send_api_request($url, $args);

            if ($response) {
                return $response->data;
            }
        }
    }

    public function alerts_and_subscriptions()
    {
        if (is_auth_user()) {
            $auth_user = (isset($_SESSION['auth_user'])) ? $_SESSION['auth_user'] : '';

            $page = intval(get_query_var('d'));
            $alert_id = get_query_var('id') ?: '';
            $action = get_query_var('action') ?: '';

            $type = get_query_var('type') ?: '';
            $creator = get_query_var('creator') ?: '';
            $format = get_query_var('format') ?: '';
            $language = get_query_var('language') ?: '';
            $publisher = get_query_var('publisher') ?: '';
            $tabsection = get_query_var('tabsection') ?: '';
            $searchterm = get_query_var('searchterm') ?: '';
            $any = get_query_var('any') ?: '';
            $exact = get_query_var('exact') ?: '';
            $without = get_query_var('without') ?: '';
            $from = get_query_var('from') ?: '';
            $to = get_query_var('to') ?: '';

            $current_url = $this->get_current_url();
            $confirm_url = $current_url . "&action=confirm";

            if ($action == 'add') {
                $display_text = '';

                if ($searchterm) {
                    $display_text .= $this->generate_alert_describe_text('all', $searchterm);
                } else {
                    $display_text .= $this->generate_alert_describe_text('empty', 'N/A');
                }
                $display_text .= $this->generate_alert_describe_text('any', $any);
                $display_text .= $this->generate_alert_describe_text('exact', $exact);
                $display_text .= $this->generate_alert_describe_text('without', $without);
                $display_text .= $this->generate_alert_describe_text('searchfield', $type);
                $display_text .= $this->generate_alert_describe_text('publisher', $publisher);
                $display_text .= $this->generate_alert_describe_text('tabsection', $tabsection);
                $display_text .= $this->generate_alert_describe_text('creator', $creator);
                $display_text .= $this->generate_alert_describe_text('format', $format);
                $display_text .= $this->generate_alert_describe_text('language', $language);
                $display_text .= $this->generate_alert_describe_text('from', $from);
                $display_text .= $this->generate_alert_describe_text('to', $to);

                $params = array(
                    'all' => $searchterm,
                    'any' => $any,
                    'exact' => $exact,
                    'without' => $without,
                    'from' => $from,
                    'to' => $to,
                    'creator' => $creator,
                    'publisher' => $publisher,
                    'tabsection' => $tabsection,
                    'searchterm' => $searchterm,
                    'format' => $format,
                    'language' => $language,
                    'type' => $type,
                    'action' => 'add',
                    'advanced' => 'true',
                );
                $search_query_string = http_build_query($params);
                $current_search_url = home_url('/search-result?search=1&');
                $current_search_url = $current_search_url . $search_query_string;
            }

            if ($action == 'confirm') {
                $params = array(
                    'field_all' => $searchterm,
                    'field_any' => $any,
                    'field_exact' => $exact,
                    'field_without' => $without,
                    'begindate' => $from,
                    'enddate' => $to,
                    'tabsection' => $tabsection,
                    'searchfield' => $type,
                    'publisher' => $publisher,
                    'format' => $format,
                    'language' => $language,
                    'creator' => $creator,
                );
                $response = $this->add_alert($params);
                if ($response) {
                    $this->send_alert_mail();
                    $action = 'success';
                }
            }
            if ($action == 'delete' && !empty($alert_id)) {
                $response = $this->delete_alert($alert_id);
                if ($response) {
                    $redirect_url = remove_query_arg(array('id', 'action'), $current_url);
                    header("Location: $redirect_url");
                    exit;
                }
            }
            $this_class = $this;
            $alerts = [];
            $alerts_subscriptions = $this->get_alerts_subscriptions();
            if ($alerts_subscriptions) {
                $alerts = $alerts_subscriptions->alerts;
            }
            if ($page) {
                include plugin_dir_path(dirname(__FILE__)) . 'public/partials/hsdl-mdt-search-public-alerts-and-subscriptions-display.php';
                die();
            }
        }
    }

    public function get_alerts_subscriptions()
    {

        $url = $this->api_end_point;
        $url .= "/alert";
        $args = array('method' => 'GET');
        $response = $this->send_api_request($url, $args);

        if ($response) {
            return $response->data;
        }
    }

    public function add_alert($params)
    {

        $url = $this->api_end_point;
        $url .= "/alert";

        $args = array('method' => 'POST', 'body' => $params);
        $response = $this->send_api_request($url, $args);

        if ($response) {
            return $response->data;
        }
    }

    public function delete_alert($alert_id)
    {
        if (!empty($alert_id)) {
            $url = $this->api_end_point;
            $url .= "/alert/$alert_id";

            $args = array('method' => 'DELETE');
            $response = $this->send_api_request($url, $args);

            if ($response) {
                return $response->data;
            }
        }
    }

    public function send_alert_mail()
    {
        return true;
    }

    public function generate_alert_describe_text($key, $value)
    {
        $display_text = '';
        if (!empty($key) && !empty($value)) {
            switch ($key) {
                case 'all':
                    $display_text = "Searching for terms: ALL ($value) ";
                    break;
                case 'any':
                    $display_text = "ANY ($value) ";
                    break;
                case 'exact':
                    $display_text = "EXACT ($value) ";
                    break;
                case 'without':
                    $display_text = "WITHOUT ($value) ";
                    break;
                case 'searchfield':
                    if ($value != 'all') {
                        $display_text = "in $value ";
                    } else {
                        $display_text = "in full text and any metadata ";
                    }
                    break;
                case 'publisher':
                    $display_text = "where publisher: $value ";
                    break;
                case 'tabsection':
                    $display_text = "where special collection or resource type is: $value ";
                    break;
                case 'creator':
                    $display_text = "where author is: $value ";
                    break;
                case 'format':
                    $display_text = "where format is: $value ";
                    break;
                case 'language':
                    $display_text = "where language is: $value ";
                    break;
                case 'from':
                    $display_text = "published from: $value ";
                    break;
                case 'to':
                    $display_text = "to $value";
                    break;
                default:
                    $display_text = "Searching for anything ";
            }
        }
        return $display_text;
    }

    public function get_previous_releases_by_year_and_month($month, $year)
    {
        $url = $this->api_end_point;
        $url .= "/nomination/previous-list";

        $args = array('method' => 'POST', 'body' => array('month' => $month, 'year' => $year));
        $response = $this->send_api_request($url, $args);


        if ($response) {
            return $response->data;
        }
    }

    public function display_previous_releases()
    {
        $page = intval(get_query_var('pcr'));
        $get_month = (!empty(get_query_var('month'))) ? get_query_var('month') : date('F');
        $get_year = (!empty(get_query_var('year'))) ? get_query_var('year') : date('Y');
        $get_sort = (!empty(get_query_var('sort'))) ? get_query_var('sort') : 'DESC';
        $rows = (!empty(get_query_var('rows'))) ? get_query_var('rows') : 30;
        $start = (!empty(get_query_var('start'))) ? get_query_var('start') : 0;
        $crid = (!empty(get_query_var('crid'))) ? get_query_var('crid') : '';
        $months = array();
        $years = array();
        $num_found = 0;
        $this_class = $this;
        $documents = [];
        $num_found = 0;
        $is_load_more = false;
        $restricted = 'false';

        for ($i = 1; $i <= 12; $i++) {
            $timestamp = mktime(0, 0, 0, $i, 1, date("Y"));
            $months[date('F', $timestamp)] = date('F', $timestamp);
        }
        for ($i = 1970; $i <= date('Y'); $i++) {
            $years[$i] = $i;
        }
        krsort($years);
        $args = array(
            "release_id" => $crid,
            "year" => $get_year,
            "month" => $get_month,
            "start" => $start,
            "rows" => $rows,
            "sort" => $get_sort,
        );
        $filter_checkbox_data = $this->get_previous_releases_by_year_and_month($get_month, $get_year);
        $decode_response = $this->get_previous_releases_result($args);

        if ($decode_response) {
            $documents = $decode_response->response->docs;
            $num_found = $decode_response->response->numFound;
            $start = $decode_response->response->start;
            $is_load_more = $num_found > ($rows + $start);
        }
        if ($page) {
            include plugin_dir_path(dirname(__FILE__)) . 'public/partials/hsdl-mdt-search-public-previous-critical-releases-display.php';
            die();
        }
    }

    public function get_previous_releases_result($data)
    {
        if (!empty($data)) {
            $release_id = (isset($data['release_id'])) ? $data['release_id'] : '';
            $start = (isset($data['start'])) ? $data['start'] : '';
            $rows = (isset($data['rows'])) ? $data['rows'] : '';
            $sort = (isset($data['sort'])) ? $data['sort'] : '';
            $month = (isset($data['month'])) ? $data['month'] : '';
            $year = (isset($data['year'])) ? $data['year'] : '';

            $url = $this->api_end_point;
            $url .= "/critical-release/search";

            $params = array(
                "nominationID" => $release_id,
                "year" => $year,
                "month" => $month,
                "start" => $start,
                "rows" => $rows,
                "sort" => $sort,
            );

            $args = array('method' => 'POST', 'body' => $params);
            $response = $this->send_api_request($url, $args);

            if ($response) {
                return $response->data;
            }
        }
    }

    public function get_previous_releases_result_ajx()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], "hsdl_ajax_submit")) {
            exit("No naughty business please");
        }

        $start = (isset($_POST['start'])) ? $_POST['start'] : 0;
        $rows = (isset($_POST['rows'])) ? $_POST['rows'] : 30;
        $sort = (isset($_POST['sort'])) ? $_POST['sort'] : 'date';
        $month = (isset($_POST['month'])) ? $_POST['month'] : '';
        $year = (isset($_POST['year'])) ? $_POST['year'] : '';
        $crid = (isset($_POST['crid'])) ? $_POST['crid'] : '';
        $documents = [];
        $num_found = 0;
        $is_load_more = false;

        $args = array(
            "nominationIDs" => array($crid),
            "year" => $year,
            "month" => $month,
            "start" => $start,
            "rows" => $rows,
            "sort" => $sort,
        );
        $decode_response = $this->get_previous_releases_result($args);


        if ($decode_response) {
            $documents = $decode_response->response->docs;
            $num_found = $decode_response->response->numFound;
            $start = $decode_response->response->start;
            $is_load_more = $num_found > ($rows + $start);
            $this_class = $this;
        }

        ob_start();
        include plugin_dir_path(dirname(__FILE__)) . 'public/partials/hsdl-mdt-search-public-search-result-common-display.php';
        $load_more_output = ob_get_contents();
        ob_end_clean();
        $data = array(
            'record_start' => $start,
            'result_html' => $load_more_output,
            'is_load_more' => $is_load_more,
            'num_found' => $num_found,
        );

        $result = json_encode($data);
        echo $result;
        die();
    }

    public function download_search_results()
    {
        $download = intval(get_query_var('download'));
        $file = get_query_var('file');
        $base_path = env('DOCUMENT_BASE_PATH') ?: '/var/www/html';

        if ($download && !empty($file)) {
            $download_location = $base_path . '/search_results/' . $file;
            $list = explode('/', $file);
            if (is_array($list)) {
                $file_name = $list[1];
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream', true, 200);
                header('Content-Disposition: attachment; filename="' . $file_name . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($download_location)); //Absolute URL
                ob_clean();
                flush();
                readfile($download_location); //Absolute URL
                exit();
            }
        }
    }
}
