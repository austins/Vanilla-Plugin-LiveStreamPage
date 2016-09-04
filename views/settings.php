<?php defined('APPLICATION') or exit(); ?>
<h1><?php echo $this->PluginInfo['Name']; ?></h1>

<div class="Info">
    <p><?php echo $this->PluginInfo['Description']; ?></p>
    <p>View live page: <?php echo anchor($this->LivePageLink, $this->LivePageLink); ?></p>
    <br/>
    <p>Twitch is a trademark of Twitch Interactive, Inc. This plugin is not in any way affiliated with or endorsed by
        Twitch Interactive, Inc.</p>
</div>

<h3><?php echo t('Settings'); ?></h3>

<?php echo $this->ConfigurationModule->toString(); ?>

<br/>

<h3><?php echo t('Feedback'); ?></h3>

<div class="Box Aside" style="text-align: center; padding: 10px;"><a
        href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=72R6B2BUCMH46" target="_blank"><img
            src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" alt="" style="vertical-align: middle;"/></a>
</div>

<div class="Info">
    <?php echo t('LiveStreamPage.Settings.DonateInfo',
        'If you like this plugin and would like to support the developer, click the donation button to the right. :)'); ?>
</div>
