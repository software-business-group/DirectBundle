<?php

namespace Ext\DirectBundle\Response;

use Doctrine\ORM\AbstractQuery as ORMAbstractQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Ext\DirectBundle\Event\DirectEvents;
use Ext\DirectBundle\Event\ResponseEvent;

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
            ->setHydrationMode(ORMAbstractQuery::HYDRATE_ARRAY)
            ->execute();
        
        $event = new ResponseEvent($this, $data);
        $this->dispatcher->dispatch(DirectEvents::POST_QUERY_EXECUTE, $event);
        $data = $event->getData();
        return $this->formatResponse($data);
    }
}
