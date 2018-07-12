<?php

namespace OWC\OpenPub\Base\PostType;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Tests\Unit\TestCase;

class PostTypeServiceProviderTest extends TestCase
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
	public function check_registration_of_posttypes()
	{
		$config = m::mock(Config::class);
		$plugin = m::mock(Plugin::class);

		$plugin->config = $config;
		$plugin->loader = m::mock(Loader::class);

		$service = new PostTypeServiceProvider($plugin);

		$plugin->loader->shouldReceive('addAction')->withArgs([
			'init',
			$service,
			'registerPostTypes'
		])->once();

		/**
		 * Examples of registering post types: http://johnbillion.com/extended-cpts/
		 */
		$configPostTypes = [
			'posttype'        => [
				'args'  => [
				],
				'names' => [
				]
			]
		];

		$service->register();

		$this->assertTrue(true);
	}
}
