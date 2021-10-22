<?php

namespace OWC\OpenPub\Tests\Base\RestAPI\ItemFields;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\RestAPI\ItemFields\CommentField;
use OWC\OpenPub\Tests\TestCase;
use WP_Comment;
use WP_Mock;
use WP_Post;

class CommentFieldTest extends TestCase
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
        $this->post->comment_count = 0;
        $this->post->comment_status = 'closed';
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function it_returns_with_no_results_if_comments_are_disabled()
    {
        $this->post->comment_count = 0;
        $this->post->comment_status = 'closed';

        $commentField = new CommentField($this->plugin);
        $actual = $commentField->create($this->post);

        $expected = [
            'count'  => 0,
            'status' => 'closed',
            'items'  => []
        ];
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_returns_the_comments_if_comments_are_enabled_but_there_are_no_comments()
    {
        $this->post->comment_count = 0;
        $this->post->comment_status = 'open';

        WP_Mock::userFunction('get_comments', [
            'return' => []
        ]);

        $commentField = new CommentField($this->plugin);
        $actual = $commentField->create($this->post);

        $expected = [
            'count'  => 0,
            'status' => 'open',
            'items'  => []
        ];
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_returns_the_comments_if_comments_are_enabled_and_there_are_comments()
    {
        $this->post->comment_count = 2;
        $this->post->comment_status = 'open';

        $comment1 = m::mock(WP_Comment::class);
        $comment1->shouldReceive('get_children')
            ->andReturn([]);
        $comment1->comment_ID = 1;
        $comment1->comment_parent = 0;
        $comment1->comment_author = 'author 1';
        $comment1->comment_content = 'comment 1';
        $comment1->comment_date = '13-01-2020';

        $comment_child2 = m::mock(WP_Comment::class);
        $comment_child2->shouldReceive('get_children')
            ->andReturn([]);
        $comment_child2->comment_ID = 4;
        $comment_child2->comment_parent = 3;
        $comment_child2->comment_author = 'child author 2';
        $comment_child2->comment_content = 'child comment 2';
        $comment_child2->comment_date = '12-01-2020';

        $comment_child1 = m::mock(WP_Comment::class);
        $comment_child1->shouldReceive('get_children')
            ->andReturn([$comment_child2]);
        $comment_child1->comment_ID = 3;
        $comment_child1->comment_parent = 2;
        $comment_child1->comment_author = 'child author 1';
        $comment_child1->comment_content = 'child comment 1';
        $comment_child1->comment_date = '12-01-2020';

        $comment2 = m::mock(WP_Comment::class);
        $comment2->shouldReceive('get_children')
            ->andReturn([$comment_child1]);
        $comment2->comment_ID = 2;
        $comment2->comment_parent = 0;
        $comment2->comment_author = 'author 2';
        $comment2->comment_content = 'comment 2';
        $comment2->comment_date = '12-01-2020';

        WP_Mock::userFunction('get_comments', [
            'return' => [$comment1, $comment2]
        ]);

        $commentField = new CommentField($this->plugin);
        $actual = $commentField->create($this->post);

        $expected = [
            'count'  => 2,
            'status' => 'open',
            'items'  => [
                [
                    'id'       => 1,
                    'parentid' => 0,
                    'author'   => 'author 1',
                    'content'  => 'comment 1',
                    'date'     => '13-01-2020',
                    'replies'  => []
                ],
                [
                    'id'       => 2,
                    'parentid' => 0,
                    'author'   => 'author 2',
                    'content'  => 'comment 2',
                    'date'     => '12-01-2020',
                    'replies'  => [
                         [
                            'id'       => 3,
                            'parentid' => 2,
                            'author'   => 'child author 1',
                            'content'  => 'child comment 1',
                            'date'     => '12-01-2020',
                            'replies'  => [
                                [
                                    'id'       => 4,
                                    'parentid' => 3,
                                    'author'   => 'child author 2',
                                    'content'  => 'child comment 2',
                                    'date'     => '12-01-2020',
                                    'replies'  => []
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_returns_nothing_if_comments_are_not_allow_to_be_outputted_in_the_api()
    {
        WP_Mock::userFunction('get_comments', [
            'return' => []
        ]);

        $commentField = new CommentField($this->plugin);
        $actual = $commentField->executeCondition();

        $this->assertInstanceOf('Closure', $actual);
        $this->assertFalse($actual());
    }

    /** @test */
    public function it_returns_true_if_comments_are_allow_to_be_outputted_in_the_api()
    {
        WP_Mock::userFunction('get_comments', [
            'return' => []
        ]);

        $_REQUEST['with'] = 'comments';
        $commentField = new CommentField($this->plugin);
        $actual = $commentField->executeCondition();

        $this->assertTrue($actual());
    }
}
