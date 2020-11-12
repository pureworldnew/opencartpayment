function accept_current_setting() {

    var checkbox_statistics = jQuery('div.gmt_gdpr').find('input[name="statistics"]');
    var checkbox_marketing = jQuery('div.gmt_gdpr').find('input[name="marketing"]');

    if(checkbox_statistics.is(':checked')) {
        document.cookie = "gmt_cookie_statistics=accepted";
        insert_event('statistics');
    }else {
        document.cookie = "gmt_cookie_statistics=no-accepted";
    }

    if(checkbox_marketing.is(':checked')) {
        document.cookie = "gmt_cookie_marketing=accepted";
        insert_event('marketing');
    }else {
        document.cookie = "gmt_cookie_marketing=no-accepted";
    }

    close_gdpr();
}

function close_gdpr() {
    jQuery('div.gmt_gdpr').fadeOut('slow');
}
function open_gdpr() {
    jQuery('div.gmt_gdpr').fadeIn('slow');
}

function insert_event(cookie_type) {
    var event_name = cookie_type == 'statistics' ? 'GDPRStatisticsAccepted' : 'GDPRMarketingAccepted';

    if(cookie_type == 'statistics')
        dataLayer.push({
            "gdpr_statistics_status": 'accepted'
        });
    else
        dataLayer.push({
            "gdpr_marketing_status": 'accepted'
        });

    dataLayer.push({
        "event": event_name
    });

}