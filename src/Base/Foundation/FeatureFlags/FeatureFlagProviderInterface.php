<?php

namespace OWC\OpenPub\Base\Foundation\FeatureFlags;

use Illuminate\Contracts\Config\Repository;

interface FeatureFlagProviderInterface
{
    /**
     * Returns an associative array with flag-code as key and the status (enabled/disabled) as value.
     *
     * @return Repository
     */
    public function getFlags(): Repository;
}
