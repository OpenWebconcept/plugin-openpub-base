<?php

namespace OWC\OpenPub\Base\RestAPI;

use Mockery as m;
use OWC\OpenPub\Base\Config;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Tests\Unit\TestCase;

class RestAPIServiceProviderTest extends TestCase
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
	public function check_registration_of_rest_endpoints()
	{
		$config = m::mock(Config::class);
		$plugin = m::mock(Plugin::class);

		$plugin->config = $config;
		$plugin->loader = m::mock(Loader::class);

		$service = new RestAPIServiceProvider($plugin);

		$plugin->loader->shouldReceive('addFilter')->withArgs([
			'owc/config-expander/rest-api/whitelist',
			$service,
			'filterEndpointsWhitelist',
			10,
			1
		])->once();

		$plugin->loader->shouldReceive('addFilter')->withArgs([
			'rest_api_init',
			$service,
			'registerRestAPIEndpointsFields',
			10
		])->once();

		$plugin->loader->shouldReceive('addFilter')->withArgs([
			'rest_prepare_openpub-item',
			$service,
			'filterRestPrepareOpenPubItem',
			10,
			3
		])->once();

		$service->register();

		$this->assertTrue(true);

		$configRestAPIFields = [
			'posttype1' => [
				'endpoint_field1' =>
					[
						'get_callback'    => ['object', 'callback1'],
						'update_callback' => null,
						'schema'          => null,
					],
				'endpoint_field2' =>
					[
						'get_callback'    => ['object', 'callback2'],
						'update_callback' => null,
						'schema'          => null,
					]
			],
			'posttype2' => [
				'endpoint_field1' =>
					[
						'get_callback'    => ['object', 'callback1'],
						'update_callback' => null,
						'schema'          => null,
					],
				'endpoint_field2' =>
					[
						'get_callback'    => ['object', 'callback2'],
						'update_callback' => null,
						'schema'          => null,
					]
			]
		];

		$config->shouldReceive('get')->with('rest_api_fields')->once()->andReturn($configRestAPIFields);

		\WP_Mock::userFunction('post_type_exists', [
				'args'   => [\WP_Mock\Functions::anyOf('posttype1', 'posttype2')],
				'times'  => '0+',
				'return' => true
			]
		);

		\WP_Mock::userFunction('register_rest_field', [
			'args'   => [
				\WP_Mock\Functions::anyOf('posttype1', 'posttype2'),
				\WP_Mock\Functions::anyOf('endpoint_field1', 'endpoint_field2'),
				'*'
			],
			'times'  => '0+'
		]);

		$service->registerRestAPIEndpointsFields();

		$this->assertTrue(true);
	}
}
