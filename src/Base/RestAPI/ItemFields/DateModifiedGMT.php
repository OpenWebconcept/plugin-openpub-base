<?php

/**
 * Adds portal url field to the output.
 */

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Models\Item;
use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class DateModifiedGMT extends CreatesFields
{
    public function create(WP_Post $post): string
    {
        return (Item::makeFrom($post))->getPostModified(true)->format('Y-m-d H:i:s');
    }
}
