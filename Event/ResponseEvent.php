<?php

namespace Ext\DirectBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Ext\DirectBundle\Response\ResponseInterface;

/**
 * Class ResponseEvent
 *
 * @package Ext\DirectBundle\Event
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class ResponseEvent extends Event
{

    /**
     * @var \Ext\DirectBundle\Response\ResponseInterface
     */
    private $response;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @param ResponseInterface $response
     * @param mixed             $data
     */
    public function __construct(ResponseInterface $response, $data)
    {
        $this->response = $response;
        $this->data = $data;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
