<?php

/**
 * This method handles GET-requests to the route "/gallery". It returns all galleries in an shortened version.
 * 
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
 *      - 404   :   No gallery with given id was found in the database.
 *      - 500   :   An Exception was thrown, the exception-message will be returned in a json-format.
 */
function single_gallery_get($data)
{
    try {
        $gridGallery = new GridGallery_Galleries_Model_Galleries();
        $resources = new GridGallery_Galleries_Model_Resources();
        $photos = new GridGallery_Photos_Model_Photos();
        $galleries = $gridGallery->getAll();;
        $body = array();

        foreach ($galleries as $gallery) {
            if ($gallery->id == $data["id"]) {
                $body["id"] = $gallery->id;
                $body["title"] = $gallery->title;

                $resourcesData = $resources->getByGalleryId($gallery->id);
                $tmp_photos = $photos->getPhotos($resourcesData);

                $imageLinks = array();
                $imagePreviewLinks = array();

                foreach ($tmp_photos as $photo) {
                    array_push($imageLinks, $photo->attachment["sizes"]["thumbnail"]["url"]);
                    array_push($imagePreviewLinks, $photo->attachment["url"]);
                }

                $body["imagePreviewLinks"] = $imagePreviewLinks;
                $body["imageLinks"] = $imageLinks;

                $resp = new WP_REST_Response($body);
                $resp->set_status(200);
                $resp->header('Content-type', 'application/json');
                return $resp;
            }
        }

        $body["message"] = "No gallery with given id!";
        $body["id"] = $data["id"];

        $resp = new WP_REST_Response($body);
        $resp->set_status(404);
        $resp->header('Content-type', 'application/json');
        return $resp;
    } catch (Exception $e) {
        $resp = new WP_REST_Response(generate_error_body($e));
        $resp->set_status(500);
        $resp->header('Content-type', 'application/json');
        return $resp;
    }
}