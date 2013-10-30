<?php

namespace Ext\DirectBundle\Response;

use Doctrine\ORM\AbstractQuery as ORMAbstractQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Ext\DirectBundle\Event\DirectEvents;
use Ext\DirectBundle\Event\ResponseEvent;

/**
 * @author Semyon Velichko <semyon@velichko.net>
 */
class AbstractQuery extends Response implements ResponseInterface
{
    
    protected $query;
    
    public function setContent($query)
    {
        if(!($query instanceof ORMAbstractQuery))
            throw new \InvalidArgumentException('$query must be instance of AbstractQuery');
        
        $this->query = $query;
        return $this;
    }
    
    public function extract()
    {
        $data = $this->query
            ->setHydrationMode(ORMAbstractQuery::HYDRATE_ARRAY);
            
        $event = new ResponseEvent($this, $data);
        $this->getFactory()->getEventDispatcher()
            ->dispatch(DirectEvents::PRE_QUERY_EXECUTE, $event);
        
        $data = $event->getData();
        if(!is_array($data) && ($data instanceof ORMAbstractQuery))
            $data = $data->execute();
        
        $event = new ResponseEvent($this, $data);
        $this->getFactory()->getEventDispatcher()
            ->dispatch(DirectEvents::POST_QUERY_EXECUTE, $event);
        $data = $event->getData();
        
        if(!is_array($data))
            throw new \InvalidArgumentException('Final result should be an array');
            
        return $this->formatResponse($data);
    }
}
