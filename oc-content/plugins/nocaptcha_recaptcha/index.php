<?php
/*
Plugin Name: noCaptcha reCaptcha
Plugin URI: http://ghoray.com/
Description: Show noCaptcha reCaptcha in Login, Register, Item post, Contact, Contact listing, Send to a friend. Also can implement any other form easily.
Version: 1.1.2
Author: Shamim
Author URI: http://ghoray.com/
Short Name: nocaptcha-recaptcha
Plugin update URI: nocaptcha-recaptcha
*/


if ( !function_exists('anr_get_option') ) :
	
function anr_get_option( $option, $default = '', $section = 'plugin-anr_nocaptcha' ) {
	
    $options = osc_get_preference( $option, $section);
	

    if ( isset( $options ) ) {
        return trim($options);
    }

    return $default;
}
	
endif;

function anr_load_scripts() {
       
	   $language	= anr_get_option('language');
		
		$lang	= "";
		if ( $language )
			$lang = "?hl=$language";
			
		osc_register_script( 'anr-google-recaptcha-script', "https://www.google.com/recaptcha/api.js$lang");
		osc_enqueue_script('anr-google-recaptcha-script');
   }
   
osc_add_hook('init', 'anr_load_scripts');

function anr_captcha_form_field( $echo = true )
	{
		$loggedin_hide 	= anr_get_option('loggedin_hide');
		
			if ( (osc_is_web_user_logged_in() || osc_is_admin_user_logged_in()) && $loggedin_hide )
				return;
				
		$site_key 	= anr_get_option('site_key');
		$theme		= anr_get_option('theme');
		$no_js		= anr_get_option('no_js');
		
		//osc_enqueue_script('anr-google-recaptcha-script');
		
		$field 		= "<div class='g-recaptcha' data-sitekey='$site_key' data-theme='$theme'></div>";
		
		if ( $no_js == 1 )
			{
				$field .="<noscript>
  							<div style='width: 302px; height: 352px;'>
    							<div style='width: 302px; height: 352px; position: relative;'>
      							<div style='width: 302px; height: 352px; position: absolute;'>
        							<iframe src='https://www.google.com/recaptcha/api/fallback?k=$site_key'
                							frameborder='0' scrolling='no'
                							style='width: 302px; height:352px; border-style: none;'>
        							</iframe>
      							</div>
								  <div style='width: 250px; height: 80px; position: absolute; border-style: none;
											  bottom: 21px; left: 25px; margin: 0px; padding: 0px; right: 25px;'>
									<textarea id='g-recaptcha-response' name='g-recaptcha-response'
											  class='g-recaptcha-response'
											  style='width: 250px; height: 80px; border: 1px solid #c1c1c1;
													 margin: 0px; padding: 0px; resize: none;' value=''>
									</textarea>
								  </div>
								</div>
							  </div>
							</noscript>";
				}
		
		if ( $echo )
			echo $field;
			
		return $field;
		
	}

function anr_verify_captcha()
	{
		$secre_key 	= anr_get_option('secret_key'); 
		$response = Params::getParam('g-recaptcha-response');
		$remoteip = $_SERVER["REMOTE_ADDR"];
		$loggedin_hide 	= anr_get_option('loggedin_hide');
		
		if ( (osc_is_web_user_logged_in() || osc_is_admin_user_logged_in()) && $loggedin_hide )
				return true;
		
		if ( !$secre_key ) //if $secre_key is not set
			return true;
		
		if ( !$response || !$remoteip )
			return false;
			
		$request = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secre_key."&response=".$response."&remoteip=".$remoteip);
			$result = json_decode( $request, true );
        if ( true == $result['success'] )
			return true;
		
        return false; 
	}

osc_register_plugin(osc_plugin_path(__FILE__), 'anr_call_after_install');

function anr_call_after_install() {
	
	osc_set_preference('theme', 'light', 'plugin-anr_nocaptcha');
	osc_set_preference('error_message', 'ERROR: Please solve Captcha correctly.', 'plugin-anr_nocaptcha');
 

}

osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", 'anr_call_after_uninstall');

