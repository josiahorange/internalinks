<?php

/**
 * Trigger this file on Plugin uninstall
 * @package Orange Internal Links Plugin
 */

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
{
    $root = dirname(dirname(dirname(dirname(__FILE__))));
    if (file_exists($root.'/wp-load.php')) {
    // WP 2.6
    require_once($root.'/wp-load.php');
    } else {
    // Before 2.6 
    require_once($root.'/wp-config.php');
    }
    
    global $wpdb;
    $wpdb->query( "DROP TABLE IF EXISTS" .  $wpdb->prefix . "internallinks" );
    delete_option("internallinks_db_version");

    die;
 }

 //Clear Database Store Data

 
