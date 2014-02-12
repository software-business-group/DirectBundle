<?php

namespace Ext\DirectBundle\Response;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Ext\DirectBundle\Router\ControllerResolver;

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
     * @var EventDispatcher
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
     * @param Request            $request
     * @param ControllerResolver $resolver
     * @param EventDispatcher    $eventDispatcher
     * @param \Twig_Environment  $templating
     */
    public function __construct(
        Request $request,
        ControllerResolver $resolver,
        EventDispatcher $eventDispatcher,
        \Twig_Environment $templating
    )
    {
        $this->request = $request;
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
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
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
