<?php

namespace OWC\OpenPub\Base\ElasticPress;

use OWC\OpenPub\Base\Repositories\Item;

class ElasticPress
{
    /**
     * @var \OWC\OpenPub\Base\Foundation\Config
     */
    private $config;

    /**
     * @var Item $item
     */
    protected $item;

    /**
     * ElasticPress constructor.
     *
     * @param \OWC\OpenPub\Base\Foundation\Config $config
     */
    public function __construct($config, Item $item)
    {
        $this->config = $config;
        $this->item   = $item;
    }

    /**
     * Initialize ElasticPress integration.
     */
    public function init()
    {
        $this->setFilters();
    }

    public function setFilters()
    {
        /**
         * Default settings
         */
        add_filter('ep_index_name', [$this, 'setIndexNameByEnvironment'], 10, 2);
        add_filter('ep_pre_request_host', [$this, 'setRequestHost'], 10, 4);
        add_filter('ep_pre_request_url', [$this, 'setRequestUrl'], 10, 5);

        /**
         * Search settings
         */
        add_filter('ep_search_fields', [$this, 'setSearchFields'], 10, 2);
        add_filter('ep_search_args', [$this, 'setSearchArgs'], 10, 3);
        add_filter('formattedArgs', [$this, 'setFormattedArgs'], 11, 2);
        add_filter('epwr_decay', [$this, 'setDecay'], 10, 3);
        add_filter('epwr_offset', [$this, 'setOffset'], 10, 3);
        add_filter('ep_indexable_post_types', [$this, 'setIndexables'], 11, 1);
        add_filter('ep_indexable_post_status', [$this, 'setStatuses']);
        add_filter('ep_analyzer_language', [$this, 'setLanguage'], 10, 2);
        add_filter('ep_post_sync_args_post_prepare_meta', [$this, 'setPostSyncArgs'], 10, 2);
        add_filter('ep_index_posts_args', [$this, 'setIndexPostsArgs'], 10, 1);
    }

    /**
     * Set decay of post.
     *
     * @param int $decay
     * @param array $formatted_args
     * @param array $args
     *
     * @return int
     */
    public function setDecay($decay, $formatted_args, $args)
    {
        return $this->config->get('elasticpress.expire.decay');
    }

    /**
     * Set offset of the decay of post.
     *
     * @param string $decay
     * @param array $formatted_args
     * @param array $args
     *
     * @return string
     */
    public function setOffset($decay, $formatted_args, $args)
    {
        return $this->config->get('elasticpress.expire.offset');
    }

    /**
     * Weight more recent content in searches.
     *
     * @param  array $formattedArgs
     * @param  array $args
     *
     * @return array
     */
    public function setFormattedArgs($formattedArgs, $args)
    {

        // Move the existing query.
        $existing_query = $formattedArgs['query'];
        unset($formattedArgs['query']);
        $formattedArgs['query']['function_score']['query'] = $existing_query;

        /**
         * Add filter matches that will weight the results.
         *
         * Use any combination of filters here, any matched filter will adjust the weighted results
         * according to the scoring settings set below. This example pseudo code below matches a custom term with the current or a parent item.
         */
        $formattedArgs['query']['function_score']['functions'] = [

            // The current item gets a weight of 3.
            [
                "filter" => [
                    "match" => [
                        "post_title" => get_query_var('s'),
                    ],
                ],
                "weight" => $this->config->get('elasticpress.search.weight'),
            ],
        ];

        // Specify how the computed scores are combined.
        $formattedArgs['query']['function_score']["score_mode"] = "sum";
        $formattedArgs['query']['function_score']["boost_mode"] = "multiply";

        return $formattedArgs;
    }

    /**
     * Sets the filter to modify the posttypes which gets indexed in the ElasticSearch instance
     */
    public function setIndexables($postTypes)
    {
        return $this->config->get('elasticpress.indexables');
    }

    /**
     * Sets additional meta_query information to further determine which posts gets indexed in the ElasticSearch instance
     *
     * @param array $args
     * @return array
     */
    public function setIndexPostsArgs($args)
    {
        $items = (new Item())
            ->query(apply_filters('owc/openpub/rest-api/items/query', []));

        $args = array_merge($args, $items->getQueryArgs());

        return $args;
    }

    /**
     * Filters the post statuses for indexation by elasticPress.
     *
     * @param array $statuses
     * @return array
     */
    public function setStatuses($statuses)
    {
        return $this->config->get('elasticpress.postStatus');
    }

    /**
     * Set the language for the ES instance.
     *
     * @var string $language
     * @return string
     */
    public function setLanguage($language, $analyzer)
    {
        return $this->config->get('elasticpress.language');
    }

    /**
     * Set the args of the post which is synced to the instance.
     *
     * @param array $item
     * @param int $postID
     * @return array
     */
    public function setPostSyncArgs($item, $postID)
    {
        return $this->transform($item, $postID);
    }

