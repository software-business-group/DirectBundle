<?php

namespace Ext\DirectBundle\Router\Loader;

use Ext\DirectBundle\Router\RouteCollection;
use Ext\DirectBundle\Router\Rule;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * Class YamlLoader
 *
 * @package Ext\DirectBundle\Router\Loader
 *
 * @author  Semyon Velichko <semyon@velichko.net>
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

    /**
     * @param RouteCollection $collection
     */
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
     * @param mixed $resource
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function load($resource)
    {
        if (is_string($resource)) {
            $resource = $this->loadFile($resource);
        }

        if (!is_array($resource)) {
            throw new \InvalidArgumentException;
        }

        foreach ($resource as $key => $params) {
            $this->processParams($key, $params);
        }

        return true;
    }

    /**
     * @param string $key
     * @param array  $params
     *
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function processParams($key, array $params)
    {
        if (isset($params['resource'])) {
            $this->getFileLoader()
                ->load($params['resource'], (isset($params['type'])?$params['type']:null));

            return;
        }

        if (!isset($params['defaults'])) {
            throw new \InvalidArgumentException('The defaults params not defined');
        }

        $rule = $this->processDefaults($key, $params['defaults']);

        if (isset($params['reader'])) {
            $this->processReader($rule, $params['reader']);
        }

        if (isset($params['writer'])) {
            $this->processWriter($rule, $params['writer']);
        }

        $this->getRouteCollection()
            ->add($rule);
    }

    /**
     * @param string $key
     * @param array  $params
     *
     * @return Rule
     * @throws \InvalidArgumentException
     */
    private function processDefaults($key, array $params)
    {
        $isWithParams = null;
        $isFormHandler = null;

        if (!isset($params['_controller'])) {
            throw new \InvalidArgumentException('The _controller does not defined');
        }

        if (isset($params['params'])) {
            $isWithParams = $params['params'];
        }

        if (isset($params['form'])) {
            $isFormHandler = $params['form'];
        }

        return new Rule($key, $params['_controller'], $isWithParams, $isFormHandler);
    }

    /**
     * @param Rule  $rule
     * @param array $params
     */
    private function processReader(Rule $rule, array $params)
    {
        if (array_key_exists('root', $params)) {
            $rule->setReaderRoot($params['root']);
        }

        if (array_key_exists('type', $params)) {
            $rule->setReaderParam('type', $params['type']);
        }

        if (array_key_exists('successProperty', $params)) {
            $rule->setReaderSuccessProperty($params['successProperty']);
        }

        if (array_key_exists('totalProperty', $params)) {
            $rule->setReaderTotalProperty($params['totalProperty']);
        }
    }

    /**
     * @param Rule   $rule
     * @param array  $params
     */
    private function processWriter(Rule $rule, array $params)
    {
        if (array_key_exists('root', $params)) {
            $rule->setWriterParam('root', $params['root']);
        }

        if (array_key_exists('type', $params['type'])) {
            $rule->setWriterParam('type', $params['type']);
        }
    }

    /**
     * @param mixed $resource
     *
     * @return array|bool|float|int|mixed|null|number|string
     * @throws \InvalidArgumentException
     */
    private function loadFile($resource)
    {
        if (!file_exists($resource)) {
            throw new \InvalidArgumentException('Resource does not exist');
        }

        return $this->getParser()->parse(
            file_get_contents($resource)
        );
    }

    /**
     * @param mixed $resource
     * @param null  $type
     *
     * @return bool|mixed
     */
    public function supports($resource, $type = null)
    {
        if ($type === 'yml' || $type === 'yaml' || preg_match('/\.yml$/', $resource)) {
            return true;
        }

        return false;
    }
}
