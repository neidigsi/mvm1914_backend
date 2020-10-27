<?php


/**
 * This method handles GET-requests to the route "/group". It returns all groups as they are in the database.
 * 
 * @return  WP_REST_Response
 *      - 200   :   Everything went well, an array of all groups will be returned.
 *      - 500   :   An Exception was thrown, the exception-message will be returned in a json-format.
 */
function groups_get()
{
    global $wpdb;
    $body = array();
    $groups = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "mvm1914_group");
    foreach ($groups as $group) {
        $new_group["name"] = $group->name;
        $new_group["managment"] = $group->management;
        $new_group["time"] = $group->time;

        $location = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "mvm1914_location WHERE id=\"" . $group->location_id . "\";");
        if (count($location) > 0) {
            $new_location["name"] = $location[0]->name;
            $new_location["street"] = $location[0]->street;
            $new_location["city"] = $location[0]->city;
            $new_location["state"] = $location[0]->state;
            $new_location["country"] = $location[0]->country;
            $new_location["plz"] = $location[0]->plz;
            $new_location["latitude"] = $location[0]->latitude;
            $new_location["longitude"] = $location[0]->longitude;
            $new_group["location"] = $new_location;
        }

        array_push($body, $new_group);
    }

    $resp = new WP_REST_Response($body);
    $resp->set_status(200);
    $resp->header('Content-type', 'application/json');
    return $resp;
}
