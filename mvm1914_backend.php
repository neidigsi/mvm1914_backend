<?php

/**
 * Plugin Name: RESTful API - Musikverein 1914 Münster e. V.
 * Plugin URI: https://github.com/neidigsi/mvm1914_backend
 * Description:
 * Version: 1.0
 * Author: Simon Neidig
 * Author URI: https://github.com/neidigsi
 */

 require_once 'api/v1/routes/post/get-post.php';
 require_once 'api/v1/routes/event/get-event.php';
 require_once 'api/v1/routes/contact/get-contact.php';
 require_once 'api/v1/routes/gallery/get-gallery.php';
 require_once 'api/v1/routes/group/get-group.php';

 add_action('rest_api_init', function() {
    register_rest_route('mvm/v1', 'post', [
        'methods' => 'GET',
        'callback' => 'posts_get',
    ]);
 });

 add_action('rest_api_init', function() {
    register_rest_route('mvm/v1', 'post/(?P<id>[a-zA-Z0-9-]+)', [
        'methods' => 'GET',
        'callback' => 'single_post_get',
    ]);
 });

 add_action('rest_api_init', function() {
    register_rest_route('mvm/v1', 'event', [
        'methods' => 'GET',
        'callback' => 'events_get',
    ]);
 });

 add_action('rest_api_init', function() {
    register_rest_route('mvm/v1', 'event/(?P<id>[a-zA-Z0-9-]+)', [
        'methods' => 'GET',
        'callback' => 'single_event_get',
    ]);
 });

 add_action('rest_api_init', function() {
    register_rest_route('mvm/v1', 'gallery', [
        'methods' => 'GET',
        'callback' => 'galleries_get',
    ]);
 });

 add_action('rest_api_init', function() {
    register_rest_route('mvm/v1', 'gallery/(?P<id>[a-zA-Z0-9-]+)', [
        'methods' => 'GET',
        'callback' => 'single_gallery_get',
    ]);
 });

 add_action('rest_api_init', function() {
    register_rest_route('mvm/v1', 'contact', [
        'methods' => 'GET',
        'callback' => 'contacts_get',
    ]);
 });

 add_action('rest_api_init', function() {
    register_rest_route('mvm/v1', 'group', [
        'methods' => 'GET',
        'callback' => 'groups_get',
    ]);
 });