<?php

namespace OWC\OpenPub\Base\ElasticPress;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Repositories\Item;
use OWC\OpenPub\Base\Tests\Unit\TestCase;
use WP_Mock;

class ElasticPressTest extends TestCase
{

    /**
     * @var ElasticPress
     */
    protected $service;

    /**
     * @var
     */
    protected $config;

    /**
     * @var
     */
    protected $plugin;

    protected function setUp(): void
    {
        WP_Mock::setUp();

        $this->config = m::mock(Config::class);

        $this->plugin         = m::mock(Plugin::class);
        $this->plugin->config = $this->config;
        $this->plugin->loader = m::mock(Loader::class);

        $this->item = m::mock(Item::class);

        $this->service = new ElasticPress($this->config, $this->item);
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function it_sets_the_language_from_the_config()
    {
        WP_Mock::expectFilterAdded('ep_analyzer_language', [$this->service, 'setLanguage'], 10, 2);

        $this->plugin->config->shouldReceive('get')->with('elasticpress.language')->andReturn('dutch');

        $this->service->setLanguage('dutch', '');

        $this->service->setFilters();

        $this->assertTrue(true);
    }

    /** @test */
    public function it_sets_the_indexables_from_the_config()
    {
        WP_Mock::expectFilterAdded('ep_indexable_post_types', [$this->service, 'setIndexables'], 11, 1);

        $this->plugin->config->shouldReceive('get')->with('elasticpress.indexables')->andReturn(['test']);

        $this->service->setIndexables(['test']);

        $this->service->setFilters();

        $this->assertTrue(true);
    }

    /** @test */
    public function it_sets_the_correct_post_args_for_syncing()
    {
        WP_Mock::expectFilterAdded('ep_post_sync_args_post_prepare_meta', [$this->service, 'setPostSyncArgs'], 10, 2);

        $this->service->setFilters();

        $this->item->shouldReceive('query')
            ->once()
            ->with([])
            ->andReturn($this->item);

        $this->item->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn(
                [
                    'id'          => 1,
                    'title'       => 'Test title',
                    'content'     => 'Test content',
                    'excerpt'     => 'Test excerpt',
                    'date'        => date('now'),
                    'connected'   => [],
                    'post_author' => [],
                ]
            );

        WP_Mock::onFilter('owc/openpub/base/elasticpress/postargs/remote-author')
            ->with(true)
            ->reply(false);

        $test = $this->service->setPostSyncArgs([], 1);

        $this->assertArrayHasKey('connected', $test);
    }

    /** @test */
    public function it_sets_the_correct_index_name()
    {
        \WP_Mock::userFunction('get_site_url', [
            'times'  => 4,
            'return' => 'owc-openpub',
        ]);

        $indexName = '';
        $siteID    = 1;

        putenv('environment=development');

        $expected = 'owc-openpub--1--development';
        $actual   = $this->service->setIndexNameByEnvironment($indexName, $siteID);

        $this->assertEquals($expected, $actual);

        putenv('environment=test');

        $expected = 'owc-openpub--1--test';
        $actual   = $this->service->setIndexNameByEnvironment($indexName, $siteID);

        $this->assertEquals($expected, $actual);

        putenv('environment=');

        $expected = 'owc-openpub--1';
        $actual   = $this->service->setIndexNameByEnvironment($indexName, $siteID);

        $this->assertEquals($expected, $actual);

        define('EP_INDEX_PREFIX', 'prefix');
        putenv('environment=test');

        $expected = 'prefix--owc-openpub--1--test';
        $actual   = $this->service->setIndexNameByEnvironment($indexName, $siteID);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_transforms_the_post_args_to_required_format()
    {
        $this->item->shouldReceive('query')
            ->once()
            ->with([])
            ->andReturn($this->item);

        $this->item->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn(
                [
                    'id'        => 1,
                    'title'     => 'Test title',
                    'content'   => 'Test content',
                    'excerpt'   => 'Test excerpt',
                    'date'      => '1234567890',
                    'connected' => [],
                ]
            );

        WP_Mock::onFilter('owc/openpub/base/elasticpress/postargs/remote-author')
            ->with(true)
            ->reply(false);

        $postIDStub   = 1;
        $postArgsStub = [
            'ID'                => $postIDStub,
            'post_author'       => [],
            'post_date'         => '',
            'post_date_gmt'     => '',
            'post_title'        => '',
            'post_excerpt'      => '',
            'post_content'      => '',
            'post_status'       => '',
            'post_name'         => '',
            'post_modified'     => '',
            'post_modified_gmt' => '',
            'post_parent'       => '',
            'post_type'         => '',
            'post_mime_type'    => '',
            'permalink'         => '',
            'guid'              => '',
        ];

        $actual = $this->invokeMethod($this->service, 'transform', [
            $postArgsStub,
            $postIDStub,
        ]);

        $expected = [
            'id'            => $postIDStub,
            'post_id'       => $postIDStub,
            'title'         => 'Test title',
            'content'       => 'Test content',
            'excerpt'       => 'Test excerpt',
            'date'          => '1234567890',
            'connected'     => [],
            'post_author'   => [
                'login'        => '',
                'display_name' => '',
                'raw'          => '',
            ],
            'post_content'  => 'Test content',
            'post_excerpt'  => 'Test excerpt',
            'post_status'   => 'publish',
            'post_type'     => 'openpub-item',
            'post_title'    => 'Test title',
            'post_date_gmt' => '1234567890',
        ];

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function test_get_settings()
    {
        \WP_Mock::passthruFunction('get_option', [
            'times'  => 1,
            'return' => '',
        ]);

        $expected = '_owc_openpub_base_settings';
        $actual   = $this->service->getSettings();

        $this->assertEquals($expected, $actual);
    }
}
