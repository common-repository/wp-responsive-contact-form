<?php
/*
Author: FreshClicks
Author URI: http://FreshClicks.net
Description: Administrative options for FreshClicks Contact Form
*/

// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
 
// Guess the location
$wprcfpluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';

load_plugin_textdomain('wprcf',$wprcfpluginpath);

$location = get_option('siteurl') . '/wp-admin/admin.php?page='.dirname(__FILE__).'/options-contactform.php'; // Form Action URI

/*Lets add some default options if they don't exist*/
add_option('wprcf_email', __('you@example.com', 'wprcf'));
add_option('wprcf_subject', __('Contact Form Results', 'wprcf'));
add_option('wprcf_success_msg', __('Thanks for your comments!', 'wprcf'));
add_option('wprcf_yourname_txt', __('Your Name', 'wprcf'));
add_option('wprcf_email_txt', __('Your Email Address', 'wprcf'));
add_option('wprcf_website_txt', __('Your Subject', 'wprcf'));
add_option('wprcf_message_txt', __('Your Message', 'wprcf'));
add_option('wprcf_required_txt', __('*', 'wprcf'));
add_option('wprcf_spamcheck_txt', __('Are you human?', 'wprcf'));
add_option('wprcf_spamcheck1_txt', __('The sum of', 'wprcf'));
add_option('wprcf_spamcheck2_txt', __('and', 'wprcf'));
add_option('wprcf_spamcheck3_txt', __('is:', 'wprcf'));
add_option('wprcf_sendbtn_txt', __('Submit', 'wprcf'));
// add Google event tracking options
add_option('wprcf_show_event_tracking', FALSE);
add_option('wprcf_event_cat', __('FreshClicks Form', 'wprcf'));
add_option('wprcf_event_value', __('', 'wprcf'));


add_option('wprcf_show_spamcheck', TRUE);
//add_option('wprcf_jquery_option', TRUE);
add_option('wprcf_bootstrap_option', TRUE);

// preset styles
add_option('wprcf_form_class', __('form-horizontal', 'wprcf'));
// input row class
add_option('wprcf_input_row_class', __('form-group', 'wprcf'));
// col-lg-2 control-label
add_option('wprcf_label_class', __('col-md-4 control-label', 'wprcf'));
// input col div class
add_option('wprcf_input_col_class', __('col-md-8', 'wprcf'));
// input class
add_option('wprcf_input_class', __('form-control', 'wprcf'));
// button location
add_option('wprcf_button_place_class', __('col-md-offset-4', 'wprcf'));
// button
add_option('wprcf_button_class', __('btn btn-default', 'wprcf'));
// text area wprcf_textarea_row
add_option('wprcf_textarea_row', __('3', 'wprcf'));

// end preset styles

