<?php

namespace OWC\OpenPub\Base\Metabox\Commands;

use Exception;
use OWC\OpenPub\Base\Support\Traits\FlattenArray;
use WP_CLI;
use WP_Post;
use WP_Query;

abstract class AbstractConvert
{
    use FlattenArray;
    
    public function execute(): void
    {
        $holder = [];
        $paged = 1;

        while ($items = $this->getOpenPubItems($paged)) {
            $holder[] = $items;
            $paged = $paged + 1;
        }

        if (empty($holder)) {
            WP_CLI::error('No openpub-items found, stopping execution of this command.');
        }

        foreach ($this->flattenArray($holder) as $item) {
            try {
                $this->convert($item);
            } catch(Exception $e) {
                WP_CLI::error(sprintf('Something went wrong with converting item [%s]. Error: %s', $item->post_title, $e->getMessage()));
            }
        }
    }

    abstract protected function convert(WP_Post $item): void;

    protected function getOpenPubItems(int $paged): array
    {
        $args = [
            'post_type' => 'openpub-item',
            'post_status' => 'any',
            'post_per_page' => 10,
            'paged' => $paged
        ];

        $results = new WP_Query($args);

        return $results->posts ?? [];
    }
}
