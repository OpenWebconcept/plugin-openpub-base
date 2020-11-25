<?php

namespace OWC\OpenPub\Base\Foundation\FeatureFlags;

use Illuminate\Contracts\Config\Repository;

class FeatureFlagProvider implements FeatureFlagProviderInterface
{
    /** @var Repository */
    private $flags;

    /**
     * @param Repository $flags
     */
    public function __construct(Repository $flags)
    {
        $this->flags = $flags;
    }

    /**
     * @inheritDoc
     */
    public function getFlags(): Repository
    {
        return $this->flags;
    }
}
