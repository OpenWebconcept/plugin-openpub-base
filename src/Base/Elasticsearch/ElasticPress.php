<?php

namespace OWC\OpenPub\Base\Elasticsearch;

class ElasticPress
{
    /**
     * @var \OWC\OpenPub\Base\Foundation\Config
     */
    private $config;

    /**
     * ElasticPress constructor.
     *
     * @param \OWC\OpenPub\Base\Foundation\Config $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Initialize ElasticPress integration.
     */
    public function init()
    {
        $this->setIndexables();
        $this->setStatuses();
        $this->setIndexPostsArgs();
        $this->setLanguage();
        $this->setPostSyncArgs();
    }

    /**
     * Sets the filter to modify the posttypes which gets indexed in the ElasticSearch instance
     */
    public function setIndexables()
    {
        $indexablesFromConfig = $this->config->get('elasticpress.indexables');
        add_filter('ep_indexable_post_types', function($post_types) use ($indexablesFromConfig) {
            return $indexablesFromConfig;
        }, 11, 1);
    }

    /**
     * Sets additional meta_query information to further determine which posts gets indexed in the ElasticSearch instance
     */
    public function setIndexPostsArgs()
    {

        add_filter('ep_index_posts_args', function($args) {

            //            $args['meta_query'] = [
            //                [
            //                    'key'     => '_owc_openpub_active',
            //                    'value'   => 1,
            //                    'compare' => '=',
            //                ]
            //            ];

            return $args;
        }, 10, 1);
    }

    /**
     * Filters the post statuses for indexation by elasticPress
     */
    public function setStatuses()
    {
        add_filter('ep_indexable_post_status', function($statuses) {
            return ['publish'];
        }, 11, 1);
    }

    /**
     * Set the language for the ES instance.
     */
    public function setLanguage()
    {
        $languageFromConfig = $this->config->get('elasticpress.language');
        add_filter('ep_analyzer_language', function($language, $analyzer) use ($languageFromConfig) {
            return $languageFromConfig;
        }, 10, 2);
    }

    /**
     * Set the args of the post which is synced to the instance.
     */
    public function setPostSyncArgs()
    {
        add_filter('ep_post_sync_args', function($postArgs, $postID) {
            $postArgs = $this->transform($postArgs, $postID);

            return $postArgs;
        }, 10, 2);
    }

    /**
     * Transforms the postArgs to a filterable object.
     *
     * @param $postArgs
     * @param $postID
     *
     * @return array
     */
    protected function transform($postArgs, $postID): array
    {
        $postArgs['post_author'] = isset($postArgs['post_author']) ? $postArgs['post_author'] : '';
        if ( apply_filters('owc/openpub/base/elasticpress/postargs/remote-author', true, $postID) ) {
            $postArgs['post_author']['raw'] = $postArgs['post_author']['display_name'] = $postArgs['post_author']['login'] = '';
        }

        $postArgs['post_meta'] = isset($postArgs['post_meta']) ? $postArgs['post_meta'] : [];
        $postArgs['post_meta'] = apply_filters('owc/openpub/base/elasticpress/postargs/meta', $postArgs['post_meta'], $postID);

        $postArgs['terms'] = isset($postArgs['terms']) ? $postArgs['terms'] : [];
        $postArgs['terms'] = apply_filters('owc/openpub/base/elasticpress/postargs/terms', $postArgs['terms'], $postID);

        //adding openpub-item taxonomies as 'post_meta.terms' field, filled with concatenated term names.
        $taxonomies_data = [
            ['taxonomy_id' => 'openpub-type'],
            ['taxonomy_id' => 'openpub-doelgroep']
        ];
        $collected_terms = [];
        foreach ( $taxonomies_data as $taxonomy_data ) {

            $terms = wp_get_post_terms($postID, $taxonomy_data['taxonomy_id']);

            if ( ! is_wp_error($terms) ) {

                foreach ( $terms as $term ) {
                    $collected_terms[] = $term->name;
                }
            }
        }
        $postArgs['post_meta']['terms'] = implode(',', $collected_terms);

        $postArgs = apply_filters('owc/openpub/base/elasticpress/postargs/all', $postArgs, $postID);

        return $postArgs;
    }

    /**
     * Define all the necessary settings.
     */
    public function setSettings()
    {

        $settings = $this->getSettings();

        if ( isset($settings['_owc_setting_elasticsearch_url']) && ( ! defined('EP_HOST') ) ) {

            if ( isset($settings['_owc_setting_elasticsearch_shield']) && ( ! defined('ES_SHIELD') ) ) {
                define('ES_SHIELD', trim($settings['_owc_setting_elasticsearch_shield']));
            }

            $url     = parse_url($settings['_owc_setting_elasticsearch_url']);
            $build   = [];
            $build[] = $url['scheme'];
            $build[] = '://';
            $build[] = ! empty(ES_SHIELD) ? sprintf('%s@', ES_SHIELD) : '';
            $build[] = $url['host'];
            $build[] = ! empty($url['port']) ? sprintf(':%s', $url['port']) : '';
            $build[] = ! empty($url['path']) ? sprintf('/%s', ltrim($url['path'], '/')) : '/';

            define('EP_HOST', implode('', ( array_filter($build) )));

            update_option('ep_host', EP_HOST);
        }

        if ( isset($settings['_owc_setting_elasticsearch_prefix']) && ( ! defined('EP_INDEX_PREFIX') ) ) {
            define('EP_INDEX_PREFIX', $settings['_owc_setting_elasticsearch_prefix']);
        }

        add_filter('ep_index_name', [$this, 'setIndexNameByEnvironment'], 10, 2);
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
        $prefix = 'owc-openpub';

        if ( defined('EP_INDEX_PREFIX') && EP_INDEX_PREFIX ) {
            $prefix = trim(EP_INDEX_PREFIX) . $prefix;
        }

        $buildIndexName = array_filter([
            $prefix,
            $siteID,
            $this->getEnvironmentVariable()
        ]);

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
}
