jQuery(document).ready(function ($) {
    $('.did-widget-loader').css('display','block');

    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'directid_widget_ajax',
            didToken: getParameterByName('didToken'),
            didEmail: getParameterByName('didEmail'),
            didCustomerReference: getParameterByName('didCustomerReference') || getParameterByName('didRpUserId')
        },
        success: function (response) {
            $('#did-widget-container').html(response);
        },
        error: function (response) {
            console.error(response);
        }
    });
});

function getParameterByName(name) {
    var url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}