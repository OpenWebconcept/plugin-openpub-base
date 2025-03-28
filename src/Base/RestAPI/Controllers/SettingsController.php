<?php

/**
 * Controller which handles the (requested) settings.
 */

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Settings\SettingsPageOptions;
use WP_REST_Request;

/**
 * Controller which handles the settings.
 */
class SettingsController extends BaseController
{
    /**
     * Get the settings.
     */
    public function getSettings(WP_REST_Request $request): array
    {
        $settingsPageOptions = SettingsPageOptions::make();

        $settings = [
            'portal_url' => $settingsPageOptions->getPortalURL(),
            'item_slug' => $settingsPageOptions->getPortalItemSlug(),
        ];

        return $settings;
    }
}
