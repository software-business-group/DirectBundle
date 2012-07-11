<?php

namespace Ext\DirectBundle\Response;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Semyon Velichko <semyon@velichko.net>
 */
interface ResponseInterface
{
    
    public function setFactory(ResponseFactory $factory);
    
    public function setContent($data);
    
    public function extract();
    
    public function setTotal($total);
    
    public function setSuccess($success);
    
    public function addEventSubscriber(EventSubscriberInterface $subscriber);
    
}
