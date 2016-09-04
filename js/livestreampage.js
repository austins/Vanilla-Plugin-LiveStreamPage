jQuery(document).ready(function($) {
    /* Live indicator */
    var channelName = gdn.definition('LiveStreamPage_ChannelName');

    if (channelName) {
        // Check if Twitch channel is live.
        $.getJSON('https://api.twitch.tv/kraken/streams/' + channelName, function(channel) {
            if (channel["stream"] != null) {
                // Twitch channel is live.
                $('a.LiveStreamPageMenuLink').addClass('TwitchChannelIsLive'); // Show live indicator.
            }
        });
    }
});
