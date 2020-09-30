<?php

function posts_get() {
    $resp = new WP_REST_Response('Posts');
    $resp->set_status(200);
    $resp->header('Content-type', 'application/json');
    return $resp;
}

function single_post_get() {
    $resp = new WP_REST_Response('Single Post');
    $resp->set_status(200);
    $resp->header('Content-type', 'application/json');
    return $resp;
}