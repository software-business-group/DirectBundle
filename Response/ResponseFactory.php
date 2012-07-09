<?php

namespace Ext\DirectBundle\Response;

use Symfony\Component\HttpFoundation\Request;
use Ext\DirectBundle\Router\ControllerResolver;

class ResponseFactory
{
    
    protected $request;
    protected $response;
    protected $dispatcher;
    protected $config;
    protected $data;
    protected $resolver;
    
    public function __construct(Request $request, ControllerResolver $resolver)
    {
        $this->request = $request;
        $this->resolver = $resolver;
    }
    
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }
    
    public function getConfig()
    {
        return $this->config;
    }
    
    public function createResponse(ResponseInterface $response, $data = null)
    {
        $this->response = $response;
        $this->response->setFactory($this);
        if($data)
            $this->response->setContent($data);
        return $this->response;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function getResolver()
    {
        return $this->resolver;
    }
    
}
