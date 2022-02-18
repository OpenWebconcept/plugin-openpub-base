<?php

namespace OWC\OpenPub\Base\Models;

use OWC\OpenPub\Base\Settings\SettingsPageOptions;

class PortalLinkGenerator
{
    protected string $portalURL = '';

    public function __construct(Item $post)
    {
        $this->post = $post;
        $this->pubSettings = SettingsPageOptions::make();
    }

    private function updatePortalURL(string $value = ''): void
    {
        if (empty($value)) {
            return;
        }

        $this->portalURL = sprintf('%s%s', $this->portalURL, trailingslashit($value));
    }

    public function generateFullPortalLink(): string
    {
        $this->createPortalSlug()->appendPostSlug();

        return $this->portalURL;
    }

    public function generateBasePortalLink(): string
    {
        $this->createPortalSlug();

        return $this->portalURL;
    }

    private function createPortalSlug(): self
    {
        $portalURL = $this->pubSettings->getPortalURL();
        $portalSlug = $this->pubSettings->getPortalItemSlug();

        $this->updatePortalURL($portalURL);
        $this->updatePortalURL($portalSlug);

        return $this;
    }

    private function appendPostSlug(): self
    {
        if (!empty($this->post->getPostName())) {
            $this->updatePortalURL($this->post->getPostName());
        } else {
            // Drafts do not have a post_name so use the sanitized title instead.
            $this->updatePortalURL(sanitize_title($this->post->getTitle(), 'untitled-draft'));
        }

        return $this;
    }

    public static function make(Item $post): self
    {
        return new static($post);
    }
}
