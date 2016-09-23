jQuery(document).ready(function($) {
    // If live status indicator is enabled, show the client ID setter.
    if ($('#Form_Plugins-dot-LiveStreamPage-dot-ShowLiveIndicator').is(':checked')) {
        $('#Form_Plugins-dot-LiveStreamPage-dot-ClientID').parent().show();
    } else {
        $('#Form_Plugins-dot-LiveStreamPage-dot-ClientID').parent().hide();
    }

    $('#Form_Plugins-dot-LiveStreamPage-dot-ShowLiveIndicator').click(function() {
        $('#Form_Plugins-dot-LiveStreamPage-dot-ClientID').parent().toggle();
    });
});
