<?php

namespace OWC\OpenPub\Base\Metabox;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

abstract class MetaboxBaseServiceProvider extends ServiceProvider
{
    const PREFIX = '_owc_';

    protected function processMetabox(array $metabox)
    {
        $fields = [];
        foreach ($metabox['fields'] as $fieldGroup) {
            $fields = array_merge($fields, $this->processFieldGroup($fieldGroup));
        }
        $metabox['fields'] = $fields;

        return $metabox;
    }

    private function processFieldGroup($fieldGroup)
    {
        $fields = [];
        foreach ($fieldGroup as $field) {
            $fields[] = $this->addPrefix($field);
        }

        return $fields;
    }

    private function addPrefix($field)
    {
        if (isset($field['id'])) {
            $field['id'] = self::PREFIX . $field['id'];
        }

        return $field;
    }
}
