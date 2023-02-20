<?php

namespace OWC\OpenPub\Base\Metabox\Commands;

use WP_Post;

class ConvertHighlighted extends AbstractConvert
{
    /**
     * This meta field value can be '1' or '0' when using the metabox.io plugin
     * With the usage of the cmb2 plugin this meta field has the value 'on' or does not have a value at all.
     */
    protected function convert(WP_Post $item): void
    {
        $highlighted = \get_post_meta($item->ID, '_owc_openpub_highlighted_item', true);

        // Return early when this command is executed after new values have been set by CMB2.
        if ($highlighted === 'on') {
            return;
        }

        if (empty($highlighted) || $highlighted !== '1') {
            \delete_post_meta($item->ID, '_owc_openpub_highlighted_item');

            return;
        }

        \update_post_meta($item->ID, '_owc_openpub_highlighted_item', 'on');
    }
}
