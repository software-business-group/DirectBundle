<?php
namespace Ext\DirectBundle\Api;

use Ext\DirectBundle\Router\RouteCollection;
use Symfony\Component\Routing\Router;

/**
 * Api is the ExtDirect Api class.
 *
 * It provide the ExtDirect Api descriptor of exposed Controllers and methods.
 *
 * @author Otavio Fernandes <otavio@neton.com.br>
 * @author Semyon Velichko <semyon@velichko.net>
 */
class Api
{

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $type;

    /**
     * @var RouteCollection
     */
    private $collection;

    /**
     * @var Router
     */
    private $router;

    const Bundle_Action_Regex = '/^([\w]+)Bundle:([\w]+):([\w]+)$/i';
    const Service_Regex = '/^([\w]+):([\w]+)$/i';

    public function __construct(RouteCollection $collection, Router $router)
    {
        $this->collection = $collection;
        $this->router = $router;
    }

    /**
     * @param mixed $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return \Symfony\Component\Routing\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Return the API in JSON format.
     *
     * @return string JSON API description
     */
    public function  __toString()
    {        
        return sprintf('Ext.ns("%1$s"); %1$s.REMOTING_API = ' . json_encode($this->createApi()), $this->getNamespace());
    }

    /**
     * Create the ExtDirect API based on config.yml or direct.yml files.
     *
     * @return string JSON description of Direct API
     * @return array
     * @throws \InvalidArgumentException
     */
    public function createApi()
    {
        $api = array(
            'type' => $this->getType(),
            'namespace' => $this->getNamespace(),
            'url' => $this->getRouter()->generate('ExtDirectBundle_route')
        );

        $actions = array();
        
        foreach($this->getRouteCollection() as $Rule) {
            if(preg_match($this::Bundle_Action_Regex, $Rule->getController(), $match)) {
                list($all, $shortBundleName, $controllerName, $methodName) = $match;
                $key = sprintf('%s_%s', $shortBundleName, $controllerName);
            } elseif(preg_match($this::Service_Regex, $Rule->getController(), $match)) {
                list($all, $key, $methodName) = $match;
            } else {
                throw new \InvalidArgumentException();
            }

            if(!array_key_exists($key, $actions) or !is_array($actions[$key]))
                $actions[$key] = array();
                
            $methodParams = array('name' => $methodName, 'len' => (integer)$Rule->getIsWithParams());
            
            if($Rule->getIsFormHandler())
                $methodParams['formHandler'] = true;

            $actions[$key][] = $methodParams;
        }

        $api['actions'] = $actions;

        return $api;
    }
}

