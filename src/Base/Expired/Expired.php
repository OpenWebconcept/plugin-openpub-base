<?php

namespace OWC\OpenPub\Base\Expired;

class Expired
{
    /**
     * Validate if the expiration setting is changed and switched on.
     * Fill '_owc_openpub_expirationdate' meta field of open-pub items which have no value.
     */
    public function expirationOptionChange(array $oldValue, array $newValue): array
    {
        $expireAfterDaysSettingOld = $oldValue['_owc_setting_openpub_expired_auto_after_days'] ?? 0;
        $expireAfterDaysSettingNew = $newValue['_owc_setting_openpub_expired_auto_after_days'] ?? 0;

        // When setting hasn't changed.
        if ($expireAfterDaysSettingOld === $expireAfterDaysSettingNew) {
            return $newValue;
        }

        // When setting isn't on.
        if ($expireAfterDaysSettingNew < 1) {
            return $newValue;
        }

        $this->fillExpiredDates($expireAfterDaysSettingNew);

        return $newValue;
    }

    /**
     * Fill '_owc_openpub_expirationdate' meta field of open-pub items which have no value.
     */
    protected function fillExpiredDates(int $expireAfterDaysSetting): void
    {
        $posts = $this->getPostsWithoutExpirationDate();

        foreach ($posts as $post) {
            update_post_meta($post->ID, '_owc_openpub_expirationdate', (new \DateTime($post->post_date, new \DateTimeZone('Europe/Amsterdam')))->modify('+' . $expireAfterDaysSetting . ' days')->format('Y-m-d H:i'));
        }
    }

    protected function getPostsWithoutExpirationDate(): array
    {
        $args = [
            'post_type' => 'openpub-item',
            'posts_per_page'   => -1,
            'meta_query' => [
                'relation' => 'OR',
                [
                    [
                        'key'     => '_owc_openpub_expirationdate',
                        'compare' => 'NOT EXISTS',
                    ],
                ],
                [
                    [
                        'key'     => '_owc_openpub_expirationdate',
                        'value' => '',
                    ],
                ]
            ]
        ];

        $query = new \WP_Query($args);

        return $query->posts ?? [];
    }
}
