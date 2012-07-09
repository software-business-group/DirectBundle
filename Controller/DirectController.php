<?php

namespace Ext\DirectBundle\Controller;
use Ext\DirectBundle\Api\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpFoundation;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


use Ext\DirectBundle\Tests\Binder as Test;
use Ext\DirectBundle\Router\Router;
use Ext\DirectBundle\Response\Basic;


class DirectController extends Controller
{
    
    private $config = array();
    
    public function __construct(ContainerInterface $container) {
            $this->container = $container;
            $this->response = new HttpFoundation\Response();
            $this->response->headers->set('Content-Type', 'application/json');
    }

    /**
     * Generate the ExtDirect API.
     * 
     * @return HttpFoundation\Response 
     */
    public function getApi()
    {        
        // instantiate the api object
        $api = new Api($this->config);

        $this->response->setContent(sprintf('Ext.ns("%1$s"); %1$s.REMOTING_API = %2$s;', $this->config['basic']['namespace'], $api));
        return $this->response;
    }
    
    /**
     * Route the ExtDirect calls.
     *
     * @param HttpFoundation\Request
     * @return HttpFoundation\Response
     */
    public function route(HttpFoundation\Request $request)
    {
        // instantiate the router object
        $router = new Router($this->container);
        $this->response->setContent($router->route());
        return $this->response;
    }
    
    public function setConfig($config) {
        $this->config = array_merge_recursive($config, array('basic' => array('url' => $this->get('router')->generate('ExtDirectBundle_route'))));
    }
}