    /**
     * Transforms the postArgs to a filterable object.
     *
     * @param $item
     * @param $postID
     *
     * @return array
     * @throws \OWC\OpenPub\Base\Exceptions\PropertyNotExistsException
     * @throws \ReflectionException
     */
    protected function transform($item, $postID): array
    {
        $author = $item['post_author'] ?? [];
        $item   = $this->item
            ->query(apply_filters('owc/openpub/rest-api/items/query/single', []))
            ->find($postID);

        $item['post_author'] = $author;
        if (apply_filters('owc/openpub/base/elasticpress/postargs/remote-author', true, $postID)) {
            $item['post_author']['raw'] = $item['post_author']['display_name'] = $item['post_author']['login'] = '';
        }

        $item = $this->formatOutputForElasticsearch($item);

        return $item;
    }

    /**
     * Format output for Elasticsearch format
     *
     * @param array $item
     * @return array
     */
    protected function formatOutputForElasticsearch(array $item): array
    {
        $item['post_id']       = $item['id'] ?? '';
        $item['post_title']    = $item['title'] ?? '';
        $item['post_content']  = $item['content'] ?? '';
        $item['post_excerpt']  = $item['excerpt'] ?? '';
        $item['post_date_gmt'] = $item['date'] ?? '';
        $item['post_status']   = 'publish';
        $item['post_type']     = 'openpub-item';
        return $item;
    }

    /**
     * Alter the mappings.
     *
     * @param array $mapping
     *
     * @return array
     */
    public function addMappings(array $mapping): array
    {
        $mapping['mappings']['properties'] = [
            'expired' => [
                'type'       => 'object',
                'properties' => [
                    'on' => [
                        'type'    => 'object',
                        'enabled' => 'false'
                    ]
                ]
            ],
            'post_date_gmt'         => [
                'type'   => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ],
        ];

        return $mapping;
    }

    /**
     * Define all the necessary settings.
     */
    public function setSettings()
    {
        $settings = $this->getSettings();

        if (isset($settings['_owc_setting_elasticsearch_url']) && (!defined('EP_HOST'))) {
            if (isset($settings['_owc_setting_elasticsearch_shield']) && (!defined('ES_SHIELD'))) {
                define('ES_SHIELD', trim($settings['_owc_setting_elasticsearch_shield']));
            }

            $url     = parse_url($settings['_owc_setting_elasticsearch_url']);
            $build[] = $url['scheme'] . '://';
            $build[] = defined('ES_SHIELD') ? sprintf('%s@', ES_SHIELD) : '';
            $build[] = $url['host'];
            $build[] = !empty($url['port']) ? sprintf(':%s', $url['port']) : '';
            $build[] = !empty($url['path']) ? sprintf('/%s', ltrim($url['path'], '/')) : '/';

            define('EP_HOST', implode('', (array_filter($build))));

            update_option('ep_host', EP_HOST);
        }

        if (isset($settings['_owc_setting_elasticsearch_prefix']) && (!defined('EP_INDEX_PREFIX'))) {
            define('EP_INDEX_PREFIX', $settings['_owc_setting_elasticsearch_prefix']);
        }
    }

    /**
     * Sets the uniformed indexName for ElasticSearch, based on prefix, environment variable and site ID.
     *
     * @param $indexName
     * @param $siteID
     *
     * @return string
     */
    public function setIndexNameByEnvironment($indexName, $siteID)
    {
        $siteUrl      = pathinfo(get_site_url());
        $siteBasename = $siteUrl['basename'];

        if (defined('EP_INDEX_PREFIX') && EP_INDEX_PREFIX) {
            $siteBasename = EP_INDEX_PREFIX . '--' . $siteBasename;
        }

        $buildIndexName = array_filter(
            [
                $siteBasename,
                $siteID,
                $this->getEnvironmentVariable(),
            ]
        );

        $indexName = implode('--', $buildIndexName);

        return $indexName;
    }

    /**
     * @return array|false|string
     */
    protected function getEnvironmentVariable()
    {
        return getenv('environment');
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return get_option('_owc_openpub_base_settings', []);
    }

    public function setSearchArgs($args, $scope, $query_args)
    {
        return $args;
    }

    /**
     * @param $searchFields
     * @param $args
     *
     * @return array
     */
    public function setSearchFields($searchFields, $args)
    {
        $searchFields[] = 'meta';
        $searchFields[] = 'connected';

        return $searchFields;
    }

    /**
     * @param $host
     * @param $failures
     * @param $path
     * @param $args
     *
     * @return mixed
     */
    public function setRequestHost($host, $failures, $path, $args)
    {
        return $host;
    }

    /**
     * @param $url
     * @param $failures
     * @param $host
     * @param $path
     * @param $args
     *
     * @return mixed
     */
    public function setRequestUrl($url, $failures, $host, $path, $args)
    {
        return $url;
    }
}
