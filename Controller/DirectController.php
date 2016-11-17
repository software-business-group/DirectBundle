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
 * Class DirectController
 *
 * @package Ext\DirectBundle\Controller
 *
 * @author  Otavio Fernandes <otavio@neton.com.br>
 * @author  Semyon Velichko  <semyon@velichko.net>
 */
class DirectController extends Controller
{

    /**
     * Generate the ExtDirect API.
     * 
     * @return HttpFoundation\Response 
     */
    public function getApiAction()
    {
        $this->get('ext_direct.file.loader');
        return new HttpFoundation\Response(
            (string) $this->get('ext_direct.api'),
            200,
            array('Content-Type' => 'text/javascript')
        );
    }

    /**
     * Route the ExtDirect calls.
     *
     * @param HttpFoundation\Request $request
     *
     * @return HttpFoundation\Response
     */
    public function routeAction(HttpFoundation\Request $request)
    {

        foreach ($request->request as $k => $v) {

                $request->request->set($k, filter_var($v, FILTER_SANITIZE_STRING));

        }
        $this->get('ext_direct.file.loader');
        return new HttpFoundation\Response(
            (string) $this->get('ext_direct.request_dispatcher')->dispatchHttpRequest($request),
            200,
            array('Content-Type' => 'text/html')
        );
    }

}
