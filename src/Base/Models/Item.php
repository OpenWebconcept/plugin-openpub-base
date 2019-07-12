<?php

namespace OWC\OpenPub\Base\Models;

class Item extends Model
{
    protected $posttype = 'openpub-item';

    protected static $globalFields = [];

    /**
     * Add additional query arguments.
     *
     * @param array $args
     *
     * @return $this
     */
    public function query(array $args)
    {
        $this->queryArgs = array_merge($this->queryArgs, $args);

        return $this;
    }
}
