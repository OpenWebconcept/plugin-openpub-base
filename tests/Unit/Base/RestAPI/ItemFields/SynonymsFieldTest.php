<?php

namespace OWC\OpenPub\Tests\Base\RestAPI\ItemFields;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\RestAPI\ItemFields\SynonymsField;
use OWC\OpenPub\Tests\TestCase;
use WP_Mock;
use WP_Post;

class SynonymsFieldTest extends TestCase
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
    public function it_returns_empty_if_no_synonyms_are_used()
    {
        WP_Mock::userFunction('get_post_meta', [
            'return' => false
        ]);

        $synonymsField = new SynonymsField($this->plugin);
        $actual = $synonymsField->create($this->post);

        $this->assertEquals('', $actual);
    }

    /** @test */
    public function it_returns_the_synonyms_if_there_are_synonyms_used()
    {
        WP_Mock::userFunction('get_post_meta', [
            'return' => '\" sofa couch divan'
        ]);

        WP_Mock::userFunction('esc_attr', [
            'return' => 'sofa couch divan'
        ]);

        $synonymsField = new SynonymsField($this->plugin);
        $actual = $synonymsField->create($this->post);

        $this->assertEquals('sofa couch divan', $actual);
    }
}
