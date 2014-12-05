<?php

namespace Ext\DirectBundle\Request;

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Ext\DirectBundle\Router\ControllerResolver;
use Ext\DirectBundle\Request\Request as ExtRequest;
use Ext\DirectBundle\Response\Exception as ExceptionResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\Serializer\Serializer;

/**
 * Class RequestDispatcher
 *
 * @package Ext\DirectBundle\Request
 *
 * @author  Otavio Fernandes <otavio@neton.com.br>
 * @author  Semyon Velichko <semyon@velichko.net>
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

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param ControllerResolver $resolver
     * @param boolean            $isKernelDebug
     */
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
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @param Serializer $serializer
     *
     * @return $this
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * @param HttpRequest $request
     *
     * @return string
     */
    public function dispatchHttpRequest(HttpRequest $request = null)
    {
        if (!$this->getHttpRequest() && $request instanceof HttpRequest) {
            $this->setHttpRequest($request);
        }

        $this->setExtRequest(
            new ExtRequest($this->getHttpRequest())
        );

        $batch = array();
        foreach ($this->getExtRequest()->getCalls() as $call) {
            $batch[] = $this->dispatchCall($call);
        }

        return $this->getSerializer()->serialize($batch, 'json');
    }

    /**
     * @param Call $call
     *
     * @return array
     * @throws NotFoundHttpException
     */
    private function dispatchCall(Call $call)
    {
        $controller = $this->getResolver()->getControllerFromCall($call);

        if (!is_callable($controller)) {
            throw new NotFoundHttpException(
                sprintf('Unable to find the controller for action "%s". Maybe you forgot to add the matching route in your routing configuration?', $call->getAction())
            );
        }

        $arguments = $this->getResolver()->getArguments($this->getHttpRequest(), $controller);

        if ($this->isKernelDebug()) {
            try {
                $result = call_user_func_array($controller, $arguments);
            } catch (\Exception $e) {
                $result = new ExceptionResponse($e);
                $call->setType('exception');
            }
        } else {
            $result = call_user_func_array($controller, $arguments);
        }

        return $call->getResponse($result);
    }

}
