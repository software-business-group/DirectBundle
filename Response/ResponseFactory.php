<?php

namespace Ext\DirectBundle\Response;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Ext\DirectBundle\Router\ControllerResolver;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ResponseFactory
 *
 * @package Ext\DirectBundle\Response
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class ResponseFactory
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var ControllerResolver
     */
    private $resolver;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var \Symfony\Bundle\TwigBundle\TwigEngine
     */
    private $templating;

    /**
     * @var string
     */
    private $errorTemplate;

    /**
     * @param RequestStack             $requestStack
     * @param ControllerResolver       $resolver
     * @param EventDispatcherInterface $eventDispatcher
     * @param \Twig_Environment        $templating
     */
    public function __construct(
        RequestStack $requestStack,
        ControllerResolver $resolver,
        EventDispatcherInterface $eventDispatcher,
        \Twig_Environment $templating
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->resolver = $resolver;
        $this->eventDispatcher = $eventDispatcher;
        $this->templating = $templating;
    }

    /**
     * @param ResponseInterface $response
     * @param mixed             $data
     *
     * @return ResponseInterface
     */
    public function createResponse(ResponseInterface $response, $data = null)
    {
        $this->response = $response;
        $this->response->setFactory($this);

        if ($data !== null) {
            $this->response->setContent($data);
        }

        return $this->response;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return \Ext\DirectBundle\Router\ControllerResolver
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return \Twig_Environment
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * @param string $errorTemplate
     */
    public function setErrorTemplate($errorTemplate)
    {
        $this->errorTemplate = $errorTemplate;
    }

    /**
     * @return string
     */
    public function getErrorTemplate()
    {
        return $this->errorTemplate;
    }

}
