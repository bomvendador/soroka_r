<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2014 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */
?>
<script type="text/javascript">
$(document).ready(function(){
	
	$('input[name=alert_email]').val('<?php _e('Enter your email address.', OSCLASSWIZARDS_THEME_FOLDER); ?>');
	
    $(".sub_button").click(function(){
        $.post('<?php echo osc_base_url(true); ?>', {email:$("#alert_email").val(), userid:$("#alert_userId").val(), alert:$("#alert").val(), page:"ajax", action:"alerts"},
            function(data){
                if(data==1) { alert('<?php echo osc_esc_js(__('You have sucessfully subscribed to the alert', OSCLASSWIZARDS_THEME_FOLDER)); ?>'); }
                else if(data==-1) { alert('<?php echo osc_esc_js(__('Invalid email address', OSCLASSWIZARDS_THEME_FOLDER)); ?>'); }
                else { alert('<?php echo osc_esc_js(__('Invalid email address', OSCLASSWIZARDS_THEME_FOLDER)); ?>');
                };
        });
        return false;
    });
	
    var sQuery = '<?php _e('Enter your email address.', OSCLASSWIZARDS_THEME_FOLDER); ?>';

    if($('input[name=alert_email]').val() == sQuery) {
        $('input[name=alert_email]').css('color', 'gray');
    }
    $('input[name=alert_email]').click(function(){
        if($('input[name=alert_email]').val() == sQuery) {
            $('input[name=alert_email]').val('');
            $('input[name=alert_email]').css('color', '');
        }
    });
    $('input[name=alert_email]').blur(function(){
        if($('input[name=alert_email]').val() == '') {
            $('input[name=alert_email]').val(sQuery);
            $('input[name=alert_email]').css('color', 'gray');
        }
    });
    $('input[name=alert_email]').keypress(function(){
        $('input[name=alert_email]').css('background','');
    })
});
</script>

<div class="alert_form">
    <h3>
        <strong><?php _e('Subscribe to this search', OSCLASSWIZARDS_THEME_FOLDER); ?></strong>
    </h3>
    <form action="<?php echo osc_base_url(true); ?>" method="post" name="sub_alert" id="sub_alert" class="nocsrf">
    
    
    <p><?php AlertForm::page_hidden(); ?>
            <?php AlertForm::alert_hidden(); ?>

            <?php if(osc_is_web_user_logged_in()) { ?>
                <?php AlertForm::user_id_hidden(); ?>
                <?php AlertForm::email_hidden(); ?>

            <?php } else { ?>
                <?php AlertForm::user_id_hidden(); ?>
                <?php AlertForm::email_text(); ?>

            <?php } ?></p>
            
            <button type="submit" class="btn btn-success sub_button">
			<?php _e('Subscribe now', OSCLASSWIZARDS_THEME_FOLDER); ?>!</button>
    </form>
</div>