<?php
/*
Plugin Name: Custom Search
Description: Custom search endpoint for WordPress REST API
Version: 1.0
Author: Your Name
*/

// Resigter custom endpoint
function custom_search_endpoint_init() {
    register_rest_route( 'custom/v1', '/search/', array(
        'methods' => 'POST',
        'callback' => 'custom_search_handler',
    ) );
}
add_action( 'rest_api_init', 'custom_search_endpoint_init' );

// Handle CORS
add_action( 'rest_pre_serve_request', function( $value ) {
    header( 'Access-Control-Allow-Origin: *' );
    header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
    header( 'Access-Control-Allow-Credentials: true' );
    header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
}, 15 );

// Handle Search=
function custom_search_handler( $data ) {
    $json_data = json_decode( $data->get_body(), true );

    // Mock data
    $items = array(
        array("content" => "Viet Nam is beautiful", "category" => "trip", "tag" => "top_management"),
        array("content" => "America is large", "category" => "onboard", "tag" => "supply_department"),
        array("content" => "Korea is so good", "category" => "onsite", "tag" => "financial_department"),
        array("content" => "Campuchia is fanacy", "category" => "trip", "tag" => "desiogn_department"),
        array("content" => "Indo is wonderful", "category" => "onsite", "tag" => "marketing_department")
    );

    $results = array();

    // Find in list item matching unique key
    foreach ($items as $item) {
        if (strpos($item['content'], $json_data['keyword']) !== false || $item['category'] == $json_data['category'] || $item['tag'] == $json_data['tag']) {
            $results[] = $item;
        }
    }

    return rest_ensure_response( $results );
}