<?php

namespace Ext\DirectBundle\Response;

use Doctrine\ORM\AbstractQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Ext\DirectBundle\Event\DirectEvents;
use Ext\DirectBundle\Event\ResponseEvent;

/**
 * @author Semyon Velichko <semyon@velichko.net>
 */
class Response implements ResponseInterface
{
    
    protected $config;
    protected $dispatcher;
    protected $factroy;
    
    protected $data = array();
    protected $success;
    protected $total;
    
    public function setFactory(ResponseFactory $factory)
    {
        $this->factory = $factory;
        $this->config = $factory->getConfig();
        $this->dispatcher = new EventDispatcher();
        return $this;
    }
    
    public function setContent($data)
    {
        $this->data = $data;
        return $this;
    }
    
    public function extract()
    {
        return $this->formatResponse($this->data);
    }
    
    public function formatResponse(array $root)
    {
        $data = array();
        
        $config = $this->factory->getResolver()->getMethodConfig();
        $config = $config['reader'];
        
        if($config['root'])
        {
            $data[$config['root']] = $root;
        } else {
            $data = $root;
        }
        
        if(is_bool($this->success))
            $data[$config['successProperty']] = $this->success;
        
        if($this->total)
            $data[$config['totalProperty']] = $this->total;
        
        return $data;
    }
    
    public function setSuccess($success)
    {
        $this->success = (bool)$success;
        return $this;
    }
    
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }
    
    public function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->dispatcher->addSubscriber($subscriber);
        return $this;
    }

    /**
     * @param $name
     * @param $listener
     * @param int $weight
     * @return $this
     */
    public function addEventListener($name, $listener, $weight = 0)
    {
        $this->dispatcher->addListener($name, $listener, $weight);
        return $this;
    }
}
