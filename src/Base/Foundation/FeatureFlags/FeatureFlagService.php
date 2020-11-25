<?php

namespace OWC\OpenPub\Base\Foundation\FeatureFlags;

use Exception;

class FeatureFlagService
{
    /** @var FeatureFlagProviderInterface */
    private $provider;

    /** @var bool */
    private $defaultPolicy;

    /**
     * @param FeatureFlagProviderInterface $provider
     * @param bool $defaultPolicy
     */
    public function __construct(FeatureFlagProviderInterface $provider = null, bool $defaultPolicy = false)
    {
        $this->defaultPolicy = $defaultPolicy;
        $this->provider      = $provider;
    }

    /**
     * Sets/updates the flags provider
     *
     * @param FeatureFlagProviderInterface $provider
     * @return self
     */
    public function setProvider(FeatureFlagProviderInterface $provider): self
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * Checks if the given flag is active, calls the optional callback if provided an feature is active and returns the status of the flag.
     *
     * @param string $flag
     * @param callable|null $callback
     *
     * @return bool
     */
    public function isActive(string $flag, callable $callback = null, $defaultValue = ''): bool
    {
        $flag = 'features.'. $flag;
        try {
            if ($this->provider->getFlags()->has($flag)) {
                $isActive = $this->provider->getFlags()->get($flag, $defaultValue);
            } else {
                $isActive = (bool) $defaultValue;
            }
        } catch (Exception $e) {
            return (bool) $defaultValue;
        }

        if ($isActive && $callback) {
            call_user_func($callback);
        }

        return $isActive;
    }
}
