<?php

namespace OWC\OpenPub\Base\Metabox;

use OWC\OpenPub\Base\Settings\SettingsPageOptions;
use WP_Query;

class AdminNotice
{
    public function upgradeAdminNotice(): void
    {
        if ($this->upgradeAdminNoticesDisabled() || ! $this->shouldDisplayAdminNotice()) {
            return;
        }
        
        $executedCommands = \get_option('_owc_openpub_base_executed_commands_upgrade_v3', []);

        if (empty($executedCommands)) {
            $message = __("Since the major release of v3.0.0 of the <strong>Yard | OpenPub Base</strong> plugin two manual actions are required. You're <strong>urgently</strong> requested to read the README.md inside the plugin folder.", 'openpub-base');
            echo $this->createAdminNoticeHTML($message);

            return;
        }

        if (! in_array('convert:highlighted', $executedCommands)) {
            $message = __("Since the major release of v3.0.0 of the <strong>Yard | OpenPub Base</strong> plugin there is still one manual action required. You're <strong>urgently</strong> requested to read the README.md inside the plugin folder and look for the part about 'convert:highlighted'.", 'openpub-base');
            echo $this->createAdminNoticeHTML($message);

            return;
        }

        if (! in_array('convert:expiration-date', $executedCommands)) {
            $message = __("Since the major release of v3.0.0 of the <strong>Yard | OpenPub Base</strong> plugin there is still one manual action required. You're <strong>urgently</strong> requested to read the README.md inside the plugin folder and look for the part about 'convert:expiration-date'.", 'openpub-base');
            echo $this->createAdminNoticeHTML($message);

            return;
        }
    }

    protected function createAdminNoticeHTML(string $message): string
    {
        return sprintf('<div class="notice notice-error is-dismissible"><p><span class="dashicons dashicons-warning" style="color: #f56e28"></span> %s</p></div>', $message);
    }

    protected function upgradeAdminNoticesDisabled(): bool
    {
        $settings = SettingsPageOptions::make();

        return $settings->upgradeAdminNoticesDisabled();
    }

    /**
     * Admin notice should only be displayed when there are openpub-items.
     * Hides the notices on new environments.
     */
    protected function shouldDisplayAdminNotice(): bool
    {
        $args = [
            'post_type' => 'openpub-item',
            'post_status' => 'any',
            'posts_per_page' => -1
        ];

        $results = new WP_Query($args);
        $count = count($results->posts);

        if (! $count) {
            $this->removeAdminNoticesOnFreshInstall();
        }

        return $count;
    }

    /**
     * When there are no openpub-items present we can assume this is a fresh install.
     * Update the option so the admin notices are only displayed when applicable.
     */
    protected function removeAdminNoticesOnFreshInstall(): void
    {
        $optionName = '_owc_openpub_base_executed_commands_upgrade_v3';
        $commands = ['convert:highlighted', 'convert:expiration-date'];

        \update_option($optionName, $commands);
    }
}
