<?php

namespace Ext\DirectBundle\Request;

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Ext\DirectBundle\Router\ControllerResolver;
use Ext\DirectBundle\Request\Request as ExtRequest;
use Ext\DirectBundle\Response\Exception as ExceptionResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RequestDispatcher
 * @package Ext\DirectBundle\Request
 * @author Otavio Fernandes <otavio@neton.com.br>
 * @author Semyon Velichko <semyon@velichko.net>
 */
class RequestDispatcher
{

    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * @var ExtRequest
     */
    private $extRequest;

    /**
     * @var ControllerResolver
     */
    private $resolver;

    /**
     * @var boolean
     */
    private $isKernelDebug;

    public function __construct(ControllerResolver $resolver, $isKernelDebug)
    {
        $this->resolver = $resolver;
        $this->isKernelDebug = $isKernelDebug;
    }

    /**
     * @return bool
     */
    private function isKernelDebug()
    {
        return $this->isKernelDebug;
    }

    /**
     * @param \Ext\DirectBundle\Request\Request $extRequest
     */
    private function setExtRequest($extRequest)
    {
        $this->extRequest = $extRequest;
    }

    /**
     * @return \Ext\DirectBundle\Request\Request
     */
    private function getExtRequest()
    {
        return $this->extRequest;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     */
    public function setHttpRequest($httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getHttpRequest()
    {
        return $this->httpRequest;
    }

    /**
     * @return \Ext\DirectBundle\Router\ControllerResolver
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param HttpRequest $request
     * @return string
     */
    public function dispatchHttpRequest(HttpRequest $request = null)
    {
        if(!$this->getHttpRequest() && $request instanceof HttpRequest)
            $this->setHttpRequest($request);

        $this->setExtRequest(
            new ExtRequest($this->getHttpRequest())
        );

        $batch = array();
        foreach ($this->getExtRequest()->getCalls() as $Call) {
            $batch[] = $this->dispatchCall($Call);
        }
        return json_encode($batch);
    }

    /**
     * @param Call $Call
     * @return array
     * @throws NotFoundHttpException
     */
    private function dispatchCall(Call $Call)
    {
        $controller = $this->getResolver()->getControllerFromCall($Call);

        if (!is_callable($controller)) {
            throw new NotFoundHttpException('Unable to find the controller for action "%s". Maybe you forgot to add the matching route in your routing configuration?', $Call->getAction());
        }

        $arguments = $this->getResolver()->getArguments($this->getHttpRequest(), $controller);

        if($this->isKernelDebug())
        {
            try {
                $result = call_user_func_array($controller, $arguments);
            } catch (\Exception $e)
            {
                $result = new ExceptionResponse($e);
                $Call->setType('exception');
            }
        } else {
            $result = call_user_func_array($controller, $arguments);
        }

        return $Call->getResponse($result);
    }

} 