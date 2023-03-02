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
  prompt_data text DEFAULT NULL,
  created_at datetime NOT NULL DEFAULT current_timestamp()
  PRIMARY KEY (id)
  ) $charset;
  ";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

  dbDelta($table);
}

register_activation_hook(__FILE__, 'database_creation');

function save_prompt($prompt_data)
{

  global $wpdb;
  $table_name = $wpdb->prefix . 'prompts';
  //  $sql = $conn->query("SHOW databases");
  $get_id = $wpdb->get_var("SELECT `id` FROM $table_name ORDER BY id DESC LIMIT 1");
  $wpdb->insert(
    $wpdb->prefix . 'risks',
    [
      'id' => $get_id + 1,
      'prompt_data' => $prompt_data,
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
  add_submenu_page("get-started", "RiskCurb Option 3", "Manage Prompts", 4, "Manage Prompts Admin", "option3");
  add_submenu_page("get-started", "RiskCurb Option 2", "Reports", 4, "Reports", "option2");
}
function exampleMenu()
{
  if(isset($_POST['prompt_data'])){
    save_prompt($_POST['prompt_data']);
    exit(json_encode(array('status'=>200,'message'=>'data saved successfully')));
  }

  $html = "";
  $html .= '
    
    <div class="panel panel-primary">
    <div class="col-md-6 col-lg-5">
      <div class="row"></div>
      <div class="clearfix"></div>
      <div class="row">
        <div class="maillist">
          <table class="mailmessages table">
          <p>Loaded Questions</p>
            <tbody class="messages_wrapper">
            
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-7">
      <div class="maillist">
      <p>Loaded Answers</p>

       <div class="maillistmsgs">
      
        </div>
        <form class="reply_form">
        <div class="form-group">
          <label>Quick Replay</label>
          <textarea class="form-control answer_input" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Answer</button>
        
        <button type="reset" class="btn btn-default">Reset</button>
        
        </form>
        <br />
        <br />
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
  
    ';

  echo $html;
}
function option1()
{

  
}
function option2()
{
  global $wpdb;
  
    // $table_name = $wpdb->prefix . 'created_sites';
    
    // $sql_data = $wpdb->get_results("SELECT * FROM $table_name");


}
function option3()
{
 
  $html = "";
  $html .= '
    
    <div class="panel panel-primary">
    <div class="col-md-6 col-lg-5">
      <div class="row"></div>
      <div class="clearfix"></div>
      <div class="row">
        <div class="maillist">
          <table class="mailmessages table">
            <tbody class="prompts_wrapper">
            
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-7">
      <div class="maillist">
       <div class="review_prompt">
      
        </div>
        <form class="add_prompt_forms">
        <div class="form-group">
          <label>Quick Add Prompt</label>
          <textarea class="form-control add_prompt_forms_input" placeholder="please enter first prompt" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Prompt</button>
        
        <button type="reset" class="btn btn-default">Reset</button>
        
        </form>
        <br />
        <br />
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
  
    ';

  echo $html;
}

