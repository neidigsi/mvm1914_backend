<?php

/**
 * Plugin Name: RESTful API - Musikverein 1914 Münster e. V.
 * Plugin URI: https://github.com/neidigsi/mvm1914_backend
 * Description:
 * Version: 1.0
 * Author: Simon Neidig
 * Author URI: https://github.com/neidigsi
 */

 function posts() {

     $resp = new WP_REST_Response('It works!');
     $resp->set_status(202);
     $resp->header('Content-type', 'application/json');
     return $resp;
 }

 add_action('rest_api_init', function() {
    register_rest_route('mvm/v1', 'posts', [
        'methods' => 'GET',
        'callback' => 'posts',
    ]);
 });