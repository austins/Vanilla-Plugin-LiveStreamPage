jQuery(document).ready(function($) {
    /* Live status indicator */
    var channelName = gdn.definition('LiveStreamPage_ChannelName');
    var clientId = gdn.definition('LiveStreamPage_ClientID');

    if (channelName && clientId) {
        // Check if Twitch channel is live.
        $.ajax({
            type: 'GET',
            url: 'https://api.twitch.tv/kraken/streams/' + channelName,
            headers: {
                'Client-ID': clientId
            },
            success: function(channel) {
                if (channel["stream"] != null) {
                    // Twitch channel is live.
                    $('a.LiveStreamPageMenuLink').addClass('TwitchChannelIsLive'); // Show live indicator.
                }
            }
        });
    }
});
