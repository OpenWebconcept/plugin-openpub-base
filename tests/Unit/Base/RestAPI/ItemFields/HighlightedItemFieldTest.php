<?php

namespace OWC\OpenPub\Tests\Base\RestAPI\ItemFields;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\RestAPI\ItemFields\HighlightedItemField;
use OWC\OpenPub\Tests\TestCase;
use WP_Mock;
use WP_Post;

class HighlightedItemFieldTest extends TestCase
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
    public function it_returns_false_when_post_highlight_is_false()
    {
        WP_Mock::userFunction('get_post_meta', [
            'return' => false
        ]);

        $highlightedItemField = new HighlightedItemField($this->plugin);
        $condition = $highlightedItemField->create($this->post);

        $this->assertFalse($condition);
    }

    /** @test */
    public function it_returns_true_when_post_highlight_is_enabled()
    {
        WP_Mock::userFunction('get_post_meta', [
            'return' => true
        ]);

        $highlightedItemField = new HighlightedItemField($this->plugin);
        $condition = $highlightedItemField->create($this->post);

        $this->assertTrue($condition);
    }
}