function anr_call_after_uninstall() {
	
	osc_delete_preference('site_key', 'plugin-anr_nocaptcha');
	osc_delete_preference('secret_key', 'plugin-anr_nocaptcha');
	osc_delete_preference('language', 'plugin-anr_nocaptcha');
	osc_delete_preference('theme', 'plugin-anr_nocaptcha');
	osc_delete_preference('error_message', 'plugin-anr_nocaptcha');
	osc_delete_preference('no_js', 'plugin-anr_nocaptcha');

}

osc_add_hook(osc_plugin_path(__FILE__)."_configure", 'anr_render_admin');

function anr_render_admin() {
        osc_admin_render_plugin(osc_plugin_folder(__FILE__) . 'admin/admin.php');
    }
	
// Admin Menu
function anr_admin_menu() {
	echo '<h3><a href="#">' . __('noCaptcha reCaptcha', 'anr') . '</a></h3>
	<ul>
	<li><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'admin/admin.php') . '">' . '&raquo; ' . __('Settings', 'anr') . '</a><li>
	</ul>';
}

function anr_admin_menu_new() {
	osc_add_admin_submenu_divider('plugins', 'noCaptcha reCaptcha', 'anr_divider', 'administrator');
    osc_add_admin_submenu_page('plugins', __('Settings', 'pam'), osc_route_admin_url('anr-admin-settings'), 'anr_settings', 'administrator');
}

if(osc_version()>=320) {
osc_add_route('anr-admin-settings', 'anr/settings', 'anr/settings', osc_plugin_folder(__FILE__).'admin/admin.php');
osc_add_hook('admin_menu_init', 'anr_admin_menu_new');
} else {
osc_add_hook('admin_menu', 'anr_admin_menu');
}
	
    function anr_admin_actions() {

        if( Params::getParam('file') != 'nocaptcha_recaptcha/admin/admin.php' && Params::getParam('route') != 'anr-admin-settings' ) {
            return;
        }
		$submit         = Params::getParam('anr-admin-settings-submit');

        if( $submit ) {
		$flash_error = '';
            $site_key = Params::getParam('site_key');
			$secret_key = Params::getParam('secret_key');
			$language = Params::getParam('language');
			$theme = Params::getParam('theme');
			$error_message = Params::getParam('error_message');
			$loggedin_hide = Params::getParam('loggedin_hide');
			$no_js = Params::getParam('no_js');
			
			$login = Params::getParam('login');
			$registration = Params::getParam('registration');
			$new = Params::getParam('new');
			$contact = Params::getParam('contact');
			$contact_listing = Params::getParam('contact_listing');
			$send_friend = Params::getParam('send_friend');
			
			if ( !$site_key) {
                $flash_error .= _m("Site Key empty.") . PHP_EOL;
            } else {
				osc_set_preference('site_key', $site_key, 'plugin-anr_nocaptcha');
			}
			if ( !$secret_key) {
                $flash_error .= _m("Secret Key empty.") . PHP_EOL;
            } else {
				osc_set_preference('secret_key', $secret_key, 'plugin-anr_nocaptcha');
			}
			if ( $theme == 'dark') {
                osc_set_preference('theme', 'dark', 'plugin-anr_nocaptcha');
            } else {
				osc_set_preference('theme', 'light', 'plugin-anr_nocaptcha');
			}
			if ( !$error_message) {
                $flash_error .= _m("Error message empty.") . PHP_EOL;
            } else {
				osc_set_preference('error_message', $error_message, 'plugin-anr_nocaptcha');
			}
			if ( $loggedin_hide == '1') {
                osc_set_preference('loggedin_hide', '1', 'plugin-anr_nocaptcha');
            } else {
				osc_set_preference('loggedin_hide', '0', 'plugin-anr_nocaptcha');
			}
			if ( $no_js == '1') {
                osc_set_preference('no_js', '1', 'plugin-anr_nocaptcha');
            } else {
				osc_set_preference('no_js', '0', 'plugin-anr_nocaptcha');
			}
			if ( $login == '1') {
                osc_set_preference('login', '1', 'plugin-anr_nocaptcha');
            } else {
				osc_set_preference('login', '0', 'plugin-anr_nocaptcha');
			}
			if ( $registration == '1') {
                osc_set_preference('registration', '1', 'plugin-anr_nocaptcha');
            } else {
				osc_set_preference('registration', '0', 'plugin-anr_nocaptcha');
			}
			if ( $new == '1') {
                osc_set_preference('new', '1', 'plugin-anr_nocaptcha');
            } else {
				osc_set_preference('new', '0', 'plugin-anr_nocaptcha');
			}
			if ( $contact == '1') {
                osc_set_preference('contact', '1', 'plugin-anr_nocaptcha');
            } else {
				osc_set_preference('contact', '0', 'plugin-anr_nocaptcha');
			}
			if ( $contact_listing == '1') {
                osc_set_preference('contact_listing', '1', 'plugin-anr_nocaptcha');
            } else {
				osc_set_preference('contact_listing', '0', 'plugin-anr_nocaptcha');
			}
			if ( $send_friend == '1') {
                osc_set_preference('send_friend', '1', 'plugin-anr_nocaptcha');
            } else {
				osc_set_preference('send_friend', '0', 'plugin-anr_nocaptcha');
			}
			osc_set_preference('language', $language, 'plugin-anr_nocaptcha');
			
			if ( $flash_error ) {
				osc_add_flash_error_message( $flash_error, 'admin');
			} else {
            	osc_add_flash_ok_message(__('Options has been updated', 'anr'), 'admin');
			}
			osc_reset_preferences();
			
			if(osc_version()<320) {
            osc_redirect_to(osc_admin_render_plugin_url('nocaptcha_recaptcha/admin/admin.php'));
			} else {
			osc_redirect_to(osc_route_admin_url('anr-admin-settings'));
			}
        }
    }
