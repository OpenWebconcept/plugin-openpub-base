<?php

namespace OWC\OpenPub\Tests\Base\RestAPI\Controllers;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\RestAPI\Controllers\ItemController;
use OWC\OpenPub\Base\RestAPI\ItemFields\FeaturedImageField;
use OWC\OpenPub\Tests\TestCase;
use WP_Mock;
use WP_Post;

class ItemControllerTest extends TestCase
{
    /** @var object */
    protected $plugin;

    protected function setUp(): void
    {
        WP_Mock::setUp();

        $config = m::mock(Config::class);
        $this->plugin = m::mock(Plugin::class);

        $this->plugin->config = $config;
        $this->plugin->loader = m::mock(Loader::class);
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function it_transforms_a_wp_object_to_array()
    {
        $post = m::mock(WP_Post::class);
        $post->ID = 1;
        $post->post_title = 'Test Test';
        $post->post_content = 'Test Content';
        $post->post_excerpt = 'Test excerpt';
        $post->post_date = '01-01-2021';
        $post->post_name = 'test-test';

		$itemController = m::mock(ItemController::class)->makePartial();

		$itemController->shouldReceive('getImageUrl')->andReturn([]);

        WP_Mock::userFunction('get_the_post_thumbnail_url', [
            'args' => [
                $post->ID
            ],
            'times'  => 1,
            'return' => 'url-to-image'
        ]);

        $expected = [
            'id'            => 1,
            'title'         => 'Test Test',
            'content'       => 'Test Content',
            'excerpt'       => 'Test excerpt',
            'date'          => '01-01-2021',
            'thumbnail_url' => 'url-to-image',
			'image'			=> [],
            'slug'          => 'test-test',
        ];

        $this->assertEquals($expected, $itemController->transform($post));
    }
}
