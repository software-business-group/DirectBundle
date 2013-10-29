<?php

namespace Ext\DirectBundle\Router\Loader;
use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Router\Rule;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * Class YamlLoader
 * @package Ext\DirectBundle\Router\Loader
 * @author Semyon Velichko <semyon@velichko.net>
 */
class YamlLoader extends AbstractLoader
{

    /**
     * @var \Ext\DirectBundle\Router\RouteCollection
     */
    private $collection;

    /**
     * @var \Symfony\Component\Yaml\Parser
     */
    private $parser;

    public function __construct(RouteCollection $collection)
    {
        $this->parser = new YamlParser();
        $this->collection = $collection;
    }

    /**
     * @return YamlParser
     */
    private function getParser()
    {
        return $this->parser;
    }

    /**
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->collection;
    }

    /**
     * @param $resource
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function load($resource)
    {
        if(is_string($resource))
            $resource = $this->loadFile($resource);

        if(!is_array($resource))
            throw new \InvalidArgumentException;

        foreach($resource as $key => $params)
            $this->processParams($key, $params);

        return true;
    }

    /**
     * @param $key
     * @param array $params
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function processParams($key, array $params)
    {
        if(isset($params['resource']))
        {
            return $this->getFileLoader()
                ->load($params['resource'], (isset($params['type'])?$params['type']:null));
        }

        if(!isset($params['defaults']))
            throw new \InvalidArgumentException('The defaults params not defined');

        $Rule = $this->processDefaults($key, $params['defaults']);

        if(isset($params['reader']))
            $this->processReader($Rule, $params['reader']);

        if(isset($params['writer']))
            $this->processWriter($Rule, $params['writer']);

        $this->getRouteCollection()
            ->add($Rule);
    }

    /**
     * @param $key
     * @param array $params
     * @return Rule
     * @throws \InvalidArgumentException
     */
    private function processDefaults($key, array $params)
    {
        $isWithParams = null;
        $isFormHandler = null;

        if(!isset($params['_controller']))
            throw new \InvalidArgumentException('The _controller does not defined');

        if(isset($params['params']))
            $isWithParams = $params['params'];

        if(isset($params['form']))
            $isFormHandler = $params['form'];

        return new Rule($key, $params['_controller'], $isWithParams, $isFormHandler);
    }

    /**
     * @param Rule $Rule
     * @param array $params
     */
    private function processReader(Rule $Rule, array $params)
    {
        if(array_key_exists('root', $params))
            $Rule->setReaderRoot($params['root']);

        if(array_key_exists('type', $params))
            $Rule->setReaderParam('type', $params['type']);

        if(array_key_exists('successProperty', $params))
            $Rule->setReaderSuccessProperty($params['successProperty']);

        if(array_key_exists('totalProperty', $params))
            $Rule->setReaderTotalProperty($params['totalProperty']);
    }

    /**
     * @param Rule $Rule
     * @param array $params
     */
    private function processWriter(Rule $Rule, array $params)
    {
        if(array_key_exists('root', $params))
            $Rule->setWriterParam('root', $params['root']);

        if(array_key_exists('type', $params['type']))
            $Rule->setWriterParam('type', $params['type']);
    }

    /**
     * @param $resource
     * @return mixed
     * @throws \InvalidArgumentException
     */
    private function loadFile($resource)
    {
        if(!file_exists($resource))
        {
            throw new \InvalidArgumentException('Resource does not exist');
        }

        return $this->getParser()->parse(
            file_get_contents($resource)
        );
    }

    public function supports($resource, $type = null)
    {
        if($type === 'yml' || $type === 'yaml' || preg_match('/\.yml$/', $resource))
            return true;

        return false;
    }

}