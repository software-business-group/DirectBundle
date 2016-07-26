<?php

namespace Ext\DirectBundle\Request;

use Ext\DirectBundle\Event\DirectEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Ext\DirectBundle\Router\ControllerResolver;
use Ext\DirectBundle\Request\Request as ExtRequest;
use Ext\DirectBundle\Response\Exception as ExceptionResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param SerializationContext $serializationContext
     */
    private $serializationContext;

    /**
     * @var HttpKernelInterface
     */
    private $httpKernel;

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
     * @return SerializationContext
     */
    public function getSerializationContext()
    {
        return $this->serializationContext;
    }

    /**
     * @param SerializationContext $serializationContext
     *
     * @return $this
     */
    public function setSerializationContext(SerializationContext $serializationContext)
    {
        $this->serializationContext = $serializationContext;

        return $this;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return $this;
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    /**
     * @param HttpKernelInterface $httpKernel
     *
     * @return $this;
     */
    public function setHttpKernel($httpKernel)
    {
        $this->httpKernel = $httpKernel;

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

        return $this->getSerializer()->serialize($batch, 'json', $this->getSerializationContext());
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

        $event = new FilterControllerEvent($this->httpKernel, $controller, $this->getHttpRequest(), HttpKernelInterface::SUB_REQUEST);
        $this->eventDispatcher->dispatch(DirectEvents::CONTROLLER, $event);
        $controller = $event->getController();
        $this->httpRequest = $event->getRequest();

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
