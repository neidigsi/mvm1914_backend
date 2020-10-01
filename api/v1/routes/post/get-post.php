<?php

/**
 * This method handles GET-requests to the route "/post". It returns all posts in an shortened version.
 * 
 * Reponses:
 *      - 200   :   Everything went well, an array of all posts will be returned.
 *      - 500   :   An Exception was thrown, the exception-message will be returned in a json-format.
 */
function posts_get()
{
    try {
        $args = array(
            'numberposts' => 100
        );

        $posts = get_posts($args);
        $body = array();

        foreach ($posts as $post) {
            $new_post["id"] = $post->ID;
            $new_post["title"] = $post->post_title;
            $new_post["extract"] = generate_extract($post->post_content, 35);
            $new_post["date"] = $post->post_date;
            $new_post["categories"] = get_post_categories($post->ID);
            $new_post["author"] = get_the_author_meta("display_name", $post->post_author);
            $new_post["thumbnail_link"] = get_the_post_thumbnail_url($post->ID);

            array_push($body, $new_post);
        }
        $resp = new WP_REST_Response($body);
        $resp->set_status(200);
        $resp->header('Content-type', 'application/json');
        return $resp;
    } catch (Exception $e) {
        $resp = new WP_REST_Response(generate_error_body($e));
        $resp->set_status(500);
        $resp->header('Content-type', 'application/json');
        return $resp;
    }
}

/**
 * This method handles GET-requests to the route "/post/{id}". It returns extended information about the post
 * with the id given as path-parameter in the request.
 * 
 * Reponses:
 *      - 200   :   Everything went well, the extended post will be returned.
 *      - 500   :   An Exception was thrown, the exception-message will be returned in a json-format.
 */
function single_post_get($data)
{
    try {
        $post = get_post($data["id"]);

        $new_post["id"] = $post->ID;
        $new_post["title"] = $post->post_title;
        $new_post["url"] = get_permalink($post->ID);
        $new_post["content"] = $post->post_content;
        $new_post["date"] = $post->post_date;
        $new_post["author"] = get_the_author_meta("display_name", $post->post_author);
        $new_post["thumbnail_link"] = get_the_post_thumbnail_url($post->ID);

        $resp = new WP_REST_Response($new_post);
        $resp->set_status(200);
        $resp->header('Content-type', 'application/json');
        return $resp;
    } catch (Exception $e) {
        $resp = new WP_REST_Response(generate_error_body($e));
        $resp->set_status(500);
        $resp->header('Content-type', 'application/json');
        return $resp;
    }
}

/**
 * This method generates an array of strings. The content of the strings are 
 * the names of the categories the post with given id is in. 
 */
function get_post_categories($post_id)
{
    $res = array();
    $categories = get_the_category($post_id);
    foreach ($categories as $category) {
        array_push($res, $category->name);
    }
    return $res;
}
