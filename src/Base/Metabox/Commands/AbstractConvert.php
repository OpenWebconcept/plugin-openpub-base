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

    protected string $command;
    
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

        $this->registerExecutedCommand();
    }

    abstract protected function convert(WP_Post $item): void;

    protected function getOpenPubItems(int $paged): array
    {
        $args = [
            'post_type' => 'openpub-item',
            'post_status' => 'any',
            'posts_per_page' => 10,
            'paged' => $paged
        ];

        $results = new WP_Query($args);

        return $results->posts ?? [];
    }

    /**
     * Register which command was executed.
     * Used for displaying a critical admin notice that states there is an urgent action required.
     */
    protected function registerExecutedCommand(): void
    {
        $optionName = '_owc_openpub_base_executed_commands_upgrade_v3';
        $executedCommands = \get_option($optionName, []);

        if (empty($executedCommands)) {
            $executedCommands = [$this->command];
        } else {
            array_push($executedCommands, $this->command);
        }

        \update_option($optionName, array_unique($executedCommands));
    }
}
