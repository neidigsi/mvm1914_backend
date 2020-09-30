<?php

function contacts_get() {
    $resp = new WP_REST_Response('Contacts');
    $resp->set_status(200);
    $resp->header('Content-type', 'application/json');
    return $resp;
}