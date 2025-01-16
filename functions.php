<?php
declare(strict_types=1);

// Include necessary files
require_once get_template_directory() . '/includes/api-endpoints.php';
require_once get_template_directory() . '/includes/custom-post-types.php';
require_once get_template_directory() . '/includes/helpers.php';

// Enable featured images
add_theme_support('post-thumbnails');

// Add featured image URL to REST API response
add_action('rest_api_init', 'add_featured_image_url_to_api');
function add_featured_image_url_to_api(): void
{
    register_rest_field('post', 'featured_image_url', array(
        'get_callback' => 'get_featured_image_url',
        'schema' => null,
    ));
}

// Enable CORS
add_action('init', 'handle_preflight');
function handle_preflight(): void
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
    if ('OPTIONS' == $_SERVER['REQUEST_METHOD']) {
        status_header(200);
        exit();
    }
}
