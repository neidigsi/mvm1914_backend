<?php

function galleries_get() {
    $resp = new WP_REST_Response('Galleries');
    $resp->set_status(200);
    $resp->header('Content-type', 'application/json');
    return $resp;
}

function single_gallery_get() {
    $resp = new WP_REST_Response('Single Gallery');
    $resp->set_status(200);
    $resp->header('Content-type', 'application/json');
    return $resp;
}