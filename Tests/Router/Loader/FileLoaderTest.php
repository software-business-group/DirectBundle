<?php

namespace Ext\DirectBundle\Tests\Router\Loader;

use Ext\DirectBundle\Router\Loader\AnnotationClassLoader;
use Ext\DirectBundle\Router\Loader\AnnotationFileLoader;
use Ext\DirectBundle\Router\Loader\FileLoader;
use Ext\DirectBundle\Router\Loader\YamlLoader;
use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Router\Rule;
use Ext\DirectBundle\Tests\TestTemplate;

class FileLoaderTest extends TestTemplate
{

    /**
     * @var string
     */
    private $resource;

    /**
     * @var RouteCollection
     */
    private $collection;

    /**
     * @var FileLoader
     */
    private $fileLoader;

    /**
     * @var YamlLoader
     */
    private $ymlLoader;

    /**
     * @var
     */
    private $annotationClassLoader;

    /**
     * @var AnnotationFileLoader
     */
    private $annotationFileLoader;

    public function setUp()
    {
        parent::setUp();

        $this->resource = ( __DIR__ . '/routing.yml' );
        $this->collection = new RouteCollection();
        $this->fileLoader = new FileLoader($this->get('file_locator'));
        $this->ymlLoader = new YamlLoader($this->getRouteCollection(), $this->getFileLoader());
        $this->annotationClassLoader = new AnnotationClassLoader(
            $this->getRouteCollection(),
            $this->get('annotation_reader'),
            $this->get('ext_direct.controller_resolver')
        );
        $this->annotationFileLoader = new AnnotationFileLoader($this->getAnnotationClassLoader());

        $this->getFileLoader()->addLoader($this->getYmlLoader());
        $this->getFileLoader()->addLoader($this->getAnnotationFileLoader());

        $this->getYmlLoader()->load($this->getResource());
    }

    /**
     * @return \Ext\DirectBundle\Router\RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->collection;
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\YamlLoader
     */
    public function getYmlLoader()
    {
        return $this->ymlLoader;
    }

    /**
     * @return \Ext\DirectBundle\Router\Loader\FileLoader
     */
    public function getFileLoader()
    {
        return $this->fileLoader;
    }

    /**
     * @return AnnotationFileLoader
     */
    public function getAnnotationFileLoader()
    {
        return $this->annotationFileLoader;
    }

    /**
     * @return AnnotationClassLoader
     */
    public function getAnnotationClassLoader()
    {
        return $this->annotationClassLoader;
    }

    public function testLoadFromRootYaml()
    {
        $collection = $this->getRouteCollection();

        // testArrayResponse
        $this->assertTrue($collection->has('testArrayResponse'));
        $Rule = $collection->get('testArrayResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:ForTesting:testArrayResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testObjectResponse
        $this->assertTrue($collection->has('testObjectResponse'));
        $Rule = $collection->get('testObjectResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:ForTesting:testObjectResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testResponseWithConfiguredReader
        $this->assertTrue($collection->has('testResponseWithConfiguredReader'));
        $Rule = $collection->get('testResponseWithConfiguredReader');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:ForTesting:testResponseWithConfiguredReader'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertEquals('root', $Rule->getReader()['root']);
        $this->assertEquals('successProperty', $Rule->getReader()['successProperty']);
        $this->assertEquals('totalProperty', $Rule->getReader()['totalProperty']);

        // testFormHandlerResponse
        $this->assertTrue($collection->has('testFormHandlerResponse'));
        $Rule = $collection->get('testFormHandlerResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:ForTesting:testFormHandlerResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertEquals('data', $Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testFormValidationResponse
        $this->assertTrue($collection->has('testFormValidationResponse'));
        $Rule = $collection->get('testFormValidationResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:ForTesting:testFormValidationResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testFormEntityValidationResponse
        $this->assertTrue($collection->has('testFormEntityValidationResponse'));
        $Rule = $collection->get('testFormEntityValidationResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:ForTesting:testFormEntityValidationResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testServiceAction
        $this->assertTrue($collection->has('testFormEntityValidationResponse'));
        $Rule = $collection->get('testFormEntityValidationResponse');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:ForTesting:testFormEntityValidationResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testException
        $this->assertTrue($collection->has('testException'));
        $Rule = $collection->get('testException');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:ForTesting:testException'
        );
        $this->assertFalse($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);
    }

    public function testLoadFromExternalYaml()
    {
        $collection = $this->getRouteCollection();

        // testRouteFromExtYaml
        $this->assertTrue($collection->has('testRouteFromExtYaml'));
        $Rule = $collection->get('testRouteFromExtYaml');
        $this->assertEquals(
            $Rule->getController(),
            'ExtDirectBundle:ForTesting:testArrayResponse'
        );
        $this->assertFalse($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);
    }



}