osc_add_hook('init_admin', 'anr_admin_actions');


function anr_user_login_check()
{
	if ( '1' != anr_get_option('login'))
	return;
	
	if ( !anr_verify_captcha() ) {
		$error_message 	= trim(osc_get_preference( 'error_message', 'plugin-anr_nocaptcha')); 
		osc_add_flash_error_message( $error_message ) ;
		osc_redirect_to( osc_user_login_url() );
		}
}
osc_add_hook('before_login', 'anr_user_login_check');

function anr_user_register_check()
{
	if ( '1' != anr_get_option('registration'))
	return;
	
	if ( !anr_verify_captcha() ) {
		$error_message 	= trim(osc_get_preference( 'error_message', 'plugin-anr_nocaptcha')); 
		osc_add_flash_error_message( $error_message ) ;
		osc_redirect_to( osc_register_account_url() );
		}
}
osc_add_hook('before_user_register', 'anr_user_register_check');


function anr_item_add_check()
{
	if ( '1' != anr_get_option('new'))
	return;
	
	if ( !anr_verify_captcha() ) {
		$error_message 	= trim(osc_get_preference( 'error_message', 'plugin-anr_nocaptcha')); 
		osc_add_flash_error_message( $error_message ) ;
		osc_redirect_to( osc_item_post_url() );
		}
}
osc_add_hook('pre_item_add',  'anr_item_add_check');


function anr_contact_check()
{
    if(Params::getParam('page')=='contact' && Params::getParam('action')=='contact_post'){
		if ( '1' != anr_get_option('contact'))
		return;
	
		if ( !anr_verify_captcha() ) {
		$error_message 	= trim(osc_get_preference( 'error_message', 'plugin-anr_nocaptcha')); 
		osc_add_flash_error_message( $error_message ) ;
		osc_redirect_to( osc_contact_url() );
		}
    }
}
osc_add_hook('init', 'anr_contact_check');


function anr_item_contact_check($item)
{
	if ( '1' != anr_get_option('contact_listing'))
	return;
	
	if ( !anr_verify_captcha() ) {
		$error_message 	= trim(osc_get_preference( 'error_message', 'plugin-anr_nocaptcha')); 
		osc_add_flash_error_message( $error_message ) ;
		View::newInstance()->_exportVariableToView('item', $item);
		osc_redirect_to( osc_item_url() );
		}
}
osc_add_hook('pre_item_contact_post', 'anr_item_contact_check');


function anr_item_send_friend_check($item)
{
	if ( '1' != anr_get_option('send_friend'))
	return;
	
	if ( !anr_verify_captcha() ) {
		$error_message 	= trim(osc_get_preference( 'error_message', 'plugin-anr_nocaptcha')); 
		osc_add_flash_error_message( $error_message ) ;
		View::newInstance()->_exportVariableToView('item', $item);
		osc_redirect_to( osc_item_send_friend_url() );
		}
}
osc_add_hook('pre_item_send_friend_post', 'anr_item_send_friend_check');

?>