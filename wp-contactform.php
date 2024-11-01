<?php
/*
Plugin Name: FreshClicks Contact Form
Plugin URI: http://www.freshclicks.net/wordpress/wordpress-responsive-contact-form/
Description: FreshClicks Contact Form is a JQUERY, AJAX, Twitter Bootstrap, and Google Analytics Event tracking enabled form for users to contact you. It uses default bootstrap classes but allows them to be modified from the admin screen. It can be implemented on a page, post, or as a widget. If your not using Bootstrap you can dynamically load it from their CDNs and have the option to enable Google Analytics tracking.
Author: FreshClicks
Author URI: http://freshClicks.net
Version: 2.1
*/

define('wprcf_DIR', dirname(__FILE__));
require_once(wprcf_DIR.'/class-widget.php');

// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

// Guess the location
$wprcfpluginpath = WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';

load_plugin_textdomain('wprcf',$wprcfpluginpath);

// enqueue and localise scripts
wp_enqueue_script( 'wprcf2-validation', plugin_dir_url( __FILE__ ) . 'wprcf2-validation.js', array( 'jquery' ) );
wp_localize_script( 'wprcf2-validation', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
// THE AJAX ADD ACTIONS
add_action( 'wp_ajax_the_ajax_hook', 'the_results_function' );
add_action( 'wp_ajax_nopriv_the_ajax_hook', 'the_results_function' ); // need this to serve non logged in users

//form security
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

// THE SUCCESS Results
function the_results_function(){
	/* this area is very simple but being serverside it affords the possibility of retreiving data from the server and passing it back to the javascript function */
	$name = test_input($_POST['inputName']);
	$email = test_input($_POST['inputEmail']);
	$phone = test_input($_POST['inputPhone']);
	$message = test_input($_POST['inputArea']);


	//Data from the options table
	$success_msg =  get_option('wprcf_success_msg');
	$recipient = get_option('wprcf_email');
	$subject = get_option('wprcf_subject');
	$your_name_txt = get_option('wprcf_yourname_txt');
	$your_email_txt =  get_option('wprcf_email_txt');
	$your_phone_txt = get_option('wprcf_phone_txt');
	$your_message_txt = get_option('wprcf_message_txt');

	//format the email
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "From: $name <$email>\r\n";
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\"\r\n";
	$data = "$your_name_txt: $name \r\n\r\n ";
	$data .= "$your_email_txt: $email \r\n\r\n ";
	$data .= "$your_phone_txt: $phone \r\n\r\n ";
	$data .= "$your_message_txt:\r\n\r\n $message \r\n\r\n ";

	echo '<div id="form-message" class="alert alert-success">';
	echo $success_msg;
	echo '<br><span class="pull-right"><small>Powered by <a href="http://www.freshclicks.net/wordpress/wordpress-responsive-contact-form/">FreshClicks Forms</a></small></span>';
	echo '</div>';
	//send email
	wp_mail($recipient, $subject, $data);
	//mail($recipient, $subject, $data, $headers);

	// echo $subject." | ".$your_name_txt . $name . " | ".$your_email_txt.$email." | ".$your_phone_txt.$phone." | ".$your_message_txt." | ".$message." | ".$recipient;// this is passed back to the javascript function
	die();// wordpress may print out a spurious zero without this - can be particularly bad if using json
}

// calls the form
function freshclicks_form($atts){

    //Safeguarding Against CSRF
	$token = md5(uniqid(rand(), TRUE));
	$_SESSION['token'] = $token;
	$_SESSION['token_time'] = time();


	//if the theme doesn't already use bootstrap
	if (! function_exists('add_Bootstrap')) {
		$wprcf_bootstrap_option = get_option('wprcf_bootstrap_option');
		if ($wprcf_bootstrap_option) {
			function add_Bootstrap() {
				print '<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">';
			}
			add_action('wp_footer', 'add_Bootstrap');
		}
	}
	extract( shortcode_atts( array('form_type' => 'full'), $atts) );
	//logic to determine if it is  module or a post

	if ($form_type == 'widget') {
		$formID = "contact-mini";
		$inputRow = "form-group";
		$inputClass = "form-control input-sm";
		$labelClass = "sr-only";
		$spamCheckRow = "form-group";
		$wprcf_button_place_class = "form-group";
		$buttonClass = get_option('wprcf_button_class');
		$buttonClass = $buttonClass . " btn-sm";
		$formClass = "form";
		$labelRow = "6";
	}else {
		$formID = "contact-form";
		$formClass = get_option('wprcf_form_class');
		$inputRow = get_option('wprcf_input_row_class');
		$inputClass =  get_option('wprcf_input_class');
		$labelClass = get_option('wprcf_label_class');
		$spamCheckRow = "col-md-8";
		$input_col =  get_option('wprcf_input_col_class');
		$wprcf_button_place_class = get_option('wprcf_button_place_class');
		$buttonClass = get_option('wprcf_button_class');
		$formClass = get_option('wprcf_form_class');
		$labelRow = get_option('wprcf_textarea_row');
	}
	$the_form = '
    <div id="contact-form">';
	if (get_option('wprcf_intro_msg') !="") {
		$the_form .= '
        <div id="intro" class="' .$inputRow. '">' . $wprcf_intro_msg . '</div>';
	}
	$the_form .= '
        <form id="theForm" class="'.$formClass.'" role="form" name="contact-form">
            <div id="name-section" class="' .$inputRow. '">
                <label for="inputName" class="'.$labelClass.'">' . __(get_option('wprcf_yourname_txt'), 'wprcf') . '</label>
                <div class="' .$input_col. '">
                    <input type="text" class=" '.$inputClass.'" id="inputName" name="inputName" placeholder="' . __(get_option('wprcf_yourname_txt'), 'wprcf') . '' . __(get_option('wprcf_required_txt'), 'wprcf') . '">
                </div>
            </div>
            <div id="email-section" class="'. $inputRow .'">
                <label for="inputEmail" class="'.$labelClass.'">' . __(get_option('wprcf_email_txt'), 'wprcf') . '</label>
                <div class="' .$input_col. '">
                    <input type="email" class="'.$inputClass.'" id="inputEmail" name="inputEmail" placeholder="' . __(get_option('wprcf_email_txt'), 'wprcf') . '' . __(get_option('wprcf_required_txt'), 'wprcf') . '">
                </div>
            </div>
            <div id="email-section" class="'. $inputRow .'">
                <label for="inputPhone" class="'.$labelClass.'">' . __(get_option('wprcf_phone_txt'), 'wprcf') . '</label>
                <div class="' .$input_col. '">
                    <input type="text" class=" '.$inputClass.'" id="inputPhone" name="inputPhone" placeholder="' . __(get_option('wprcf_phone_txt'), 'wprcf') . '">
                </div>
            </div>
            <div id="area-section" class="'. $inputRow .'">
                <label for="inputMessage" class="'.$labelClass.'">' . __(get_option('wprcf_message_txt'), 'wprcf') . '</label>
                <div class="' .$input_col. '">
                    <textarea name="inputArea" id="inputArea" class=" '.$inputClass.'" rows="'.$labelRow.'" placeholder="' . __(get_option('wprcf_message_txt'), 'wprcf') . '"></textarea>
                </div>
            </div>
            ';
	// Insert spam check logic
	$spam_check = get_option('wprcf_show_spamcheck');
	if ($spam_check) {
		$rand1 = rand(1,10);
		$rand2 = rand(0,10);
		$_SESSION['wprcf_spamanswer'] = $rand1+$rand2;
		$the_form .= '
			<div class="'. $inputRow.'" id="spam-section">
				<label for="inputSpamCheck" class="'.$labelClass.'">' . __(get_option('wprcf_spamcheck_txt'), 'wprcf') . '</label>
        		<div class="'.$spamCheckRow.' row">
					<div class="col-xs-6 text-right pull-left">
          					' . __(get_option('wprcf_spamcheck1_txt'), 'wprcf') . ' '.$rand1.' ' . __(get_option('wprcf_spamcheck2_txt'), 'wprcf') . ' '.$rand2.' ' . __(get_option('wprcf_spamcheck3_txt'), 'wprcf') . '
          			</div>
					<div class="col-xs-6 pull-right">
          				<input type="text" class="'.$inputClass.'" id="inputSpam" name="inputSpam">
          			</div>
        		</div>
			</div>';
	}// end spam check logic

	$the_form .='
			<div class="'. $inputRow .'">
				<div class="' .$wprcf_button_place_class. '">
				    <input name="action" type="hidden" value="the_ajax_hook" />&nbsp; <!-- this puts the action the_ajax_hook into the serialized form -->
					<input id="submit_button" class="'.$buttonClass.'" value = "' . __(get_option('wprcf_sendbtn_txt'), 'wprcf') . '" type="button" onClick="submit_me();" />
					<input type="hidden" id="inputSpamCheck" name="inputSpamCheck" value="'.$_SESSION['wprcf_spamanswer'].'" >
        		</div>
			</div>
        </form>
    </div>';
	if (get_option('wprcf_ending_msg') !="") {
		$the_form .= '
	    <div id="ending" class="'. $inputRow .'">
       		<div class="row">
       			<div class="' .$inputRow. '">' . __(get_option('wprcf_ending_msg'), 'wprcf') . '</div>
       		</div>
      	</div>';
	}
	$the_form .= '<script>';
	// event tracking logic
	if (get_option('wprcf_show_event_tracking')) {
		$the_form .='var show_event_tracking = "' . __(get_option('wprcf_show_event_tracking'), 'wprcf') . '";';
	}
	// event category logic
	if (get_option('wprcf_event_cat')) {
		$the_form .='var event_cat = "' . __(get_option('wprcf_event_cat'), 'wprcf') . '";';
	}
	// event value logic
	if (get_option('wprcf_event_value') != null) {
		$the_form .='var event_value = "' . __(get_option('wprcf_event_value'), 'wprcf') . '";';
	}
	$the_form .= '</script>';
	return $the_form;
} // #end form

/*Can't use WP's function here, so lets use our own*/
if (! function_exists('wprcf_get_ip')) {
	function wprcf_get_ip() {
		if (isset($_SERVER)) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				$ip_addr = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$ip_addr = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$ip_addr = $_SERVER["REMOTE_ADDR"];
			}
		} else {
			if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
				$ip_addr = getenv( 'HTTP_X_FORWARDED_FOR' );
			} else if ( getenv( 'HTTP_CLIENT_IP' ) ) {
				$ip_addr = getenv( 'HTTP_CLIENT_IP' );
			} else {
				$ip_addr = getenv( 'REMOTE_ADDR' );
			}
		}
		return $ip_addr;
	}
}
//add a setting option from wp plugin page
if (! function_exists('wprcf_filter_plugin_actions')) {
	function wprcf_filter_plugin_actions( $links, $file ){
		//Static so we don't call plugin_basename on every plugin row.
		static $this_plugin;
		if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
	
		if ( $file == $this_plugin ){
			$settings_link = '<a href="options-general.php?page=wp-responsive-contact-form/options-contactform.php">' . __('Settings') . '</a>';
			array_unshift( $links, $settings_link ); // before other links
		}
		return $links;
	}
}

