<?php

/**
 * This method handles GET-requests to the route "/event". It returns all events in an shortened version.
 * 
 * @param   Object  $data   The data received in request.
 * @return  WP_REST_Response
 *      - 200   :   Everything went well, an array of all events will be returned.
 *      - 500   :   An Exception was thrown, the exception-message will be returned in a json-format.
 */
function events_get()
{
    try {
        $args = array(
            'post_type'      => 'aec_events',
            'numberposts'    => 100,
            'meta_query' => array(
                array(
                    'key'     => 'end_date_time',
                    'value'      => current_time('mysql'),
                    'compare' => '>=',
                    'type'    => 'DATETIME'
                )
            )
        );

        $events = get_posts($args);
        $body = array();

        foreach ($events as $event) {
            $new_event["id"] = $event->ID;
            $new_event["title"] = $event->post_title;
            $new_event["thumbnailLink"] = get_the_post_thumbnail_url($event->ID);
            $new_event["startDate"] = get_post_meta($event->ID, 'start_date_time', true);
            $new_event["endDate"] = get_post_meta($event->ID, 'end_date_time', true);
            $new_event["allDayEvent"] = get_post_meta($event->ID, 'all_day_event', true) === '1' ? true : false;
            $new_event["location"] = get_venue($event->ID);
            $new_event["categories"] = get_event_categories($event->ID);
            array_push($body, $new_event);
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
 * This method handles GET-requests to the route "/event/{id}". It returns extended information about the event
 * with the id given as path-parameter in the request.
 * 
 * @param   Object  $data   The data received in request.
 * @return  WP_REST_Response
 *      - 200   :   Everything went well, the extended event will be returned.
 *      - 500   :   An Exception was thrown, the exception-message will be returned in a json-format.
 */
function single_event_get($data)
{
    try {
        $event = get_post($data["id"]);

        $new_event["title"] = $event->post_title;
        $new_event["description"] = $event->post_content;
        $new_event["url"] = get_permalink($event->ID);
        $new_event["thumbnailLink"] = get_the_post_thumbnail_url($event->ID);
        $new_event["startDate"] = get_post_meta($event->ID, 'start_date_time', true);
        $new_event["endDate"] = get_post_meta($event->ID, 'end_date_time', true);
        $new_event["allDayEvent"] = get_post_meta($event->ID, 'all_day_event', true) === '1' ? true : false;
        $new_event["location"] = get_venue($event->ID);

        $resp = new WP_REST_Response($new_event);
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
 * 
 * @param   int     The id of the event.
 * @return  array   An array with all category-names the event with given id is in.
 */
function get_event_categories($event_id)
{
    $res = array();
    $categories = get_the_terms($event_id, 'aec_categories');
    foreach ($categories as $category) {
        array_push($res, $category->name);
    }
    return $res;
}

/**
 * This method generates a venue-object of the venue the event wil take place.
 * 
 * @param   int     The id of the event.
 * @return  object  An venue object with the needed information about it.
 */
function get_venue($event_id)
{
    $venue["id"] = get_post_meta($event_id, 'venue_id', true);
    $venue["name"] = get_the_title($venue["id"]);
    $venue["street"] = get_post_meta($venue["id"], 'address', true);
    $venue["plz"] = get_post_meta($venue["id"], 'pincode', true);
    $venue["city"] = get_post_meta($venue["id"], 'city', true);
    $venue["state"] = get_post_meta($venue["id"], 'state', true);
    $venue["country"] = get_post_meta($venue["id"], 'country', true);
    $venue["latitude"] = get_post_meta($venue["id"], 'latitude', true);
    $venue["longitude"] = get_post_meta($venue["id"], 'longitude', true);
    return $venue;
}
