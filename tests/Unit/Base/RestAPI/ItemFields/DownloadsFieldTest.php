<?php

namespace OWC\OpenPub\Tests\Base\RestAPI\ItemFields;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\RestAPI\ItemFields\DownloadsField;
use OWC\OpenPub\Tests\TestCase;
use WP_Mock;
use WP_Post;

class DownloadsFieldTest extends TestCase
{
    protected $post;

    protected $plugin;

    protected function setUp(): void
    {
        WP_Mock::setUp();

        $config = m::mock(Config::class);
        $this->plugin = m::mock(Plugin::class);

        $this->plugin->config = $config;
        $this->plugin->loader = m::mock(Loader::class);

        $this->post = m::mock(WP_Post::class);
        $this->post->ID = 1;
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function it_returns_the_downloads()
    {
        WP_Mock::userFunction('get_post_meta', [
            'return' => [
                [
                    'openpub_downloads_url'   => 'url',
                    'openpub_downloads_title' => 'title',
                ]
            ]
        ]);

        $downloadsField = new DownloadsField($this->plugin);
        $actual = $downloadsField->create($this->post);

        $this->assertTrue(is_array($actual));

        $this->assertEquals([
            [
                    'title' => 'title',
                    'url'   => 'url'
            ]
            ], $actual);
    }
}
