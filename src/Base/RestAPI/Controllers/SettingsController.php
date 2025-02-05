<?php

/**
 * Controller which handles the (requested) settings.
 */

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use WP_REST_Request;
use OWC\OpenPub\Base\Settings\SettingsPageOptions;

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
			'item_slug' => $settingsPageOptions->getPortalItemSlug(),
		];

		return $settings;
	}
}
