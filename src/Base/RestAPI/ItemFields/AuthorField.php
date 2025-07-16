<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class AuthorField extends CreatesFields
{
    public function create(WP_Post $post): ?int
    {
        return isset($post->post_author) ? (int) $post->post_author : null;
    }
}
