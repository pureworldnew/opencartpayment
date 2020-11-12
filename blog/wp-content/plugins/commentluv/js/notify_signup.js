jQuery(document).ready(function(){
    clsubmit = jQuery('#clsubmit');
    jQuery(clsubmit).data('form',jQuery('#mainblock form:first').serialize());
    jQuery(clsubmit).data('background',clsubmit.css('background'));
    // set width in px to make button center align for all languages
    jQuery('#cl_notify').css('width',jQuery('#cl_notify').width());
    // hide options if commentluv is disabled
    if(jQuery('.clenable:checked').val() == 'no'){
        jQuery('.ifenable').hide();
    }
    // change save settings button if any options were changed
    jQuery('#mainblock input').change(check_change);
    // listener for click of button to subscribe
    jQuery('#cl_notify').click(function(){
        jQuery(this).val(notify_signup_settings.wait_message + '...');
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {'action': 'notify_signup'},
            success: function(data){
                if(data.success == true){
                    jQuery('#cl_notify').parents('div.submit').slideUp();
                    jQuery('#notify_message').empty().html(notify_signup_settings.notify_success1 + ' ' + data.email + ' ' + notify_signup_settings.notify_success2);
                } else {
                    jQuery('#notify_message').empty().html('<strong>' + notify_signup_settings.notify_fail + ' <a target="_blank" href="http://www.commentluv.com">www.commentluv.com</a>');
                }  
            }
        })
    });
    // listener for click of enable radio buttons
    jQuery('.clenable').click(function(){
        if(jQuery(this).val() == 'yes'){
            // show the rest of the functions
            jQuery('.ifenable').show();
        } else {
            jQuery('.ifenable').hide();
        }
    });
    // listener for change of badge drop down
    jQuery('#badge_type').change(function(){
        var choice = jQuery(this).val();
        var image = notify_signup_settings[choice];
        jQuery('#display_badge').attr('src',notify_signup_settings.image_url + image);
        jQuery('#display_badge').show();

    });
    // listener for focus of input in display settings panel
    jQuery('.display-settings input').focus(function(){
        jQuery(this).prev('input').attr('checked',true);
        check_change();  
    });
    // listener for click on technical settings
    jQuery('#opentech').click(function(){ jQuery('#techbody').toggle();})
});

function check_change(){
    var formdata = jQuery('#mainblock form:first').serialize();
    if(formdata != jQuery(clsubmit).data('form')){
        jQuery('#clsubmit').css({'background':'orange','font-weight':'bolder'});
    } else {
        jQuery('#clsubmit').css({'background':jQuery(clsubmit).data('background'),'font-weight':'inherit'});
    }
}