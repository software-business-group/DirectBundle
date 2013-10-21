<?php

namespace Ext\DirectBundle\Tests\Router\Loader;

use Ext\DirectBundle\Router\Loader\AnnotationClassLoader;
use Ext\DirectBundle\Router\Loader\AnnotationFileLoader;
use Ext\DirectBundle\Router\Loader\FileLoader;
use Ext\DirectBundle\Router\Loader\YamlLoader;
use Ext\DirectBundle\Router\Router;
use Ext\DirectBundle\Router\Rule;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileLoaderTest extends WebTestCase
{

    /**
     * @var
     */
    static $kernel;

    /**
     * @var string
     */
    private $resource;

    /**
     * @var Router
     */
    private $router;

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

    /**
     * @var array
     */
    private $rules = array();

    protected function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->resource = ( __DIR__ . '/routing.yml' );
        $this->router = new Router();
        $this->fileLoader = new FileLoader($this->getRouter(), $this->get('file_locator'));
        $this->ymlLoader = new YamlLoader($this->getRouter(), $this->getFileLoader());
        $this->annotationClassLoader = new AnnotationClassLoader(
            $this->getRouter(),
            $this->get('annotation_reader'),
            $this->get('ext_direct.controller_resolver')
        );
        $this->annotationFileLoader = new AnnotationFileLoader($this->get('file_locator'), $this->getAnnotationClassLoader());

        $this->getFileLoader()->addLoader($this->getYmlLoader());
        $this->getFileLoader()->addLoader($this->getAnnotationFileLoader());

        $this->getYmlLoader()->load($this->getResource());
        $this->rules = $this->getRouter()->all();
    }

    /**
     * @param $serviceId
     * @return mixed
     */
    public function get($serviceId)
    {
        return static::$kernel->getContainer()->get($serviceId);
    }

    /**
     * @return \Ext\DirectBundle\Router\Router
     */
    public function getRouter()
    {
        return $this->router;
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

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param $key
     * @return Rule
     * @throws \InvalidArgumentException
     */
    private function getRuleByKey($key)
    {
        if(!isset($this->getRules()[$key]))
            throw new \InvalidArgumentException();

        return $this->getRules()[$key];
    }

    public function testLoadFromRootYaml()
    {
        $rules = $this->getRules();

        // testArrayResponse
        $this->assertArrayHasKey('testArrayResponse', $rules);
        $Rule = $this->getRuleByKey('testArrayResponse');
        $this->assertEquals(
            $Rule->getDefaults()['controller'],
            'ExtDirectBundle:ForTesting:testArrayResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testObjectResponse
        $this->assertArrayHasKey('testObjectResponse', $rules);
        $Rule = $this->getRuleByKey('testObjectResponse');
        $this->assertEquals(
            $Rule->getDefaults()['controller'],
            'ExtDirectBundle:ForTesting:testObjectResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testResponseWithConfiguredReader
        $this->assertArrayHasKey('testResponseWithConfiguredReader', $rules);
        $Rule = $this->getRuleByKey('testResponseWithConfiguredReader');
        $this->assertEquals(
            $Rule->getDefaults()['controller'],
            'ExtDirectBundle:ForTesting:testResponseWithConfiguredReader'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertFalse($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertEquals('root', $Rule->getReader()['root']);
        $this->assertEquals('successProperty', $Rule->getReader()['successProperty']);
        $this->assertEquals('totalProperty', $Rule->getReader()['totalProperty']);

        // testFormHandlerResponse
        $this->assertArrayHasKey('testFormHandlerResponse', $rules);
        $Rule = $this->getRuleByKey('testFormHandlerResponse');
        $this->assertEquals(
            $Rule->getDefaults()['controller'],
            'ExtDirectBundle:ForTesting:testFormHandlerResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertEquals('data', $Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testFormValidationResponse
        $this->assertArrayHasKey('testFormValidationResponse', $rules);
        $Rule = $this->getRuleByKey('testFormValidationResponse');
        $this->assertEquals(
            $Rule->getDefaults()['controller'],
            'ExtDirectBundle:ForTesting:testFormValidationResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testFormEntityValidationResponse
        $this->assertArrayHasKey('testFormEntityValidationResponse', $rules);
        $Rule = $this->getRuleByKey('testFormEntityValidationResponse');
        $this->assertEquals(
            $Rule->getDefaults()['controller'],
            'ExtDirectBundle:ForTesting:testFormEntityValidationResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testServiceAction
        $this->assertArrayHasKey('testFormEntityValidationResponse', $rules);
        $Rule = $this->getRuleByKey('testFormEntityValidationResponse');
        $this->assertEquals(
            $Rule->getDefaults()['controller'],
            'ExtDirectBundle:ForTesting:testFormEntityValidationResponse'
        );
        $this->assertTrue($Rule->getIsWithParams());
        $this->assertTrue($Rule->getIsFormHandler());
        $this->assertEquals('json', $Rule->getReader()['type']);
        $this->assertNull($Rule->getReader()['root']);
        $this->assertEquals('success', $Rule->getReader()['successProperty']);
        $this->assertEquals('total', $Rule->getReader()['totalProperty']);

        // testException
        $this->assertArrayHasKey('testException', $rules);
        $Rule = $this->getRuleByKey('testException');
        $this->assertEquals(
            $Rule->getDefaults()['controller'],
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
        // testRouteFromExtYaml
        $this->assertArrayHasKey('testRouteFromExtYaml', $this->getRules());
        $Rule = $this->getRuleByKey('testRouteFromExtYaml');
        $this->assertEquals(
            $Rule->getDefaults()['controller'],
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