<?php

namespace OWC\OpenPub\Base\Taxonomy;

class TaxonomyController
{
    /**
     * Add 'show on' additional explanation.
     *
     * @param string $taxonomy
     *
     * @return void
     */
    public function addShowOnExplanation(string $taxonomy): void
    {
        if ('openpub-show-on' !== $taxonomy) {
            return;
        }

        echo '<div class="form-field">
            <h3>' . __('Additional explanation', 'openpub-base') . '</h3>
            <p>' . __('The slug value must be the ID of the blog you want to add as term. The ID is used for displaying the correct openpub-items on every blog.', 'openpub-base') . '</p>
            </div>';
    }
}
