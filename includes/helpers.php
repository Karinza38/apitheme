<?php
declare(strict_types=1);
function get_featured_image_url($post)
{
    if ($post['featured_media']) {
        $img = wp_get_attachment_image_src($post['featured_media'], 'full');
        return $img[0];
    }
    return false;
}

function prepare_post_for_response($post): array
{
    return array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'content' => $post->post_content,
        'excerpt' => $post->post_excerpt,
        'date' => $post->post_date,
        'slug' => $post->post_name,
        'featured_image' => get_the_post_thumbnail_url($post->ID, 'full'),
        'categories' => wp_get_post_categories($post->ID, array('fields' => 'names')),
        'tags' => wp_get_post_tags($post->ID, array('fields' => 'names')),
    );
}
