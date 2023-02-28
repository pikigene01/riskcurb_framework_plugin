<?php
/*
Plugin Name: RiskCurb Framework Plugin
Plugin URI: https://riskcurb.com
Description: This is a multisite plugin for https://wordpress.org/ users to create 
    new site and manage custom plugins
Author: RiskCurb
Author URI: riskcurb@curbsoftware.com
Version: 1.2.0
*/

if (!defined('ABSPATH')) {
  echo "Nothing l can do when called directly";
  die;
}

//create site table for admin

function database_creation()
{
  global $wpdb;
  $table_name = 'prompts';
  $site_details = $wpdb->prefix . $table_name;
  $charset = $wpdb->get_charset_collate;

  $table = "CREATE TABLE " . $site_details . "
  (
  id int NOT NULL,
  prompt text DEFAULT NULL,
  answer text DEFAULT NULLL,
  response varchar(255) DEFAULT NULL,
  created_at datetime NOT NULL DEFAULT current_timestamp()
  PRIMARY KEY (id)
  ) $charset;
  ";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

  dbDelta($table);
}

register_activation_hook(__FILE__, 'database_creation');

function save_prompt($prompt, $answer, $response)
{

  global $wpdb;
  $table_name = $wpdb->prefix . 'prompts';
  //  $sql = $conn->query("SHOW databases");
  $get_id = $wpdb->get_var("SELECT `id` FROM $table_name ORDER BY id DESC LIMIT 1");
  $wpdb->insert(
    $wpdb->prefix . 'risks',
    [
      'id' => $get_id + 1,
      'prompt' => $prompt,
      'answer' => $answer,
      'response' => $response
    ]
  );
}
function add_plugin_stylesheet()
{
  wp_enqueue_style('style1', plugins_url('/includes/css/app.css', __FILE__));
  wp_enqueue_style('style2', plugins_url('/includes/vendor/bootstrap/css/bootstrap.min.css', __FILE__));
  wp_enqueue_style('style3', plugins_url('/includes/css/adminnine.css', __FILE__));
  wp_enqueue_style('style4', plugins_url('/includes/vendor/font-awesome/css/font-awesome.min.css', __FILE__));
}

add_action('admin_print_styles', 'add_plugin_stylesheet');
function my_scripts()
{

  wp_enqueue_script('script2', plugins_url('/includes/vendor/jquery/jquery.min.js', __FILE__));
  wp_enqueue_script('script3', plugins_url('/includes/vendor/bootstrap/js/bootstrap.min.js', __FILE__));
  // wp_enqueue_script('script9', 'https://cdn.socket.io/4.5.4/socket.io.min.js');
  wp_enqueue_script('script9', plugins_url('/includes/js/socket.io.min.js', __FILE__));
  wp_enqueue_script('script4', plugins_url('/includes/js/adminnine.js', __FILE__));
  wp_enqueue_script('script1', plugins_url('/includes/js/app.js', __FILE__));
  wp_enqueue_script('script5', plugins_url('/includes/vendor/datatables/js/jquery.dataTables.min.js', __FILE__));
  wp_enqueue_script('script6', plugins_url('/includes/vendor/datatables-plugins/dataTables.bootstrap.min.js', __FILE__));
  wp_enqueue_script('script7', plugins_url('/includes/vendor/datatables-responsive/dataTables.responsive.js', __FILE__));
  wp_enqueue_script('script8', 'https://kit.fontawesome.com/d5c483be9d.js');
}
add_action('admin_enqueue_scripts', 'my_scripts');

add_action("admin_menu", "addMenu");

function addMenu()
{
  add_menu_page("Welcome", "RiskCurb Framework", 4, "get-started", "exampleMenu");
  add_submenu_page("get-started", "RiskCurb Option 1", "Get Started", 4, "getstarted", "option1");
  add_submenu_page("get-started", "RiskCurb Option 2", "Reports", 4, "Reports", "option2");
  add_submenu_page("get-started", "RiskCurb Option 3", "Manage Sites", 4, "create-new-site", "option3");
}
function exampleMenu()
{
  $html = "";
  $html .= "
  
    ";
  echo $html;
  echo "Thank you for using Riskcurb membership plugin contact details gene@curbsoftware.com";
}
function option1()
{

  $html = "";
  $html .= '
    <script type="text/javascript">
    const genes = document.querySelectorAll(".gene");
    genes.forEach((gene)=>{
    alert("tuby")
        gene.innerHTML = "nfdhinfdinbfd";
    })
    </script>
    <div class="gene">
    Gene
    </div>
    ';

  echo $html;
  echo "Getting started page for plugin ";
}
function option2()
{
  global $wpdb;
  
    // $table_name = $wpdb->prefix . 'created_sites';
    
    // $sql_data = $wpdb->get_results("SELECT * FROM $table_name");


}
function option3()
{
  
}

