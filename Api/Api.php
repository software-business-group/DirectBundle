<?php
namespace Ext\DirectBundle\Api;

/**
 * Api is the ExtDirect Api class.
 *
 * It provide the ExtDirect Api descriptor of exposed Controllers and methods.
 *
 * @author Otavio Fernandes <otavio@neton.com.br>
 */
class Api
{

    /**
     * The ExtDirect JSON API description.
     * 
     * @var array
     */
    protected $api = array('actions' => array());
    
    protected $config;

    /**
     * Initialize the API.
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->createApi();
    }

    /**
     * Return the API in JSON format.
     *
     * @return string JSON API description
     */
    public function  __toString()
    {        
        return json_encode(array_merge($this->config['basic'], $this->api));
    }

    /**
     * Create the ExtDirect API based on config.yml or direct.yml files.
     *
     * @return string JSON description of Direct API
     */
    protected function createApi()
    {
        $api = array();
        
        foreach($this->config['router']['rules'] as $rule) {
            if(!preg_match('/^([\w]+)Bundle:([\w]+):([\w]+)$/', $rule['defaults']['_controller'], $match)) {
                throw new \InvalidArgumentException();
            }
            list($all, $shortBundleName, $controllerName, $methodName) = $match;
            
            $key = sprintf('%s_%s', $shortBundleName, $controllerName);
            
            if(!array_key_exists($key, $api) or !is_array($api[$key]))
                $api[$key] = array();
                
            $methodParams = array('name' => $methodName, 'len' => (integer)$rule['defaults']['params']);
            
            if($rule['defaults']['form'])
                $methodParams['formHandler'] = true;
            
            $api[$key][] = $methodParams;
        }
        
        $this->api['actions'] = $api;
    }
}