/*check form submission and update options*/
if ('process' == $_POST['stage']) {
	if (!current_user_can('manage_options')) die(__('You cannot edit the FreshClicks Contact Form options.'));
	check_admin_referer('ewprcf-config');
	
	update_option('wprcf_email', $_POST['wprcf_email']);
	update_option('wprcf_subject', $_POST['wprcf_subject']);
	update_option('wprcf_success_msg', $_POST['wprcf_success_msg']);
	update_option('wprcf_intro_msg', $_POST['wprcf_intro_msg']);
	update_option('wprcf_ending_msg', $_POST['wprcf_ending_msg']);
	update_option('wprcf_yourname_txt', $_POST['wprcf_yourname_txt']); 
	update_option('wprcf_email_txt', $_POST['wprcf_email_txt']); 
	update_option('wprcf_phone_txt', $_POST['wprcf_phone_txt']);
	update_option('wprcf_message_txt', $_POST['wprcf_message_txt']); 
	update_option('wprcf_required_txt', $_POST['wprcf_required_txt']); 
	update_option('wprcf_spamcheck_txt', $_POST['wprcf_spamcheck_txt']); 
	update_option('wprcf_spamcheck1_txt', $_POST['wprcf_spamcheck1_txt']); 
	update_option('wprcf_spamcheck2_txt', $_POST['wprcf_spamcheck2_txt']); 
	update_option('wprcf_spamcheck3_txt', $_POST['wprcf_spamcheck3_txt']); 
	update_option('wprcf_sendbtn_txt', $_POST['wprcf_sendbtn_txt']); 
	// add Google event tracking options
	update_option('wprcf_show_event_tracking', $_POST['wprcf_show_event_tracking']);
	update_option('wprcf_event_cat', $_POST['wprcf_event_cat']); 
	update_option('wprcf_event_value', $_POST['wprcf_event_value']); 
	// added class options
	update_option('wprcf_form_class', $_POST['wprcf_form_class']);
	update_option('wprcf_input_row_class', $_POST['wprcf_input_row_class']);
	update_option('wprcf_label_class', $_POST['wprcf_label_class']);
	update_option('wprcf_input_col_class', $_POST['wprcf_input_col_class']);
	update_option('wprcf_input_class', $_POST['wprcf_input_class']);
	update_option('wprcf_button_place_class', $_POST['wprcf_button_place_class']);
	update_option('wprcf_button_class', $_POST['wprcf_button_class']);
	// wprcf_textarea_row
	update_option('wprcf_textarea_row', $_POST['wprcf_textarea_row']);

	if(isset($_POST['wprcf_show_spamcheck'])) {
		// If wprcf_show_spamcheck is checked
		update_option('wprcf_show_spamcheck', true);
	} else {
		update_option('wprcf_show_spamcheck', false);
	}
	//if(isset($_POST['wprcf_jquery_option'])) {
		// If wprcf_jquery_option is checked
	//	update_option('wprcf_jquery_option', true);
	//} else {
	//	update_option('wprcf_jquery_option', false);
	//}
	if(isset($_POST['wprcf_bootstrap_option'])) {
		// If wprcf_bootstrap_option is checked
		update_option('wprcf_bootstrap_option', true);
	} else {
		update_option('wprcf_bootstrap_option', false);
	}
	if(isset($_POST['wprcf_show_event_tracking'])) {
		// If wprcf_show_event_tracking is checked
		update_option('wprcf_show_event_tracking', true);
	} else {
		update_option('wprcf_show_event_tracking', false);
	}
}

/*Get options for form fields*/
$wprcf_email = stripslashes(get_option('wprcf_email'));
$wprcf_subject = stripslashes(get_option('wprcf_subject'));
$wprcf_success_msg = stripslashes(get_option('wprcf_success_msg'));
$wprcf_show_spamcheck = get_option('wprcf_show_spamcheck');
//$wprcf_jquery_option = get_option('wprcf_jquery_option');
$wprcf_bootstrap_option = get_option('wprcf_bootstrap_option');
$wprcf_intro_msg = stripslashes(get_option('wprcf_intro_msg'));
$wprcf_ending_msg = stripslashes(get_option('wprcf_ending_msg'));
$wprcf_yourname_txt = stripslashes(get_option('wprcf_yourname_txt'));
$wprcf_email_txt = stripslashes(get_option('wprcf_email_txt'));
$wprcf_phone_txt = stripslashes(get_option('wprcf_phone_txt'));
$wprcf_message_txt = stripslashes(get_option('wprcf_message_txt'));
$wprcf_required_txt = stripslashes(get_option('wprcf_required_txt'));
$wprcf_spamcheck_txt = stripslashes(get_option('wprcf_spamcheck_txt'));
$wprcf_spamcheck1_txt = stripslashes(get_option('wprcf_spamcheck1_txt'));
$wprcf_spamcheck2_txt = stripslashes(get_option('wprcf_spamcheck2_txt'));
$wprcf_spamcheck3_txt = stripslashes(get_option('wprcf_spamcheck3_txt'));
$wprcf_sendbtn_txt = stripslashes(get_option('wprcf_sendbtn_txt'));
// add Google event tracking options
$wprcf_show_event_tracking = stripslashes(get_option('wprcf_show_event_tracking'));
$wprcf_event_cat = stripslashes(get_option('wprcf_event_cat'));
$wprcf_event_value = stripslashes(get_option('wprcf_event_value'));
// add class options
$wprcf_form_class = stripslashes(get_option('wprcf_form_class'));
$wprcf_input_row_class = stripslashes(get_option('wprcf_input_row_class'));
$wprcf_label_class = stripslashes(get_option('wprcf_label_class'));
$wprcf_input_col_class = stripslashes(get_option('wprcf_input_col_class'));
$wprcf_input_class = stripslashes(get_option('wprcf_input_class'));
$wprcf_button_place_class = stripslashes(get_option('wprcf_button_place_class'));
$wprcf_button_class = stripslashes(get_option('wprcf_button_class'));
$wprcf_textarea_row = stripslashes(get_option('wprcf_textarea_row'));

