function submit_me(){
    //valifdation rules
    var inputName = $("input#inputName").val();
    if (inputName == "") {
        jQuery( "#name-section" ).addClass( "has-error" );
        jQuery("input#inputName").focus();
        if (typeof show_event_tracking !== 'undefined') {
            _gaq.push(['_trackEvent', event_cat, 'Validation Error', your_name_txt]);
        }
        return false;
    }
    var inputEmail = $("input#inputEmail").val();
    if (inputEmail == "") {
        $( "#email-section" ).addClass( "has-error" );
        $("input#email").focus();
        if (typeof show_event_tracking !== 'undefined') {
            _gaq.push(['_trackEvent', event_cat, 'Validation Error', your_email_txt]);
        }
        return false;
    }
    var inputSpamCheck = $("input#inputSpamCheck").val();
    var inputSpam = $("input#inputSpam").val();
    if (inputSpam != inputSpamCheck) {
        $("input#inputSpam").focus();
        $( "#spam-section" ).addClass( "has-error" );
        if (typeof show_event_tracking !== 'undefined') {
            _gaq.push(['_trackEvent', event_cat, 'Validation Error', 'Spam Check']);
        }
        return false;
    }
    //end valifdation rules
    //var inputWebsite = $("input#inputWebsite").val();
    //var inputArea = $("textarea#inputArea").val();

    //submit form - should change this out to JSON later
    //var dataString = 'inputName='+ inputName + '&inputEmail=' + inputEmail + '&inputWebsite=' + inputWebsite + '&inputArea=' + inputArea + '&subject=' +subject + '&recipient=' + recipient + '&your_name_txt=' + your_name_txt + '&your_email_txt=' + your_email_txt + '&your_website_txt=' + your_website_txt + '&your_message_txt=' + your_message_txt;

    jQuery.post(the_ajax_script.ajaxurl, jQuery("#theForm").serialize()
        ,
        function(response_from_the_action_function){
            jQuery("#contact-form").html(response_from_the_action_function);
        }
    );
}