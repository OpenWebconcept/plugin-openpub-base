<?php

namespace OWC\OpenPub\Base\PostType;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\PostType\PostTypes\OpenPubItemModel;
use OWC\OpenPub\Base\Tests\Unit\TestCase;

class OpenPubItemModelTest extends TestCase
{

    public function setUp()
    {
        \WP_Mock::setUp();
    }

    public function tearDown()
    {
        \WP_Mock::tearDown();
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /** @test */
    public function check_model_construct()
    {
        $config = m::mock(Config::class);
        $openpub_item = m::mock(OpenPubItemModel::class);

        $model = new OpenPubItemModel($config);

        $this->assertTrue(true);
    }

    // /** @test */
    public function check_get_terms_as_array_methods()
    {
        $config = m::mock(Config::class);
        $openpub_item = m::mock(OpenPubItemModel::class);

        $model = new OpenPubItemModel($config);

        $term1 = new \stdClass();
        $term1->term_id = 1;
        $term1->name = 'term_name1';
        $term1->slug = 'term_slug1';

        $term2 = new \stdClass();
        $term2->term_id = 2;
        $term2->name = 'term_name2';
        $term2->slug = 'term_slug2';

        $terms[] = $term1;
        $terms[] = $term2;

        $object = [ 'id' => 1 ];
        $taxonomyId = 1;

        $output = [
            [
                'ID'   => 1,
                'name' => 'term_name1',
                'slug' => 'term_slug1'
            ],
            [
                'ID'   => 2,
                'name' => 'term_name2',
                'slug' => 'term_slug2'
            ]
        ];

        //$this->assertEquals( $output, $this->invokeMethod($model, 'getTermsAsArray', array($object, 1)));
    }

    /** @test */
    public function check_get_title_alternative_method()
    {
        $config = m::mock(Config::class);
        $openpub_item = m::mock(OpenPubItemModel::class);

        $model = new OpenPubItemModel($config);

        $output = [
            [
                'ID'   => 1,
                'name' => 'term_name1',
                'slug' => 'term_slug1'
            ],
            [
                'ID'   => 2,
                'name' => 'term_name2',
                'slug' => 'term_slug2'
            ]
        ];

        $object['id'] = 1;
        $fieldName = 'field_name';
        $request = null;

        $title_alternative = '<h1>Alternative Title</h1>';

        \WP_Mock::userFunction('get_post_meta', [
                'args'   => [ $object['id'], '_owc_openpub_titel_alternatief', true ],
                'times'  => '1',
                'return' => $title_alternative
            ]
        );

        $output = 'Alternative Title';

        \WP_Mock::expectFilter('owc/openpub-base/rest-api/openpubitem/field/get-title-alternative', $output, $object,
            $fieldName, $request);

        $this->assertEquals($output, $model->getTitleAlternative($object, $fieldName, $request));

        $title_alternative = 'Alternative Title';

        \WP_Mock::userFunction('get_post_meta', [
                'args'   => [ $object['id'], '_owc_openpub_titel_alternatief', true ],
                'times'  => '1',
                'return' => $title_alternative
            ]
        );

        $this->assertEquals($output, $model->getTitleAlternative($object, $fieldName, $request));

        $title_alternative = "Alternative\r\nTitle";

        \WP_Mock::userFunction('get_post_meta', [
                'args'   => [ $object['id'], '_owc_openpub_titel_alternatief', true ],
                'times'  => '1',
                'return' => $title_alternative
            ]
        );

        $this->assertEquals($output, $model->getTitleAlternative($object, $fieldName, $request));

        $title_alternative = "Alternative\n\rTitle";

        \WP_Mock::userFunction('get_post_meta', [
                'args'   => [ $object['id'], '_owc_openpub_titel_alternatief', true ],
                'times'  => '1',
                'return' => $title_alternative
            ]
        );

        $this->assertEquals($output, $model->getTitleAlternative($object, $fieldName, $request));
    }
}



