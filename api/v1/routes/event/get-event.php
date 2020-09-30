<?php

function events_get() {
    $resp = new WP_REST_Response('Events');
    $resp->set_status(200);
    $resp->header('Content-type', 'application/json');
    return $resp;
}

function single_event_get() {
    $resp = new WP_REST_Response('Single Event');
    $resp->set_status(200);
    $resp->header('Content-type', 'application/json');
    return $resp;
}