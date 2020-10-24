<?php

/**
 * This method handles GET-requests to the route "/gallery". It returns all galleries in an shortened version.
 * 
 * @param   Object  $data   The data received in request.
 * @return  WP_REST_Response
 *      - 200   :   Everything went well, an array of all galleries will be returned.
 *      - 500   :   An Exception was thrown, the exception-message will be returned in a json-format.
 */
function galleries_get()
{
    try {
        $gridGallery = new GridGallery_Galleries_Model_Galleries();
        $resources = new GridGallery_Galleries_Model_Resources();
        $photos = new GridGallery_Photos_Model_Photos();
        $attachment = new GridGallery_Galleries_Attachment();
        $galleries = $gridGallery->getAll();;
        $body = array();

        foreach ($galleries as $gallery) {
            $new_gallery["id"] = $gallery->id;
            $new_gallery["title"] = $gallery->title;

            $resourcesData = $resources->getByGalleryId($gallery->id);
            $tmp_photos = $photos->getPhotos($resourcesData);
            $tmp_photos = $tmp_photos[0]->attachment;

            $mil = $tmp_photos["date"];
            $seconds = $mil / 1000;
            $year = date("Y", $seconds);
            $new_gallery["thumbnailLink"] = $tmp_photos["sizes"]["thumbnail"]["url"];

            if ($year != "1970") {
                if ($body[$year] == null) {
                    $body[$year]["year"] = $year;
                    $body[$year]["galleries"] = array();
                }

                array_push($body[$year]["galleries"], $new_gallery);
            }
        }

        $res = array();
        foreach ($body as $body_part) {
            array_push($res, $body_part);
        }
        $resp = new WP_REST_Response($res);
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
 * This method handles GET-requests to the route "/gallery/{id}". It returns extended information about the gallery
 * with the id given as path-parameter in the request.
 * 
 * @param   Object  $data   The data received in request.
 * @return  WP_REST_Response
 *      - 200   :   Everything went well, the extended gallery will be returned.
 *      - 500   :   An Exception was thrown, the exception-message will be returned in a json-format.
 */
function single_gallery_get($data)
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
