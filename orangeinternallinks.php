<?php 
/**
 * @package Orange Internal Links Plugin
 */
/*
Plugin Name: Orange Internal Links Plugin
Plugin URI: https://quickandeasylighting.com/
Description: Orange Internal Links Plugin
Version:1.0.0
Author: Josiah Orange
Author URI: http://orangedesigns.co.uk/
License: GPLv2 or later
Text Domain: orangeinternallinks
*/

//security code

if ( ! defined('ABSPATH') ) {
    die;
}

defined( 'ABSPATH' ) or die('Nothing to see here');


function internallinks_activate()
{   
    echo "bobobbobo";
    $root = dirname(dirname(dirname(dirname(__FILE__))));
    if (file_exists($root.'/wp-load.php')) {
    // WP 2.6
    require_once($root.'/wp-load.php');
    } else {
    // Before 2.6 
    require_once($root.'/wp-config.php');
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . "internallinks";
    if ( $wpdb->get_var('SHOW TABLES LIKE ' . $table_name) != $table_name){

        $sql = 'CREATE TABLE ' . $table_name . '(
        id INTEGER(10) UNSIGNED AUTO_INCREMENT, 
        hit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        key_word VARCHAR(255),
        link VARCHAR(255),
        follow BOOLEAN,
        newtab BOOLEAN,
        PRIMARY KEY (id) )';
    
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        add_option('internallinks_database_version', '1.0');
    } 


}

function internallinks_deactivate()
{
  

}


//personal code
add_filter('the_content', 'link_words');

function link_words($content){  
    $root = dirname(dirname(dirname(dirname(__FILE__))));
    if (file_exists($root.'/wp-load.php')) {
    // WP 2.6
    require_once($root.'/wp-load.php');
    } else {
    // Before 2.6 
    require_once($root.'/wp-config.php');
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . "internallinks";
    $results = $wpdb->get_results( "SELECT * FROM $table_name"); 
    if(!empty($results)){
        foreach($results as $row){
            if($row->follow == false && $row->newtab == false){
                $meta = "target=\"_blank\" rel=\"nofollow noreferrer noopener\"";
            }
            else if($row->follow == true && $row->newtab == true){
                $meta = "rel=\"dofollow\"";
            }   
            else if ($row->follow == false && $row->newtab == true){
                $meta = "rel=\"nofollow noreferrer noopener\"";
            }
            else{
                $meta = "target=\"_blank\"";
            }
            /*
            $pos = strpos($content,$row->key_word);
            if ($pos !== false) {
                $content = substr_replace($content,"<a " . $meta . " href=\"" . $row->link . "\">" . $row->key_word . "</a>",$pos,strlen($row->key_word));
            }*/
            $pattern = "/\b" . $row->key_word . "\b/";
            $content = preg_replace($pattern, "<a " . $meta . " href=\"" . $row->link . "\">" . $row->key_word . "</a>", $content, 1);
            

            //$content = preg_replace($row->key_word,"<a " . $meta . " href=\"" . $row->link . "\">" . $row->key_word . "</a>", $content, 1);

        }
    }
    return $content;

}




//plugin code main

class OrangeInternalLinks
{


    //settings page stuff 

    public $plugin;

    function __construct(){


    }

    function register(){

        //script enqueues
        add_action('admin_enqueue_scripts', array( $this, 'enqueue'));
        
      
        //admin and settings
        $this->plugin = plugin_basename( __FILE__ );
        add_action( 'admin_menu', array( $this, 'add_admin_pages'));
        add_filter("plugin_action_links_$this->plugin", array( $this, 'settings_link') );

    }

//admin and settings

    
    public function settings_link( $links ){
        $settings_link = '<a href="admin.php?page=orangeinternallinks">Settings</a>';
        array_push( $links, $settings_link);
        return $links;

    }

    public function add_admin_pages(){
        add_menu_page('Orange Internal Links Plugin', 'Orange Internal Links',  'manage_options', 'orangeinternallinks', array( $this, 'admin_index'), 'dashicons-screenoptions', 110);
    }

    public function admin_index(){
        require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
     }



//activate and deactivate and uninstall


    function activate(){
        
        flush_rewrite_rules();
        internallinks_activate();

    }
    function deactivate() {
        flush_rewrite_rules();
        internallinks_deactivate();


    }


//script enqueues

    function enqueue(){
        
        wp_enqueue_style('oilstyle', plugins_url( '/assets/orangeilstyle.css', __FILE__), array(), false, 'all');
        wp_enqueue_script('oilscript', plugins_url( '/assets/orangeilscript.js', __FILE__));

 
    }






}

//starting the plugin

if (class_exists('OrangeInternalLinks')){
   
    $orangeInternalLinks = new OrangeInternalLinks();
    $orangeInternalLinks->register();




  

}





//activation
register_activation_hook( __FILE__, array($orangeInternalLinks, 'activate'));


//deactivation
register_deactivation_hook( __FILE__, array($orangeInternalLinks, 'deactivate'));

//uninstall