//admin plugin list
function wprcf_add_options_page() {
	global $wprcfpluginpath;
	//add link to settings
	add_options_page('FreshClicks Contact Form Options', 'FreshClicks Form', 'manage_options', $wprcfpluginpath.'options-contactform.php');
	add_filter( 'plugin_action_links', 'wprcf_filter_plugin_actions', 10, 2 );
}

/* Action calls for all functions */

add_action('admin_menu', 'wprcf_add_options_page');
add_shortcode('freshclicks_form', 'freshclicks_form');

/**
 * Add the freshclicks.net RSS feed to the WordPress dashboard
 */
if (!function_exists('freshclcicks_db_widget')) {
	function freshclicks_text_limit( $text, $limit, $finish = ' [&hellip;]') {
		if( strlen( $text ) > $limit ) {
	    	$text = substr( $text, 0, $limit );
			$text = substr( $text, 0, - ( strlen( strrchr( $text,' ') ) ) );
			$text .= $finish;
		}
		return $text;
	}
	//FreshClicks marketing tool
	function freshclcicks_db_widget($image = 'normal', $num = 3, $excerptsize = 250, $showdate = true) {
		require_once(ABSPATH.WPINC.'/rss.php');  
		if ( $rss = fetch_rss( 'http://feeds.feedburner.com/FreshClicks' ) ) {
			print '<div class="rss-widget">';
			print '<a href="http://freshclicks.net" title="Go to FreshClicks"><img src="'. plugins_url( '' , __FILE__ ) . '/FreshClicksLogo.png" class="alignright" alt="FreshClicks"/></a>';
			print '<ul>';
			$rss->items = array_slice( $rss->items, 0, $num );
			foreach ( (array) $rss->items as $item ) {
				print '<li>';
				print '<a class="rsswidget" href="'.clean_url( $item['link'], $protocolls=null, 'display' ).'">'. htmlentities($item['title']) .'</a> ';
				if ($showdate)
					print '<span class="rss-date">'. date('F j, Y', strtotime($item['pubdate'])) .'</span>';
				print '<div class="rssSummary">'. freshclicks_text_limit($item['summary'],$excerptsize) .'</div>';
				print '</li>';
			}
			print '</ul>';
			print '<div style="border-top: 1px solid #ddd; padding-top: 10px; text-align:center;">';
			print '<a href="http://feeds.feedburner.com/FreshClicks"><img src="'.get_bloginfo('wpurl').'/wp-includes/images/rss.png" alt=""/> Subscribe with RSS</a>';
			if ($image == 'normal') {
				print ' &nbsp; &nbsp; &nbsp; ';
			} else {
				print '<br/>';
			}
			print '<a href="http://freshclcicks.net/">Subscribe by email</a>';
			print '</div>';
			print '</div>';
		}
	}
 	//FreshClicks marketing tool
	function freshclicks_widget_setup() {
	    wp_add_dashboard_widget( 'freshclcicks_db_widget' , 'The Latest news from FreshClicks' , 'freshclcicks_db_widget');
	}
 
	add_action('wp_dashboard_setup', 'freshclicks_widget_setup');
}


?>