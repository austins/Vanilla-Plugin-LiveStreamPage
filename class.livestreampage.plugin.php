<?php
/**
 * LiveStreamPage Plugin
 *
 * @copyright 2016 Austin S.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 */

// Define the plugin:
$PluginInfo['LiveStreamPage'] = array(
    'Name' => 'Live Stream Page',
    'Description' => 'Your viewers can watch your Twitch live stream from within your Vanilla Forums with this plugin.',
    'Version' => '1.0.0',
    'RequiredApplications' => array('Vanilla' => '2.2'),
    'MobileFriendly' => true,
    'Author' => 'Austin S.',
    'AuthorUrl' => 'https://github.com/austins',
    'License' => 'GNU GPL2',
    'SettingsUrl' => '/settings/livestreampage',
    'SettingsPermission' => 'Garden.Settings.Manage'
);

class LiveStreamPagePlugin extends Gdn_Plugin {
    /** Route settings for the live page. */
    const ROUTE = 'live';
    const ROUTE_EXPRESSION_SUFFIX = '$';
    const ROUTE_TARGET = 'plugin/livestreampage';

    /**
     * Get channel name from config.
     *
     * @return bool|string The channel name passed through rawurlencode() or false if not set.
     */
    public static function getChannelName() {
        // Get channel name from config.
        $channelName = c('Plugins.LiveStreamPage.ChannelName');
        if ($channelName) {
            $channelName = rawurlencode($channelName);
        }

        return $channelName;
    }

    /**
     * Get link to the live page.
     *
     * @param bool $withDomain Whether or not to include the domain with the url.
     * @return string The link to the live page.
     */
    public static function getLivePageLink($withDomain = true) {
        $link = '/';

        // Determine menu link by route.
        $route = Gdn::router()->matchRoute(self::ROUTE . self::ROUTE_EXPRESSION_SUFFIX);
        $link .= ($route && $route['Destination'] === self::ROUTE_TARGET) ? self::ROUTE : self::ROUTE_TARGET;

        // If $withDomain === false, don't pass $link through url() since $link is used for the main menu link.
        return $withDomain ? url($link, true) : $link;
    }

    /**
     * Add main menu link to the live page.
     *
     * @param Gdn_Controller $sender
     */
    public function base_render_before($sender) {
        if ($sender->Menu) {
            $sender->Menu->addLink('LiveStreamPage', t('Live'), self::getLivePageLink(false), false,
                array('class' => 'LiveStreamPageMenuLink'));

            $channelName = self::getChannelName();
            if ($channelName && c('Plugins.LiveStreamPage.ShowLiveIndicator', true)) {
                // Add live status indicator.
                $sender->addCssFile('livestreampage.css', 'plugins/LiveStreamPage');
                $sender->addJsFile('livestreampage.js', 'plugins/LiveStreamPage');
                $sender->addDefinition('LiveStreamPage_ChannelName', $channelName);
            }
        }
    }

    /**
     * Create the live page.
     *
     * @param PluginController $sender
     */
    public function pluginController_liveStreamPage_create($sender) {
        // Get channel name.
        $sender->setData('ChannelName', self::getChannelName(), true);

        // Set canonical URL.
        $sender->canonicalUrl(self::getLivePageLink());

        // Setup head.
        $sender->title(t('Live'));
        $sender->MasterView = '';
        $sender->removeCssFile('admin.css');
        $sender->addCssFile('style.css');
        $sender->addCssFile('livestreampage.css', 'plugins/LiveStreamPage');
        $sender->CssClass = 'NoPanel';
        $sender->render('livestreampage', '', 'plugins/LiveStreamPage');
    }

    /**
     * Create settings page for this plugin.
     *
     * @param SettingsController $sender
     */
    public function settingsController_liveStreamPage_create($sender) {
        // Set required permission.
        $sender->permission('Garden.Settings.Manage');

        $sender->setData('PluginInfo', Gdn::pluginManager()->getPluginInfo('LiveStreamPage'), true);
        $sender->setData('LivePageLink', self::getLivePageLink(), true);

        // Set up the configuration module.
        $configModule = new ConfigurationModule($sender);
        $configModule->initialize(array(
            'Plugins.LiveStreamPage.ChannelName' => array(
                'LabelCode' => 'Twitch Channel Name',
                'Description' => 'Enter the Twitch channel name you would like to display on the live page.',
                'Control' => 'TextBox'
            ),
            'Plugins.LiveStreamPage.ShowLiveIndicator' => array(
                'LabelCode' => 'Show status indicator next to main menu link when channel is live?',
                'Control' => 'Checkbox'
            )
        ));

        $sender->ConfigurationModule = $configModule;

        $sender->title(t('Live Stream Page Settings'));
        $sender->addSideMenu();
        $sender->render('settings', '', 'plugins/LiveStreamPage');
    }

    /**
     * Setup code to be run when this plugin is enabled.
     */
    public function setup() {
        // Initialize default setting.
        saveToConfig('Plugins.LiveStreamPage.ShowLiveIndicator', true);

        // Add route for the live page.
        if (!Gdn::router()->matchRoute(self::ROUTE . self::ROUTE_EXPRESSION_SUFFIX)) {
            Gdn::router()->setRoute(self::ROUTE . self::ROUTE_EXPRESSION_SUFFIX, self::ROUTE_TARGET, 'Internal');
        }
    }

    /**
     * Delete route for the live page when this plugin is disabled.
     */
    public function onDisable() {
        $route = Gdn::router()->matchRoute(self::ROUTE . self::ROUTE_EXPRESSION_SUFFIX);

        // Make sure that no other matching route than the plugin's default route is deleted.
        if ($route && $route['Destination'] === self::ROUTE_TARGET) {
            Gdn::router()->deleteRoute(self::ROUTE . self::ROUTE_EXPRESSION_SUFFIX);
        }
    }
}
