<?php

namespace OWC\OpenPub\Tests\Base\RestAPI\ItemFields;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\RestAPI\ItemFields\FeaturedImageField;
use OWC\OpenPub\Tests\TestCase;
use WP_Mock;
use WP_Post;

class FeaturedImageFieldTest extends TestCase
{
    const DATETIMEFORMAT = 'Y-m-d H:i';
    const DATEFORMAT = 'Y-m-d';

    protected $post;

    protected $plugin;

    protected $now;

    protected $dateTime;

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
    public function it_does_not_return_a_thumbnail_it_does_not_exists()
    {
        WP_Mock::userFunction('has_post_thumbnail', [
            'return' => false
        ]);

        $featuredImageField = new FeaturedImageField($this->plugin);
        $status = $featuredImageField->create($this->post);

        $this->assertEmpty($status);
    }

    /** @test */
    public function it_returns_a_correct_featured_image_response()
    {
        WP_Mock::userFunction('has_post_thumbnail', [
            'return' => true
        ]);

        WP_Mock::userFunction('get_post_thumbnail_id', [
            'return' => 2
        ]);

        $attachment = m::mock(WP_Post::class);
        $attachment->ID = 1;
        $attachment->post_title = 'title';
        $attachment->post_content = 'content';
        $attachment->post_excerpt = 'excerpt';

        WP_Mock::userFunction('get_post', [
            'return' => $attachment
        ]);

        WP_Mock::userFunction('get_post_meta', [
            'return' => 'alt'
        ]);

        WP_Mock::userFunction('wp_get_attachment_metadata', [
            'return' => ['sizes' => []]
        ]);

        WP_Mock::userFunction('wp_get_attachment_image', [
            'return' => 'large-size'
        ]);

        WP_Mock::userFunction('wp_get_attachment_image_sizes', [
            'return' => 'large-size'
        ]);

        WP_Mock::userFunction('wp_get_attachment_image_srcset', [
            'return' => 'large-size'
        ]);

        $featuredImageField = new FeaturedImageField($this->plugin);
        $actual = $featuredImageField->create($this->post);

        $expected = [
            'title'       => 'title',
            'description' => 'content',
            'caption'     => 'excerpt',
            'alt'         => 'alt',
            'rendered'    => 'large-size',
            'sizes'       => 'large-size',
            'srcset'      => 'large-size',
            'meta'        => [],
        ];
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_returns_a_correct_featured_image_response_with_multiple_size()
    {
        WP_Mock::userFunction('has_post_thumbnail', [
            'return' => true
        ]);

        WP_Mock::userFunction('get_post_thumbnail_id', [
            'return' => 2
        ]);

        $attachment = m::mock(WP_Post::class);
        $attachment->ID = 1;
        $attachment->post_title = 'title';
        $attachment->post_content = 'content';
        $attachment->post_excerpt = 'excerpt';

        WP_Mock::userFunction('get_post', [
            'return' => $attachment
        ]);

        WP_Mock::userFunction('get_post_meta', [
            'return' => 'alt'
        ]);

        WP_Mock::userFunction('wp_get_attachment_metadata', [
            'return' => [
                'sizes' => [
                    'thumbnail' => []
                ],
                'width'  => 100,
                'height' => 100,
            ]
        ]);

        WP_Mock::userFunction('get_attached_file', [
            'return' => 'url-attached-full-file'
        ]);

        WP_Mock::userFunction('get_post_mime_type', [
            'return' => 'image/png'
        ]);

        WP_Mock::userFunction('wp_get_attachment_image_src', [
            'return_in_order' => [['url-to-thumbnail-image'], ['url-to-full-image']]
        ]);

        WP_Mock::userFunction('wp_get_attachment_image', [
            'return_in_order' => ['url-to-thumbnail-image', 'url-to-full-image']
        ]);

        WP_Mock::userFunction('wp_get_attachment_image', [
            'return' => 'url-to-large-image'
        ]);

        WP_Mock::userFunction('wp_get_attachment_image_sizes', [
            'return' => '(max-width: 1024px) 100vw, 1024px'
        ]);

        WP_Mock::userFunction('wp_get_attachment_image_srcset', [
            'return' => 'large-size'
        ]);

        $featuredImageField = new FeaturedImageField($this->plugin);
        $actual = $featuredImageField->create($this->post);

        $expected = [
            'title'       => 'title',
            'description' => 'content',
            'caption'     => 'excerpt',
            'alt'         => 'alt',
            'rendered'    => 'url-to-full-image',
            'sizes'       => '(max-width: 1024px) 100vw, 1024px',
            'srcset'      => 'large-size',
            'meta'        => [
                'sizes' => [
                    'full' => [
                        'file'      => 'url-attached-full-file',
                        'width'     => 100,
                        'height'    => 100,
                        'mime-type' => 'image/png',
                        'url'       => 'url-to-full-image',
                        'rendered'  => 'url-to-full-image'
                    ],
                    'thumbnail' => [
                        'url'      => 'url-to-thumbnail-image',
                        'rendered' => 'url-to-thumbnail-image'
                    ]
                ],
                'width'  => 100,
                'height' => 100,
            ],
        ];
        $this->assertEquals($expected, $actual);
    }
}