?>

<div class="wrap">
  <h2><?php _e('FreshClicks Contact Form Options', 'wprcf') ?></h2>
  <?php _e('Place this shortcode [freshclicks_form] anywhere you want your form to appear.', 'wprcf') ?>
  <form name="form1" method="post" action="<?php echo $location ?>&amp;updated=true">
	<?php
	if ( function_exists('wp_nonce_field') )
		wp_nonce_field('ewprcf-config');
	?>
	<input type="hidden" name="stage" value="process" />
    <hr /><h3><?php _e('Dependency settings', 'wprcf') ?></h3>

    <p><?php _e('If your is not using the Twitter Bootstrap framework then keep this option enabled.', 'wprcf') ?></p>
    <table width="100%" cellspacing="2" cellpadding="5" class="form-table">
    <!-- add the bootstrap option -->
    	<tr valign="top">
			<th width="30%" scope="row" style="text-align: left"><?php _e('Use Twitter Bootstrap from the CDN', 'wprcf') ?></th>
			<td>
				<input name="wprcf_bootstrap_option" type="checkbox" id="wprcf_bootstrap_option" value="wprcf_bootstrap_option"
				<?php if($wprcf_bootstrap_option == TRUE) {?> checked="checked" <?php } ?> />
			</td>
		</tr>
    <!-- add the JQuery option
    	<tr valign="top">
			<th width="30%" scope="row" style="text-align: left"><?php// _e('Use JQuery from the CDN', 'wprcf') ?></th>
			<td>
				<input name="wprcf_jquery_option" type="checkbox" id="wprcf_jquery_option" value="wprcf_jquery_option"
				<?php // if($wprcf_jquery_option == TRUE) {?> checked="checked" <?php// } ?> />
			</td>
		</tr> -->
        <!-- add the Google Analytics form tracking -->
    	<tr valign="top">
			<th width="30%" scope="row" style="text-align: left"><?php _e('Use Google Analytics Event tracking', 'wprcf') ?></th>
			<td>
				<input name="wprcf_show_event_tracking" type="checkbox" id="wprcf_show_event_tracking" value="wprcf_show_event_tracking"
				<?php if($wprcf_show_event_tracking == TRUE) {?> checked="checked" <?php } ?> /> <?php _e('(<strong>Note:</strong> If you select this option and DO NOT have Google analytics installed, it will prevent the form from working.)', 'wprcf') ?>
			</td>
		</tr>
        <!-- add tracking event category -->
        <tr valign="top">
        	<th scope="row"><?php _e('Event Category:') ?></th>
        	<td><input name="wprcf_event_cat" type="text" id="wprcf_event_cat" value="<?php echo $wprcf_event_cat; ?>" size="40" />
        	<br />
			<?php _e('For Google Analytics Event Tracking, a category is a name that you supply as a way to group objects that you want to track.<br />If you have selected Event tracking this is a required field, but I\'m not validating this field yet so make sure you don\'t leave this blank!', 'wprcf') ?></td>
      	</tr>
        <tr valign="top">
        	<th scope="row"><?php _e('Event Value:') ?></th>
        	<td><input name="wprcf_event_value" type="text" id="wprcf_event_value" value="<?php echo $wprcf_event_value; ?>" size="20" />
        	<br />
			<?php _e('This is an optional numeric field and represents the value you have assigned to a successfully completed form. If you don\'t have a value, leave it blank. I\'m not validating this fields yet so if you do add a value make sure it is numeric with no special characters.', 'wprcf') ?></td>
      	</tr>
    </table>
    <table width="100%" cellspacing="2" cellpadding="5" class="form-table">  
    <hr /><h3><?php _e('Email settings', 'wprcf') ?></h3>
      <tr valign="top">
        <th scope="row"><?php _e('E-mail Address:') ?></th>
        <td><input name="wprcf_email" type="text" id="wprcf_email" value="<?php echo $wprcf_email; ?>" size="40" />
        <br />
<?php _e('This address is where the email will be sent to.', 'wprcf') ?></td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('Subject:') ?></th>
        <td><input name="wprcf_subject" type="text" id="wprcf_subject" value="<?php echo $wprcf_subject; ?>" size="50" />
        <br />
