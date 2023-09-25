<?php

use function Env\env;

if (!empty($col_focus)) {
    if ($col_focus == 'Collections') {
        $endpoint = 'collection';
        $post_type = 'collections';
    }
    if ($col_focus == 'In Focus') {
        $endpoint = 'infocus';
        $post_type = 'in_focus';
    }
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    );

    $query = new WP_Query($args);

    $url = env('API_ENDPOINT') ?: 'http://localhost:3000/api/v1';

    $args = array('method' => 'GET');
    $token = (isset($_SESSION['jwt']) && !empty($_SESSION['jwt'])) ? $_SESSION['jwt'] : '';
    if ($token) {
        $args['headers'] = array(
            'Authorization' => 'Bearer ' . $token
        );
    }
    $url .= "/$endpoint";
    $response = send_api_request($url, $args);
    $facets_item_array = [];
    $posts_array = [];
    if ($response) {
        $facets = $response->data;
        if (!empty($facets)) {
            foreach ($facets as $facet) {
                while ($query->have_posts()) : $query->the_post();
                    if ($facet->type == get_the_title()) {
                        $facets_item_array[] = $facet;
                        $posts_array[get_the_ID()] = get_the_title();
                    }
                endwhile;
                wp_reset_postdata();
            }
        }
    }
    $facets_item_array_json = json_encode($facets_item_array, JSON_FORCE_OBJECT);

    if ($col_focus == 'Collections') {
        echo do_shortcode("[mdt_collections facets='$facets_item_array_json']");
    }
    if ($col_focus == 'In Focus') {
        echo do_shortcode("[mdt_infocus facets='$facets_item_array_json']");
    }
}