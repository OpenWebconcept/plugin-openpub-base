<?php

namespace OWC\OpenPub\Base\Support\Traits;

trait FlattenArray
{
    public function flattenArray($array)
    {
        return array_reduce(
            $array,
            function ($carry, $item) {
                if (is_array($item)) {
                    return array_merge($carry, $this->flattenArray($item));
                } else {
                    $carry[] = $item;
                    return $carry;
                }
            },
            []
        );
    }
}