<?php _e('This will be the subject of the email.', 'wprcf') ?></td>
      </tr>
      <tr valign="top">
			<th width="30%" scope="row" style="text-align: left"><?php _e('Show \'Spam Prevention\' Option', 'wprcf') ?></th>
			<td>
				<input name="wprcf_show_spamcheck" type="checkbox" id="wprcf_show_spamcheck" value="wprcf_show_spamcheck"
				<?php if($wprcf_show_spamcheck == TRUE) {?> checked="checked" <?php } ?> />
			</td>
		</tr>
     </table>
	<!-- #Email settings -->
	<hr /><h3><?php _e('Messages', 'wprcf') ?></h3>
		<table width="100%" cellspacing="2" cellpadding="5" class="form-table">
		  <tr valign="top">
			<th scope="row"><?php _e('Success Message:', 'wprcf') ?></th>
			<td><textarea name="wprcf_success_msg" id="wprcf_success_msg" style="width: 80%;" rows="4" cols="50"><?php echo $wprcf_success_msg; ?></textarea>
			<br />
	<?php _e('When the form is sucessfully submitted, this is the message the user will see.', 'wprcf') ?></td>
		  </tr>
		</table>
	<!-- #Messages -->
	<hr /><h3><?php _e('Custom Language Texts', 'wprcf') ?></h3>
    <table width="100%" cellspacing="2" cellpadding="5" class="form-table">   

	   
       
       <tr valign="top">
			<th scope="row"><?php _e('Above the form text: <small>incase you want to include additional info, like alternative contact options.', 'wprcf') ?></th>
			<td><textarea name="wprcf_intro_msg" id="wprcf_intro_msg" style="width: 80%;" rows="4" cols="50"><?php echo $wprcf_intro_msg; ?></textarea>
			<br />
	<?php _e('Place valid html mark up here and it will display above your form. If you leave it blank, it wont display.', 'wprcf') ?></td>
		  </tr>
         
       <tr valign="top">
			<th scope="row"><?php _e('Below form text: <small>Same as the above for text. Just trying to give you more options.</small>', 'wrpcf') ?></th>
			<td><textarea name="wprcf_ending_msg" id="wprcf_ending_msg" style="width: 80%;" rows="4" cols="50"><?php echo $wprcf_ending_msg; ?></textarea>
			<br />
	<?php _e('Place valid html mark up here and it will display below your form. If you leave it blank, it wont display.', 'wprcf') ?></td>
		  </tr>
       
       
       <tr valign="top">
        <th scope="row"><?php _e('"Your Name" Text:', 'wprcf') ?></th>
        <td><input name="wprcf_yourname_txt" type="text" id="wprcf_yourname_txt" value="<?php echo $wprcf_yourname_txt; ?>" size="50" />
        <br />
<?php _e('This will be the name label text in the contact form.', 'wprcf') ?></td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('"Email" Text:', 'wprcf') ?></th>
        <td><input name="wprcf_email_txt" type="text" id="wprcf_email_txt" value="<?php echo $wprcf_email_txt; ?>" size="50" />
        <br />
<?php _e('This will be the Email Address label text in the contact form.', 'wprcf') ?></td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('"Phone" Text:', 'wprcf') ?></th>
        <td><input name="wprcf_phone_txt" type="text" id="wprcf_phone_txt" value="<?php echo $wprcf_phone_txt; ?>" size="50" />
        <br />
<?php _e('This will be the Phone label in the contact form.', 'wprcf') ?></td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('"Message" Text:', 'wprcf') ?></th>
        <td><input name="wprcf_message_txt" type="text" id="wprcf_message_txt" value="<?php echo $wprcf_message_txt; ?>" size="50" />
        <br />
<?php _e('This will be the Message label text in the contact form.', 'wprcf') ?></td>
      </tr>      
      <tr valign="top">
        <th scope="row"><?php _e('"required" Text :', 'wprcf') ?></th>
        <td><input name="wprcf_required_txt" type="text" id="wprcf_required_txt" value="<?php echo $wprcf_required_txt; ?>" size="50" />
        <br />
