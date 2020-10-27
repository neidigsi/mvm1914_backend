<?php

function mvm1914_install() {
	global $wpdb;

    $contact_table_name = $wpdb->prefix . 'mvm1914_contact';
    $group_table_name = $wpdb->prefix . 'mvm1914_group';
    $location_table_name = $wpdb->prefix . 'mvm1914_location';
	
	$charset_collate = $wpdb->get_charset_collate();

	$contact_sql = "CREATE TABLE $contact_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(55),
        person varchar(55),
        mail varchar(55),
		PRIMARY KEY  (id)
    ) $charset_collate;";
    
    $group_sql = "CREATE TABLE $group_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(55),
        management varchar(55),
        time varchar(55),
        location_id mediumint(9),
		PRIMARY KEY  (id)
    ) $charset_collate;";
    
    $location_sql = "CREATE TABLE $location_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(55),
        street varchar(55),
        city varchar(55),
        state varchar(55),
        country varchar(55),
        plz varchar(55),
        latitude varchar(55),
        longitude varchar(55),
		PRIMARY KEY  (id)
    ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $contact_sql );
    dbDelta( $group_sql );
    dbDelta( $location_sql );
}