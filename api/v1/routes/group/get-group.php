<?php

function groups_get() {
    $resp = new WP_REST_Response('Groups');
    $resp->set_status(200);
    $resp->header('Content-type', 'application/json');
    return $resp;
}