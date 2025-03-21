<?php

namespace OWC\OpenPub\Base\Models;

use OWC\OpenPub\Base\Settings\SettingsPageOptions;
use WP_Term;

class PortalLinkGenerator
{
    protected Item $post;
    protected SettingsPageOptions $pubSettings;

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

        if ($this->pubSettings->useShowOn()) {
            $portalURL = $this->getShowOnPortalURL();
        }

        $portalSlug = $this->pubSettings->getPortalItemSlug();

        $this->updatePortalURL($portalURL);
        $this->updatePortalURL($portalSlug);

        return $this;
    }

    private function getShowOnPortalURL(): string
    {
        $terms = wp_get_object_terms($this->post->getID(), 'openpub-show-on');

        if (! is_array($terms) || empty($terms)) {
            return '';
        }

        $portalURL = reset($terms);

        if (isset($_GET['source'])) {
            foreach ($terms as $term) {
                if ($term->slug === $_GET['source']) {
                    $portalURL = $term;

                    break;
                }
            }
        }
        $portalURL = $portalURL instanceof WP_Term ? $portalURL->name : '';

        return wp_http_validate_url($portalURL) ? $portalURL : '/';
    }

    private function appendPostSlug(): self
    {
        if (! empty($this->post->getPostName())) {
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
