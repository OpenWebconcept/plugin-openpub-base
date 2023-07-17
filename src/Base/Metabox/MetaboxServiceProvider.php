<?php

namespace OWC\OpenPub\Base\Metabox;

use CMB2;
use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\Metabox\Commands\ConvertExpirationDate;
use OWC\OpenPub\Base\Metabox\Commands\ConvertHighlighted;

class MetaboxServiceProvider extends ServiceProvider
{
    public const PREFIX = '_owc_';
    
    public function register()
    {
        $this->plugin->loader->addAction('cmb2_admin_init', $this, 'registerMetaboxes', 10, 0);
        $this->plugin->loader->addAction('admin_notices', new AdminNotice, 'upgradeAdminNotice', 10, 0);

        if (class_exists('\WP_CLI')) {
            \WP_CLI::add_command('convert:expiration-date', [ConvertExpirationDate::class, 'execute'], ['shortdesc' => 'Convert meta value expiration date to timestamp because of the implementation of CMB2.']);
            \WP_CLI::add_command('convert:highlighted', [ConvertHighlighted::class, 'execute'], ['shortdesc' => 'Convert meta value highlighted from numeric value to text because of the implementation of CMB2.']);
        }
    }

    public function registerMetaboxes(): void
    {
        $configMetaboxes = $this->plugin->config->get('cmb2_metaboxes');
        
        if (! is_array($configMetaboxes)) {
            return;
        }

        // Add metabox if plugin setting is checked.
        if ($this->plugin->settings->useEscapeElement()) {
            $configMetaboxes = $this->getEscapeElementMetabox($configMetaboxes);
        }

        // Add metabox if plugin setting is checked.
        if ($this->plugin->settings->useShowOn()) {
            $configMetaboxes = $this->getShowOnMetabox($configMetaboxes);
        }

        // Add default value when setting expireAfter is defined and has a bigger value than zero.
        if ($this->plugin->settings->expireAfter() > 0) {
            $configMetaboxes = $this->addExpirationDefaultValue($configMetaboxes);
        }

        $configMetaboxes = apply_filters("owc/openpub/base/before-register-metaboxes", $configMetaboxes);

        foreach ($configMetaboxes as $configMetabox) {
            if (! is_array($configMetabox)) {
                continue;
            }

            $this->registerMetabox(apply_filters(
                "owc/openpub/base/before-register-metabox",
                $configMetabox
            ));
        }
    }

    protected function registerMetabox(array $configMetabox): void
    {
        $fields = $configMetabox['fields'] ?? [];
        unset($configMetabox['fields']); // Fields will be added later on.

        $metabox = \new_cmb2_box($configMetabox);

        if (empty($fields) || ! is_array($fields)) {
            return;
        }

        $this->registerMetaboxFields($metabox, $fields);
    }

    protected function registerMetaboxFields(CMB2 $metabox, array $fields): void
    {
        foreach ($fields as $field) {
            $fieldKeys = array_keys($field);
            
            foreach ($fieldKeys as $fieldKey) {
                if (! is_array($field[$fieldKey])) {
                    continue;
                }

                if (isset($field[$fieldKey]['id'])) {
                    $field[$fieldKey]['id'] = self::PREFIX . $field[$fieldKey]['id'];
                }

                $metabox->add_field($field[$fieldKey]);
            }
        }
    }

    protected function addExpirationDefaultValue(array $configMetaboxes): array
    {
        $configMetaboxes['base']['fields']['general']['expiration']['std'] = (new \DateTime('now', new \DateTimeZone('Europe/Amsterdam')))->modify('+' . $this->plugin->settings->expireAfter() . ' days')->format('Y-m-d H:i');
        $configMetaboxes['base']['fields']['general']['expiration']['desc'] = __('Items with an expiration date will be excluded from the search results and on news overview page from this date forward.', 'openpub-base');

        return $configMetaboxes;
    }

    protected function getEscapeElementMetabox(array $configMetaboxes): array
    {
        return array_merge($configMetaboxes, $this->plugin->config->get('escape_element_metabox'));
    }

    protected function getShowOnMetabox(array $configMetaboxes): array
    {
        return array_merge($configMetaboxes, $this->plugin->config->get('show_on_metabox'));
    }
}
