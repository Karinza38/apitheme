<?php
declare(strict_types=1);

class Custom_REST_API
{
    const string API_NAMESPACE = 'blog/v1';

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes(): void
    {
        register_rest_route(self::API_NAMESPACE, '/recent-posts', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_recent_posts'],
            'permission_callback' => '__return_true',
            'args' => $this->get_pagination_args(),
        ]);

        register_rest_route(self::API_NAMESPACE, '/search', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'search_posts'],
            'permission_callback' => '__return_true',
            'args' => array_merge(
                $this->get_pagination_args(),
                [
                    'term' => [
                        'required' => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ],
                ]
            ),
        ]);
    }

    private function get_pagination_args(): array
    {
        return [
            'per_page' => [
                'default' => 10,
                'sanitize_callback' => 'absint',
            ],
            'page' => [
                'default' => 1,
                'sanitize_callback' => 'absint',
            ],
        ];
    }

    public function get_recent_posts(WP_REST_Request $request): WP_REST_Response
    {
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $request->get_param('per_page'),
            'paged' => $request->get_param('page'),
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        return $this->get_posts_response($args);
    }

    public function search_posts(WP_REST_Request $request): WP_REST_Response
    {
        $args = [
            'post_type' => 'post',
            's' => $request->get_param('term'),
            'posts_per_page' => $request->get_param('per_page'),
            'paged' => $request->get_param('page'),
        ];

        return $this->get_posts_response($args);
    }

    private function get_posts_response(array $args): WP_REST_Response
    {
        $query = new WP_Query($args);
        $posts = array_map([$this, 'prepare_post_for_response'], $query->posts);

        $response = new WP_REST_Response($posts, 200);
        $response->header('X-WP-Total', $query->found_posts);
        $response->header('X-WP-TotalPages', ceil($query->found_posts / $args['posts_per_page']));

        return $response;
    }

    private function prepare_post_for_response(WP_Post $post): array
    {
        // Implement this method to format the post data as needed
        return [
            'id' => $post->ID,
            'title' => get_the_title($post),
            // Add more fields as needed
        ];
    }
}

new Custom_REST_API();
