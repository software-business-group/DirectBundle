<?php

namespace Ext\DirectBundle\Controller;
use Ext\DirectBundle\Api\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpFoundation;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


use Ext\DirectBundle\Tests\Binder as Test;
use Ext\DirectBundle\Router\RouterDepricated;
use Ext\DirectBundle\Response\Basic;

/**
 * @author Semyon Velichko <semyon@velichko.net>
 */
class DirectController extends Controller
{
    
    private $config = array();
    
    public function __construct(ContainerInterface $container) {
            $this->container = $container;
            $this->response = new HttpFoundation\Response();
            $this->response->headers->set('Content-Type', 'text/html');
    }

    /**
     * Generate the ExtDirect API.
     * 
     * @return HttpFoundation\Response 
     */
    public function getApiAction()
    {        
        // instantiate the api object
        $api = new Api($this->config);

        $this->response->setContent(sprintf('Ext.ns("%1$s"); %1$s.REMOTING_API = %2$s;', $this->config['basic']['namespace'], $api));
        $this->response->headers->set('Content-Type', 'text/javascript');
        return $this->response;
    }

    /**
     * Route the ExtDirect calls.
     *
     * @param HttpFoundation\Request $request
     * @return HttpFoundation\Response
     */
    public function routeAction(HttpFoundation\Request $request)
    {
        // instantiate the router object
        $router = new RouterDepricated($this->container);
        $this->response->setContent($router->route());
        return $this->response;
    }
    
    public function setConfig($config) {
        $this->config = array_merge_recursive($config, array('basic' => array('url' => $this->get('router')->generate('ExtDirectBundle_route'))));
    }
    
    public function getConfig()
    {
        return $this->config;
    }
}
