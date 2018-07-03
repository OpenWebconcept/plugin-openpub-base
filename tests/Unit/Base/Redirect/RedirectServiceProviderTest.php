<?php

namespace OWC\OpenPub\Base\Redirect;

use Mockery as m;
use OWC\OpenPub\Base\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Tests\Unit\TestCase;

class RedirectServiceProviderTest extends TestCase
{

    public function setUp()
    {
        \WP_Mock::setUp();
    }

    public function tearDown()
    {
        \WP_Mock::tearDown();
    }

    /** @test */
    public function check_template_redirect_action()
    {
        $config = m::mock(Config::class);
        $plugin = m::mock(Plugin::class);

        $plugin->config = $config;
        $plugin->loader = m::mock(Loader::class);

        $service = new RedirectServiceProvider($plugin);

        $plugin->loader->shouldReceive('addAction')->withArgs([
            'template_redirect',
            $service,
            'redirectToMarketingSite',
            10
        ])->once();

        $service->register();

        $this->assertTrue(true);
    }

    /** @test */
    public function check_redirect_all_but_admin_method()
    {
        $config = m::mock(Config::class);
        $plugin = m::mock(Plugin::class);

        $plugin->config = $config;
        $plugin->loader = m::mock(Loader::class);

        $service = new RedirectServiceProvider($plugin);

        \WP_Mock::userFunction('is_admin', [
                'args'   => null,
                'times'  => '1',
                'return' => false
            ]
        );

        \WP_Mock::userFunction('wp_doing_ajax', [
                'args'   => null,
                'times'  => '1',
                'return' => false
            ]
        );

        \WP_Mock::userFunction('is_feed', [
                'args'   => null,
                'times'  => '1',
                'return' => false
            ]
        );

        \WP_Mock::userFunction('wp_redirect', [
                'args'   => 'https://www.openwebconcept.nl/',
                'times'  => '1',
                'return' => false
            ]
        );

        $service->redirectToMarketingSite();

        $this->assertTrue(true);
    }

}
