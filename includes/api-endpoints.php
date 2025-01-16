<?php
declare(strict_types=1);

// Register custom endpoints
add_action('rest_api_init', 'register_custom_endpoints');

function register_custom_endpoints(): void
{
    // Recent posts endpoint
    register_rest_route('blog/v1', '/recent-posts', array(
        'methods' => 'GET',
        'callback' => 'get_recent_posts',
        'args' => array(
            'per_page' => array(
                'default' => 5,
                'sanitize_callback' => 'absint',
            ),
            'page' => array(
                'default' => 1,
                'sanitize_callback' => 'absint',
            ),
        ),
    ));

    // Search endpoint
    register_rest_route('blog/v1', '/search', array(
        'methods' => 'GET',
        'callback' => 'search_posts',
        'args' => array(
            'term' => array(
                'required' => true,
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'per_page' => array(
                'default' => 10,
                'sanitize_callback' => 'absint',
            ),
            'page' => array(
                'default' => 1,
                'sanitize_callback' => 'absint',
            ),
        ),
    ));
}

function get_recent_posts($request)
{
    $per_page = $request->get_param('per_page');
    $page = $request->get_param('page');

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $query = new WP_Query($args);
    $posts = $query->posts;

    $data = array();
    foreach ($posts as $post) {
        $data[] = prepare_post_for_response($post);
    }

    $total_posts = $query->found_posts;
    $max_pages = ceil($total_posts / $per_page);

    $response = new WP_REST_Response($data, 200);
    $response->header('X-WP-Total', $total_posts);
    $response->header('X-WP-TotalPages', $max_pages);

    return $response;
}

function search_posts($request)
{
    $search_term = $request->get_param('term');
    $per_page = $request->get_param('per_page');
    $page = $request->get_param('page');

    $args = array(
        'post_type' => 'post',
        's' => $search_term,
        'posts_per_page' => $per_page,
        'paged' => $page,
    );

    $query = new WP_Query($args);
    $posts = $query->posts;

    $data = array();
    foreach ($posts as $post) {
        $data[] = prepare_post_for_response($post);
    }

    $total_posts = $query->found_posts;
    $max_pages = ceil($total_posts / $per_page);

    $response = new WP_REST_Response($data, 200);
    $response->header('X-WP-Total', $total_posts);
    $response->header('X-WP-TotalPages', $max_pages);

    return $response;
}
