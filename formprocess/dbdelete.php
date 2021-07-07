<?php 
//define('WP_USE_THEMES', false);
//require_once(BASE_PATH . 'wp-load.php');
//include wp-config or wp-load.php
$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
if (file_exists($root.'/wp-load.php')) {
// WP 2.6
require_once($root.'/wp-load.php');
} else {
// Before 2.6
require_once($root.'/wp-config.php');
}

$oilid = $_POST['oilid']; 

global $wpdb;

$tablename = $wpdb->prefix . "internallinks";

$wpdb->delete($tablename, array('id'=>$oilid));


