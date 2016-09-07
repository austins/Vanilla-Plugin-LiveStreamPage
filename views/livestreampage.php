<?php defined('APPLICATION') or exit();

echo wrap($this->title(), 'h1', array('class' => 'H'));

if (!$this->ChannelName) {
    echo t('The Twitch channel name must be set to display this page.');
} else {
    echo '<div id="TwitchEmbedWrap">';

    echo '<div id="TwitchVideoWrap">';
    echo wrap('', 'iframe', array(
        'id' => 'TwitchVideoEmbed',
        'src' => 'https://player.twitch.tv/?channel=' . $this->ChannelName,
        'frameborder' => '0',
        'scrolling' => 'no',
        'allowfullscreen' => 'yes'
    ));
    echo '</div>';

    echo '<div id="TwitchChatWrap">';
    echo wrap('', 'iframe', array(
        'id' => 'TwitchChatEmbed',
        'src' => 'https://www.twitch.tv/' . $this->ChannelName . '/chat?popout=',
        'frameborder' => '0',
        'scrolling' => 'no'
    ));
    echo '</div>';

    echo '</div>'; // #TwitchEmbedWrap

    echo wrap(anchor(t('Watch the live video at www.twitch.tv.'),
        'https://www.twitch.tv/' . $this->ChannelName . '?tt_medium=live_embed&tt_content=text_link'), 'p',
        array('class' => 'Center'));
}
