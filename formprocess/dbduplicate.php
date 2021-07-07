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

$oil_link = $_POST['oillink']; 
$oil_keyword = $_POST['oilkeyword']; 
$oil_newtab = $_POST['oilnewtab']; 
$oil_follow = $_POST['oilfollow']; 
$oilid = $_POST['oilid']; 
$oilfollow = false;
$oilnewtab = false;

if ($oil_follow == "False"){
    $oilfollow = false;
}
else{
     $oilfollow = true;   
}
if ($oil_newtab == "False"){
    $oilnewtab = false;
}
else{
     $oilnewtab = true;   
}

global $wpdb;

$tablename = $wpdb->prefix . "internallinks";

$results = $wpdb->get_results( "SELECT * FROM $tablename"); 

if(!empty($results)){
    foreach($results as $row){  
        if ($row->key_word == $oil_keyword){
            $oil_keyword = $oil_keyword . " Copy";
        }
    }
}

$wpdb->insert($tablename, array(

    "link" => $oil_link,
    "follow" => $oilfollow,
    "key_word" => $oil_keyword,
    "newtab" => $oilnewtab,

)
);
//$wpdb->query("INSERT INTO `$tablename`(`link`, `keyword`, `follow`, `newtab`) VALUES ("$oil_link, $oil_keyword, $oil_follow, $oil_newtab")" );


