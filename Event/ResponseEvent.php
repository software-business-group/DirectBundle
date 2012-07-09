<?php

namespace Ext\DirectBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Ext\DirectBundle\Response\ResponseInterface;


class ResponseEvent extends Event
{
    private $response;
    protected $data;
    
    public function __construct(ResponseInterface $response, $data)
    {
        $this->response = $response;
        $this->data = $data;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
}
