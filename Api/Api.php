<?php
namespace Ext\DirectBundle\Api;

use Ext\DirectBundle\Router\Router;

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
     * @var Router
     */
    private $router;

    const Bundle_Action_Regex = '/^([\w]+)Bundle:([\w]+):([\w]+)$/i';
    const Service_Regex = '/^([\w]+):([\w]+)$/i';

    /**
     * @param mixed $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return mixed
     */
    private function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param mixed $rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * @return mixed
     */
    private function getRules()
    {
        return $this->rules;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    private function getType()
    {
        return $this->type;
    }

    /**
     * Return the API in JSON format.
     *
     * @return string JSON API description
     */
    public function  __toString()
    {        
        return json_encode(array_merge(array(''), $this->createApi()));
    }

    /**
     * Create the ExtDirect API based on config.yml or direct.yml files.
     *
     * @return string JSON description of Direct API
     * @return array
     * @throws \InvalidArgumentException
     */
    private function createApi()
    {
        $api = array();
        
        foreach($this->rules['router']['rules'] as $rule) {
            if(preg_match($this::Bundle_Action_Regex, $rule['defaults']['_controller'], $match)) {
                list($all, $shortBundleName, $controllerName, $methodName) = $match;
                $key = sprintf('%s_%s', $shortBundleName, $controllerName);
            } elseif(preg_match($this::Service_Regex, $rule['defaults']['_controller'], $match)) {
                list($all, $key, $methodName) = $match;
            } else {
                throw new \InvalidArgumentException();
            }

            if(!array_key_exists($key, $api) or !is_array($api[$key]))
                $api[$key] = array();
                
            $methodParams = array('name' => $methodName, 'len' => (integer)$rule['defaults']['params']);
            
            if($rule['defaults']['form'])
                $methodParams['formHandler'] = true;
            
            $api[$key][] = $methodParams;
        }
        
        return $api;
    }
}
