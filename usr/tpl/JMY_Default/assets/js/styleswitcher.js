var widthLink = $("link[href|='../assets/css/width']");
var colorLink = $("link[href|='../assets/css/style']");
var switchCheck = $('input[name="full-width-checkbox"]');


if($.cookie("reason-color")) {
    colorLink.attr("href","../assets/css/" + $.cookie("reason-color"));
}

if($.cookie("reason-width")) {
    widthLink.attr("href","../assets/css/" + $.cookie("reason-width"));

    if ($.cookie("reason-width") == "width-boxed.css" && switchCheck.bootstrapSwitch('state')) {
        switchCheck.bootstrapSwitch('state', false);
    }
}

$(document).ready(function() {
    $("#color-options .color-box").click(function() {
        colorLink.attr("href", "../assets/css/" + $(this).attr('rel'));
        colorLink.attr("href", "../assets/css/" + $(this).attr('rel'));
        $.cookie("reason-color",$(this).attr('rel'), {expires: 7, path: '../../../../default.htm'});
        return false;
    });

    $("#width-options .container-option").click(function() {
        widthLink.attr("href", "../assets/css/" + $(this).attr('rel'));
        $.cookie("reason-width",$(this).attr('rel'), {expires: 7, path: '../../../../default.htm'});
        return false;
    });

    switchCheck.on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            widthLink.attr("href", "../assets/css/width-full.css");
            $.cookie("reason-width", "width-full.css", {expires: 7, path: '../../../../default.htm'}); }
        else
        {
            widthLink.attr("href", "../assets/css/width-boxed.css");
            $.cookie("reason-width", "width-boxed.css", {expires: 7, path: '../../../../default.htm'});
        }
    });
});
