<?php 

if ( (!defined('ABS_PATH')) ) exit('ABS_PATH is not loaded. Direct access is not allowed.');
if ( !OC_ADMIN ) exit('User access is not allowed.'); 
?>
	  <style>
			input[type='text'] {
				width: 100%;
			}
		</style>
<?php
	  $languages = array(
							__( 'Auto Detect', 'anr' )         	=> '',
							__( 'Arabic', 'anr' )              	=> 'ar',
							__( 'Bulgarian', 'anr' )           	=> 'bg',
							__( 'Catalan', 'anr' )             	=> 'ca',
							__( 'Chinese (Simplified)', 'anr' )	=> 'zh-CN',
							__( 'Chinese (Traditional)', 'anr' ) => 'zh-TW',
							__( 'Croatian', 'anr' )           	=> 'hr',
							__( 'Czech', 'anr' )             	=> 'cs',
							__( 'Danish', 'anr' )             	=> 'da',
							__( 'Dutch', 'anr' )              	=> 'nl',
							__( 'English (UK)', 'anr' )         => 'en-GB',
							__( 'English (US)', 'anr' )         => 'en',
							__( 'Filipino', 'anr' )				=> 'fil',
							__( 'Finnish', 'anr' ) 				=> 'fi',
							__( 'French', 'anr' )           	=> 'fr',
							__( 'French (Canadian)', 'anr' )   	=> 'fr-CA',
							__( 'German', 'anr' )   			=> 'de',
							__( 'German (Austria)', 'anr' )		=> 'de-AT',
							__( 'German (Switzerland)', 'anr' ) => 'de-CH',
							__( 'Greek', 'anr' )           		=> 'el',
							__( 'Hebrew', 'anr' )             	=> 'iw',
							__( 'Hindi', 'anr' )             	=> 'hi',
							__( 'Hungarain', 'anr' )            => 'hu',
							__( 'Indonesian', 'anr' )         	=> 'id',
							__( 'Italian', 'anr' )         		=> 'it',
							__( 'Japanese', 'anr' )				=> 'ja',
							__( 'Korean', 'anr' ) 				=> 'ko',
							__( 'Latvian', 'anr' )           	=> 'lv',
							__( 'Lithuanian', 'anr' )   		=> 'lt',
							__( 'Norwegian', 'anr' )   			=> 'no',
							__( 'Persian', 'anr' )           	=> 'fa',
							__( 'Polish', 'anr' )   			=> 'pl',
							__( 'Portuguese', 'anr' )   		=> 'pt',
							__( 'Portuguese (Brazil)', 'anr' )  => 'pt-BR',
							__( 'Portuguese (Portugal)', 'anr' )=> 'pt-PT',
							__( 'Romanian', 'anr' )         	=> 'ro',
							__( 'Russian', 'anr' )         		=> 'ru',
							__( 'Serbian', 'anr' )				=> 'sr',
							__( 'Slovak', 'anr' ) 				=> 'sk',
							__( 'Slovenian', 'anr' )           	=> 'sl',
							__( 'Spanish', 'anr' )   			=> 'es',
							__( 'Spanish (Latin America)', 'anr' )=> 'es-419',
							__( 'Swedish', 'anr' )           	=> 'sv',
							__( 'Thai', 'anr' )   				=> 'th',
							__( 'Turkish', 'anr' )   			=> 'tr',
							__( 'Ukrainian', 'anr' )   			=> 'uk',
							__( 'Vietnamese', 'anr' )   		=> 'vi'
							
							);
							
		$locations = array(	 
							__( 'Login Form', 'anr' )   		=> 'login',
							__( 'Registration Form', 'anr' )   	=> 'registration',
							__( 'New listing Form', 'anr' )   	=> 'new',
							__( 'Web contact Form', 'anr' )  	=> 'contact',
							__( 'Contact listing Form', 'anr' ) => 'contact_listing',
							__( 'Send to a friend Form', 'anr' )=> 'send_friend'
									
							);
									
	  ?>

	  	 <h2 class="render-title"><?php _e("Advanced noCaptcha reCaptcha Settings", "anr"); ?></h2>
          <form method="post" action="<?php echo osc_admin_base_url(true); ?>">
		  <input type="hidden" name="page" value="plugins" />
          <input type="hidden" name="action" value="renderplugin" />
          <?php if(osc_version()<320) { ?>
          <input type="hidden" name="file" value="nocaptcha_recaptcha/admin/admin.php" />
          <?php } else { ?>
          <input type="hidden" name="route" value="anr-admin-settings" />
          <?php }; ?>
          <input type="hidden" name="plugin_action" value="save_options" />
          <table>
          <thead>
          	<tr>
				<th width = "50%"><?php _e("Setting", "anr"); ?></th>
				<th width = "50%"><?php _e("Value", "anr"); ?></th>
			</tr>
          </thead>
          	<tr>
				<td><?php _e("Site Key", "anr"); ?><br/><small><a href="https://www.google.com/recaptcha/admin" target="_blank"><?php _e("Get From Google", "anr"); ?></a></small></td>
				<td><input type="text" name="site_key" value="<?php echo osc_esc_html(anr_get_option("site_key")); ?>" /></td>
			</tr>
		  	<tr>
				<td><?php _e("Secret key", "anr"); ?><br/><small><a href="https://www.google.com/recaptcha/admin" target="_blank"><?php _e("Get From Google", "anr"); ?></a></small></td>
				<td><input type="text" name="secret_key" value="<?php echo osc_esc_html(anr_get_option("secret_key")); ?>" /></td>
			</tr>
		  	<tr>
				<td><?php _e("Language", "anr"); ?></td>
				<td><select name="language">
		  
		 			<?php foreach ( $languages as $language => $code ) { ?>
		  
		  				<option <?php if(anr_get_option("language") == $code){echo 'selected="selected"';}?> value="<?php echo $code;?>"><?php echo $language;?></option>
		  			<?php } ?>
		  
		  			</select></td>
			</tr>
		  	<tr>
				<td><?php _e("Theme", "anr"); ?></td>
				<td><select name="theme">
		  
		   				<option <?php if(anr_get_option("theme") == "light"){echo 'selected="selected"';}?> value="light"><?php _e("Light","anr");?></option>
		   				<option <?php if(anr_get_option("theme") == "dark"){echo 'selected="selected"';}?> value="dark"><?php _e("Dark","anr");?></option>
		   
		 			</select></td>
			</tr>
		  	<tr>
				<td><?php _e("Error Message", "anr"); ?></td>
				<td><input type="text" name="error_message" value="<?php echo osc_esc_html(anr_get_option("error_message", "ERROR: Please solve Captcha correctly.")); ?>" /></td>
			</tr>
		  
		  	<tr>
				<td><?php _e("Show Captcha on", "anr"); ?></td>
				<td>
		  
		 			<?php foreach ( $locations as $location => $slug ) { ?>
		  
		  				<ul colspan="2"><input type="checkbox" name="<?php echo $slug; ?>" value="1" <?php if(anr_get_option($slug) == "1"){echo 'checked="checked"';}?> /> <?php echo $location; ?></ul>
		  			<?php } ?>
		  
		 		</td>
			</tr>

		  	<tr>
		  		<td colspan="2"><input type="checkbox" name="loggedin_hide" value="1" <?php if(anr_get_option("loggedin_hide") == "1"){echo 'checked="checked"';}?> /> <?php _e("Hide Captcha for logged in users?","anr");?></td>
		 	</tr>
		 	<tr>
				<td colspan="2"><input type="checkbox" name="no_js" value="1" <?php if(anr_get_option("no_js") == "1"){echo 'checked="checked"';}?>/> <?php _e("Show captcha if javascript disabled?","anr");?><br/><small><?php _e("If JavaScript is a requirement for your site, we advise that you do NOT check this","anr");?></small></td></tr>
          	<tr>
		  		<td colspan="2"><span><input class="btn btn-submit" type="submit" name="anr-admin-settings-submit" value="<?php echo osc_esc_html("Save changes","anr");?>" /></span></td>
			</tr>
          </table>
		  </form>

