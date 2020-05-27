<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Tests\Unit\TestCase;
use WP_Mock;
use WP_Post;

class ExpiredFieldTest extends TestCase
{
    const DATETIMEFORMAT = 'Y-m-d H:i';
    const DATEFORMAT     = 'Y-m-d';

    protected $post;

    protected $plugin;

    protected $now;

    protected $dateTime;

    protected function setUp(): void
    {
        WP_Mock::setUp();

        $config       = m::mock(Config::class);
        $this->plugin = m::mock(Plugin::class);

        $this->plugin->config = $config;
        $this->plugin->loader = m::mock(Loader::class);

        $this->post     = m::mock(WP_Post::class);
        $this->post->ID = 1;

        WP_Mock::userFunction('get_option', [
            'args'   => 'timezone_string',
            'return' => 'Europe/Amsterdam'
        ]);

        $this->now      = date(self::DATETIMEFORMAT);
        $this->dateTime = new \DateTimeZone('Europe/Amsterdam');
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function return_expired_status_if_no_status_is_available()
    {
        WP_Mock::userFunction('get_post_meta', [
            'args' => [
                $this->post->ID,
                '_owc_openpub_expirationdate',
                'true'
            ],
            'times'  => 1,
            'return' => ''
        ]);

        $expiredField = new ExpiredField($this->plugin);
        $status       = $expiredField->create($this->post);

        $this->assertTrue(is_array($status));

        $this->assertEquals([
                    'message' => '',
                    'status'  => false,
                    'on'      => false
            ], $status);
    }

    /** @test */
    public function return_expired_status_when_date_is_in_future()
    {
        $futureDate = \DateTime::createFromFormat(self::DATETIMEFORMAT, $this->now, $this->dateTime);
        $futureDate->modify("+1 day");

        WP_Mock::userFunction('get_post_meta', [
            'args' => [
                $this->post->ID,
                '_owc_openpub_expirationdate',
                'true'
            ],
            'times'  => 1,
            'return' => $futureDate->format(self::DATETIMEFORMAT)
        ]);

        $expiredField = new ExpiredField($this->plugin);
        $status       = $expiredField->create($this->post);
        $this->assertTrue(is_array($status));
        $this->assertEquals([
                    'message' => '',
                    'status'  => false,
                    'on'      => $futureDate
            ], $status);
    }

    /** @test */
    public function return_expired_status_when_date_has_no_time()
    {
        $futureDate = \DateTime::createFromFormat(self::DATETIMEFORMAT, $this->now, $this->dateTime);
        $futureDate->modify("midnight +1 day");

        WP_Mock::userFunction('get_post_meta', [
            'args' => [
                $this->post->ID,
                '_owc_openpub_expirationdate',
                'true'
            ],
            'times'  => 1,
            'return' => $futureDate->format(self::DATEFORMAT)
        ]);

        $expiredField = new ExpiredField($this->plugin);
        $status       = $expiredField->create($this->post);
        $this->assertTrue(is_array($status));
        $this->assertEquals([
                    'message' => '',
                    'status'  => false,
                    'on'      => $futureDate
            ], $status);
    }

    /** @test */
    public function return_expired_status_when_date_is_in_past()
    {
        $pastDate = \DateTime::createFromFormat(self::DATETIMEFORMAT, $this->now, $this->dateTime);
        $pastDate->modify("-1 day");

        WP_Mock::userFunction('get_post_meta', [
            'args' => [
                $this->post->ID,
                '_owc_openpub_expirationdate',
                'true'
            ],
            'times'  => 1,
            'return' => $pastDate->format(self::DATETIMEFORMAT)
        ]);

        $expiredField = new ExpiredField($this->plugin);
        $status       = $expiredField->create($this->post);
        $this->assertTrue(is_array($status));
        $this->assertEquals([
                    'message' => 'Item is expired',
                    'status'  => true,
                    'on'      => $pastDate
            ], $status);
    }
}
