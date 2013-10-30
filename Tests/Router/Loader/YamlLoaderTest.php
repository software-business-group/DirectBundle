<?php

namespace Ext\DirectBundle\Tests\Router\Loader;
use Ext\DirectBundle\Tests\TestTemplate;
use Ext\DirectBundle\Router\Loader\YamlLoader;
use Ext\DirectBundle\Router\Loader\FileLoader;
use Ext\DirectBundle\Router\RouteCollection;

/**
 * Class YamlLoaderTest
 * @package Ext\DirectBundle\Tests\Router\Loader
 * @author Semyon Velichko <semyon@velichko.net>
 */
class YamlLoaderTest extends TestTemplate
{

    /**
     * @var string
     */
    private $resource;

    /**
     * @var
     */
    private $fileLoader;

    /**
     * @var YamlLoader
     */
    private $yamlLoader;

    /**
     * @var RouteCollection
     */
    private $collection;

    public function setUp()
    {
        parent::setUp();

        $this->collection = new RouteCollection();
        $this->fileLoader = new FileLoader($this->get('file_locator'), $this->get('ext_direct.router.cache'));
        $this->yamlLoader = new YamlLoader($this->getRouteCollection(), $this->getFileLoader());
        $this->getFileLoader()->addLoader($this->getYamlLoader());
        $this->getYamlLoader()->load( __DIR__ . '/routing.yml' );
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return \Ext\DirectBundle\Router\RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->collection;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\YamlLoader
     */
    public function getYamlLoader()
    {
        return $this->yamlLoader;
    }

    /**
     * @return FileLoader
     */
    public function getFileLoader()
    {
        return $this->fileLoader;
    }

    public function testArrayResponse()
    {
        $collection = $this->getRouteCollection();

        // testArrayResponse
        $this->assertTrue($collection->has('testArrayResponse'));
        $Rule = $collection->get('testArrayResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:Test:testArrayResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReaderParam('type'));
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('total', $Rule->getReaderParam('totalProperty'));
    }

    public function testObjectResponse() {
        $collection = $this->getRouteCollection();

        // testObjectResponse
        $this->assertTrue($collection->has('testObjectResponse'));
        $Rule = $collection->get('testObjectResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:Test:testObjectResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReaderParam('type'));
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('total', $Rule->getReaderParam('totalProperty'));
    }

    public function testResponseWithConfiguredReader()
    {
        $collection = $this->getRouteCollection();

        // testResponseWithConfiguredReader
        $this->assertTrue($collection->has('testResponseWithConfiguredReader'));
        $Rule = $collection->get('testResponseWithConfiguredReader');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:Test:testResponseWithConfiguredReader'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReaderParam('type'));
        $this->assertEquals('root', $Rule->getReaderParam('root'));
        $this->assertEquals('successProperty', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('totalProperty', $Rule->getReaderParam('totalProperty'));
    }

    public function testFormHandlerResponse()
    {
        $collection = $this->getRouteCollection();

        // testFormHandlerResponse
        $this->assertTrue($collection->has('testFormHandlerResponse'));
        $Rule = $collection->get('testFormHandlerResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:Test:testFormHandlerResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReaderParam('type'));
        $this->assertEquals('data', $Rule->getReaderParam('root'));
        $this->assertEquals('success', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('total', $Rule->getReaderParam('totalProperty'));
    }

    public function testFormValidationResponse()
    {
        $collection = $this->getRouteCollection();

        // testFormValidationResponse
        $this->assertTrue($collection->has('testFormValidationResponse'));
        $Rule = $collection->get('testFormValidationResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:Test:testFormValidationResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReaderParam('type'));
        $this->assertNull($Rule->getReaderParam('root'));
        $this->assertEquals('success', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('total', $Rule->getReaderParam('totalProperty'));
    }

    public function testFormEntityValidationResponse()
    {
        $collection = $this->getRouteCollection();

        // testFormEntityValidationResponse
        $this->assertTrue($collection->has('testFormEntityValidationResponse'));
        $Rule = $collection->get('testFormEntityValidationResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:Test:testFormEntityValidationResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReaderParam('type'));
        $this->assertNull($Rule->getReaderParam('root'));
        $this->assertEquals('success', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('total', $Rule->getReaderParam('totalProperty'));
    }

    public function testServiceAction()
    {
        $collection = $this->getRouteCollection();

        // testServiceAction
        $this->assertTrue($collection->has('testFormEntityValidationResponse'));
        $Rule = $collection->get('testFormEntityValidationResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:Test:testFormEntityValidationResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReaderParam('type'));
        $this->assertNull($Rule->getReaderParam('root'));
        $this->assertEquals('success', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('total', $Rule->getReaderParam('totalProperty'));
    }

    public function testException()
    {
        $collection = $this->getRouteCollection();

        // testException
        $this->assertTrue($collection->has('testException'));
        $Rule = $collection->get('testException');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:Test:testException'
        );
        $this->assertFalse($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReaderParam('type'));
        $this->assertNull($Rule->getReaderParam('root'));
        $this->assertEquals('success', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('total', $Rule->getReaderParam('totalProperty'));
    }

    public function testLoadFromExternalYaml()
    {
        $collection = $this->getRouteCollection();

        // testRouteFromExtYaml
        $this->assertTrue($collection->has('testRouteFromExtYaml'));
        $Rule = $collection->get('testRouteFromExtYaml');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:Test:testArrayResponse'
        );
        $this->assertFalse($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReaderParam('type'));
        $this->assertNull($Rule->getReaderParam('root'));
        $this->assertEquals('success', $Rule->getReaderParam('successProperty'));
        $this->assertEquals('total', $Rule->getReaderParam('totalProperty'));
    }

}