<div style="padding: 20px;">
    <h2 class="render-title"><?php _e("How it works", "anr"); ?></h2>

    <p><?php _e("You can add noCaptcha reCaptcha at:", "anr"); ?></p>

    <ul style="line-height: 30px;">
        <li>- <?php _e("New listing page.", "anr"); ?></li>
		<li>- <?php _e("User login page.", "anr"); ?></li>
        <li>- <?php _e("User registration page.", "anr"); ?></li>
        <li>- <?php _e("Web contact page.", "anr"); ?></li>
        <li>- <?php _e("Contact listing page.", "anr"); ?></li>
        <li>- <?php _e("'Send to a friend' listing page.", "anr"); ?></li>
    </ul>

    <p><?php _e("First, you need to fill and save noCaptcha reCaptcha credentials in this page,
        If you don't have an account, you can create a new one here", "anr"); ?> <a target="blank" href="https://www.google.com/recaptcha/admin"><?php _e("https://www.google.com/recaptcha/admin/", "anr"); ?></a></p>

    <p><?php _e("Second, you will need to paste the following line of code before the submit form button.", "anr"); ?></p>
    <br>
    <code><?php echo osc_esc_html('<?php anr_captcha_form_field(); ?>'); ?></code>
    <br>

    <pre>
<?php echo osc_esc_html('<form>

    ...

    	<?php anr_captcha_form_field(); ?>
	
    	<button type="submit" value="Save"/>
</form>'); ?>
    </pre>
    <br>
    <br>
    <p style="font-size: 18px;"><b><?php _e('IMPORTANT NOTE:', 'anr'); ?></b><br><?php _e('Only tick mark in "show captcha on" where you added code in theme file. eg. you added code in login form than tick only "login form". other keep untick.you can add all of them.', 'anr'); ?><br />
	<?php _e('Use new site key and public key.', 'anr'); ?><br />
	<?php _e('Remove reCAPTCHA Public key and Private key from Settings > Spam and Bots.', 'anr'); ?></p>
    <br>
    <br>

    <h2 class="render-title"><?php _e('FAQ', 'anr'); ?></h2>

    <h3><?php _e('More noCaptcha reCaptcha information, change language, ...', 'anr'); ?></h3>
    <p><?php _e('See above form', 'anr'); ?></p>

    <br>
    <hr/>

    <br>
    <h2 class="render-title"><?php _e('Change recaptcha by noCaptcha reCaptcha, Bender theme example', 'anr'); ?></h2>

    <h3><?php _e("New listing (oc-content/bender/item-post.php) Bender theme", "anr"); ?></h3>

    <?php _e("Find and replace this code. if not exist, simply add the replace part inside", "anr"); ?> <?php echo osc_esc_html("<form>"); ?>

    <pre>
<?php echo osc_esc_html('
			<?php if( osc_recaptcha_items_enabled() ) { ?>
                              <div class="controls">
                                    <?php osc_show_recaptcha(); ?>
                                </div>
                            <?php }?>'); ?>
    </pre>

    <?php _e("replace by", "anr"); ?>

    <pre><?php echo osc_esc_html("<?php anr_captcha_form_field(); ?>"); ?></pre>

    <h3><?php _e("User registration (oc-content/bender/user-register.php) Bender theme", "anr"); ?></h3>

    <?php _e("Find and replace this code. it not exist, simply add the replace part inside", "anr"); ?> <?php echo osc_esc_html("<form>"); ?>

    <pre>
    <?php echo osc_esc_html("<?php osc_show_recaptcha('register'); ?>"); ?>
    </pre>

    <?php _e("replace by", "anr"); ?>

    <pre><?php echo osc_esc_html("<?php anr_captcha_form_field(); ?>"); ?></pre>

    <h3><?php _e('All theme files', 'anr'); ?></h3>
    <ul style="line-height: 30px;">
        <li><?php _e("New listing                     (oc-content/bender/item-post.php)        Bender theme", "anr"); ?></li>
		<li><?php _e("User login               		  (oc-content/bender/user-login.php)       Bender theme", "anr"); ?></li>
        <li><?php _e("User registration               (oc-content/bender/user-register.php)    Bender theme", "anr"); ?></li>
        <li><?php _e("Web contact page                (oc-content/bender/contact.php)          Bender theme", "anr"); ?></li>
        <li><?php _e("Contact listing page            (oc-content/bender/item-sidebar.php)     Bender theme", "anr"); ?></li>
        <li><?php _e("'Send to a friend' listing page (oc-content/bender/item-send-friend.php) Bender theme", "anr"); ?></li>
    </ul>
</div>