<?php _e('text next to required name and email fields', 'wprcf') ?></td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('"Anti-Spam" Text :', 'wprcf') ?></th>
        <td>
        	<input name="wprcf_spamcheck_txt" type="text" id="wprcf_spamcheck_txt" value="<?php echo $wprcf_spamcheck_txt; ?>" size="12" />
        	<input name="wprcf_spamcheck1_txt" type="text" id="wprcf_spamcheck1_txt" value="<?php echo $wprcf_spamcheck1_txt; ?>" size="12" />
            <input name="wprcf_spamcheck2_txt" type="text" id="wprcf_spamcheck2_txt" value="<?php echo $wprcf_spamcheck2_txt; ?>" size="12" />
            <input name="wprcf_spamcheck3_txt" type="text" id="wprcf_spamcheck3_txt" value="<?php echo $wprcf_spamcheck3_txt; ?>" size="12" />
        <br />
<?php _e('This will be the anti-spam text in the contact form.', 'wprcf') ?></td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('"Send Button" Text:', 'wprcf') ?></th>
        <td><input name="wprcf_sendbtn_txt" type="text" id="wprcf_sendbtn_txt" value="<?php echo $wprcf_sendbtn_txt; ?>" size="50" />
        <br />
<?php _e('This will be the "Send Button" text in the contact form.', 'wprcf') ?></td>
      </tr>
	</table>
    <hr /><h3><?php _e('Style Classes', 'wprcf') ?></h3>
    <?php _e('If your using Bootstrap you can leave the defualts or custimze them using the provide class modification options below', 'wprcf') ?>
	<table width="100%" cellpadding="5" class="form-table">
		<tr valign="top">
        	<th scope="row"><?php _e('The overal form class', 'wprcf') ?></th>
        	<td><input name="wprcf_form_class" type="text" id="wprcf_form_class" value="<?php echo $wprcf_form_class; ?>" size="50" />
        	<br />
			<?php _e('Defines the overall style of your contact form', 'wprcf') ?></td>
		</tr>
		<tr valign="top">
        	<th scope="row"><?php _e('Input row', 'wprcf') ?></th>
        	<td><input name="wprcf_input_row_class" type="text" id="wprcf_input_row_class" value="<?php echo $wprcf_input_row_class; ?>" size="50" />
        	<br />
			<?php _e('The row that contains both the input field and the label', 'wprcf') ?></td>
		</tr>
        <tr valign="top">
        	<th scope="row"><?php _e('Label class', 'wprcf') ?></th>
        	<td><input name="wprcf_label_class" type="text" id="wprcf_label_class" value="<?php echo $wprcf_label_class; ?>" size="50" />
        	<br />
			<?php _e('The labels class', 'wprcf') ?></td>
		</tr>
        <tr valign="top">
        	<th scope="row"><?php _e('input div class', 'wprcf') ?></th>
        	<td><input name="wprcf_input_col_class" type="text" id="wprcf_input_col_class" value="<?php echo $wprcf_input_col_class; ?>" size="50" />
        	<br />
			<?php _e('The class that controls the div that contains the input field', 'wprcf') ?></td>
		</tr>
        <tr valign="top">
        	<th scope="row"><?php _e('input class', 'wprcf') ?></th>
        	<td><input name="wprcf_input_class" type="text" id="wprcf_input_class" value="<?php echo $wprcf_input_class; ?>" size="50" />
        	<br />
			<?php _e('The class that controls the input field', 'wprcf') ?></td>
		</tr>
        <tr valign="top">
        	<th scope="row"><?php _e('Textarea Rows', 'wprcf') ?></th>
        	<td><input name="wprcf_textarea_row" type="text" id="wprcf_textarea_row" value="<?php echo $wprcf_textarea_row; ?>" size="50" />
        	<br />
			<?php _e('Controls the number of rows in the forms textarea', 'wprcf') ?></td>
		</tr>
        <tr valign="top">
        	<th scope="row"><?php _e('Button placement class', 'wprcf') ?></th>
        	<td><input name="wprcf_button_place_class" type="text" id="wprcf_button_place_class" value="<?php echo $wprcf_button_place_class; ?>" size="50" />
        	<br />
			<?php _e('Controls the palcement of the button', 'wprcf') ?></td>
		</tr>
        <tr valign="top">
        	<th scope="row"><?php _e('Button style class', 'wprcf') ?></th>
        	<td><input name="wprcf_button_class" type="text" id="wprcf_button_class" value="<?php echo $wprcf_button_class; ?>" size="50" />
        	<br />
			<?php _e('Change the look of the button using Twitter Bootstraps button classes', 'wprcf') ?></td>
		</tr>
	</table>
    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Update Options', 'wprcf') ?> &raquo;" />
    </p>
  </form>
</div>