<?php
/**
 *plugin Name: 'Biruk Students Expenses'
 * Description: "custom functionality to mange students, projects and expenses"
 * Author: "Biruk Asmare, Muse"
 * * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once( plugin_dir_path( __FILE__ ) . 'Biruk_researcher_route.php');
require_once( plugin_dir_path( __FILE__ ) . 'Biruk_expense_route.php');
require_once( plugin_dir_path( __FILE__ ) . 'Biruk_options_route.php');

require_once( plugin_dir_path( __FILE__ ) . 'Expenses_UI.php');
require_once( plugin_dir_path( __FILE__ ) . 'Options_UI.php');
require_once( plugin_dir_path( __FILE__ ) . 'LoginUI.php');
require_once( plugin_dir_path( __FILE__ ) . 'Researcher_UI.php');
//require( plugin_dir_path( __FILE__ ) . 'Biruk_Database.php');

    function biruk_plugin_activate(){
        $biruk_db= new BirukDatabase();
        $resp= $biruk_db->setup_database();
    }
    function biruk_plugin_deactivate(){
        $biruk_db= new BirukDatabase();
        $resp= $biruk_db->clean_db();
        
    }
    function biruk_plugin_uninstall(){

    }

    function setup(){
        //instantiate a class that creates the required tables
        //in wp database
        //require_once( plugin_dir_path( __FILE__ ) . 'Biruk_researcher_route.php');
        require_once( plugin_dir_path( __FILE__ ) . 'Biruk_Database.php');
        //$biruk_db= new BirukDatabase();
        //$biruk_db->clean_db();
        //$resp= $biruk_db->setup_database();
        //echo "<pre>";
            //print_r($resp);
        //echo "</pre>";
        echo "<h1> Short code for the researchers functionality</h1> : <h2> [researchers_dispaly] </h2> </br>";
        echo "<h1> Short code for the expense functionality</h1> : <h2> [expenses_fetch] </h2> </br>";
        echo "<h1> Short code for the Login functionality</h1> : <h2> [biruk_login_form] </h2> </br>";
        echo "<h1> Short code for the Editing options</h1> : <h2> [biruk_show_options] </h2> </br>";
        
        
       
    }

//short codes to add custom forms in to pages



function register_shortcodes(){
    
    add_shortcode('researchers_dispaly', 'display_all_researchers');
    //display_expenses_Registration_form
   // add_shortcode('expenses_dispaly', 'display_expenses_Registration_form');
    //show_expenses()
    add_shortcode('expenses_fetch', 'show_expenses');
    //short code for adding login functionality
    add_shortcode('biruk_login_form', 'show_login_form');
    //short code for adding options functionality
    add_shortcode('biruk_show_options', 'show_options');
    
 }


function login_functionalities ( $items, $args ) {
	$html="";
    if (is_user_logged_in()) {
        $current_user= wp_get_current_user();
		$avatar=get_avatar($current_user->ID,$size = 20);
		$html.= '<li class="mega-menu-item "><a class="mega-menu-link" href="/students"> Researchers </a></li>';
		$html.= '<li class="mega-menu-item "><a class="mega-menu-link" href="/expenses"> Expenses </a></li>';
		 $html.= '<li class="mega-menu-item "><a class="mega-menu-link" href="#"> Welcome, <span>'.$avatar.'</span> '.$current_user->user_login.'</a></li>';
		$html.= '<li class="mega-menu-item "><a class="mega-menu-link" href="'.wp_logout_url( get_page_link() ).'"> Logout </a></li>';
	}else{
		$html='<li class="mega-menu-item "> <a href="#" type="button" class="btn btn-info mega-menu-link" data-toggle="modal" data-target="#loginModal"> Login </a>';
		
	}
	 $items .= $html;
    return $items;
	}

 add_action( 'init', 'register_shortcodes');
add_action( 'wp_head' , 'add_login_modal');
add_filter( 'wp_nav_menu_items', 'login_functionalities', 10, 2 );
 add_action('wp_loaded',function(){
    show_admin_bar(false);
 });
function biruk_add_menu_page(){
    add_menu_page( "Experiment page", "Students_expenses_plugin", "manage_options", "Biruk plugin page","setup" );

}

add_action('admin_menu', 'biruk_add_menu_page');
add_action('wp_ajax_nopriv_biruk_custom_login','biruk_login');
function load_custom_styles() {
    wp_enqueue_style( 'bootstrap-css','https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
    wp_enqueue_style( 'bootstrap-validator-css','//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/css/bootstrapValidator.min.css');
   wp_enqueue_style('font-owsome','https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
   wp_enqueue_style('jquery-ui-style','//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
   wp_enqueue_style('jquery-ui-datatables_style','//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css');
}
function load_custom_scripts() {
    
  wp_register_script('handler','https://code.jquery.com/jquery-3.5.1.min.js',array('jquery'),'3.5.1',true);
   wp_register_script('jquery_UI','https://code.jquery.com/ui/1.12.1/jquery-ui.min.js',array('jquery'),'1.12.1',true);
   wp_register_script('popper','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',array('jquery'),'4.1.3',true);
   wp_register_script('bootstrap-js','https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js',array('jquery'),'4.1.3',true);
   wp_register_script('jquery-validator-js','https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.js',array(),'1.19.1',true);
   wp_register_script('jquery-validator-extras-js','https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/additional-methods.min.js',array(),'1.19.1',true);
   wp_register_script( 'jquery-ui-datatables_js', "//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js", array(), '1.10.21', true );
   
   wp_register_script( 'biruk_plugin_js', plugin_dir_url( __FILE__ ) . 'js/main.js', array(), '1.0.0', true );
   wp_register_script( 'biruk_plugin_expense_js', plugin_dir_url( __FILE__ ) . 'js/Expense.js', array(), '1.0.0', true );
   //
   

  wp_enqueue_script( 'handler');
 wp_enqueue_script( 'jquery_UI');
   wp_enqueue_script( 'popper');
   wp_enqueue_script( 'bootstrap-js');
   wp_enqueue_script( 'jquery-validator-js');
   wp_enqueue_script( 'jquery-validator-extras-js' );
   wp_enqueue_script('jquery-ui-datatables_js');
   wp_enqueue_script( 'biruk_plugin_js');
   wp_enqueue_script( 'biruk_plugin_expense_js');

}
//load CSS styles from bootstrap
add_action('wp_enqueue_scripts','load_custom_styles',10000);
add_action('wp_enqueue_scripts','load_custom_scripts');

//activation
register_activation_hook( __FILE__,  'biruk_plugin_activate');

//deactivation
register_deactivation_hook( __FILE__, 'biruk_plugin_deactivate');



