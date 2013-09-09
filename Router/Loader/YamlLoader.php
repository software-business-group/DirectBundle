<?php

namespace Ext\DirectBundle\Router\Loader;
use Ext\DirectBundle\Router\Router;
use Ext\DirectBundle\Router\Rule;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * Class YamlLoader
 * @package Ext\DirectBundle\Router\Loader
 * @author Semyon Velichko <semyon@velichko.net>
 */
class YamlLoader implements LoaderInterface
{

    /**
     * @var \Ext\DirectBundle\Router\Router
     */
    private $router;

    /**
     * @var \Symfony\Component\Yaml\Parser
     */
    private $parser;

    public function __construct(Router $router)
    {
        $this->parser = new YamlParser();
        $this->router = $router;
    }

    /**
     * @return YamlParser
     */
    private function getParser()
    {
        return $this->parser;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    public function load($resource)
    {
        $content = $this->loadFile($resource);

        if(!is_array($content))
            return array();

        foreach($content as $key => $params)
            $this->processParams($key, $params);
    }

    /**
     * @param $key
     * @param array $params
     * @throws \InvalidArgumentException
     */
    private function processParams($key, array $params)
    {
        if(!array_key_exists('defaults', $params))
            throw new \InvalidArgumentException('The defaults params not defined');

        $Rule = $this->processDefaults($key, $params);

        if(isset($params['reader']))
            $this->processReader($Rule, $params['reader']);

        if(isset($params['writer']))
            $this->processWriter($Rule, $params['writer']);

        $this->getRouter()
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

        $defaults['controller'] = $params['_controller'];

        if(isset($params['params']))
            $isWithParams = $params['params'];

        if(isset($params['form']))
            $isFormHandler = $params['form'];

        return new Rule($key, $defaults, $isWithParams, $isFormHandler);
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
            throw new \InvalidArgumentException('Resource does not exist');

        return $this->getParser()->parse(
            file_get_contents($resource)
        );
    }

    public function supports($resource, $type = null)
    {
        if($type === 'yml' || preg_match('/\.yml$/', $resource))
            return true;

        return false;
    }

}