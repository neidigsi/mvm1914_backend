<?php

/**
 * This method handles GET-requests to the route "/contact". It returns all contact-persons as they are in the database.
 * 
 * @return  WP_REST_Response
 *      - 200   :   Everything went well, an array of all contact-persons will be returned.
 *      - 500   :   An Exception was thrown, the exception-message will be returned in a json-format.
 */
function contacts_get()
{
    try {
        global $wpdb;
        $body = array();
        $persons = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "mvm1914_contact");
        foreach ($persons as $person) {
            $new_person["name"] = $person->name;
            $new_person["person"] = $person->person;
            $new_person["mail"] = $person->mail;
            array_push($body, $new_person);
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
