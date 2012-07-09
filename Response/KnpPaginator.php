<?php

namespace Ext\DirectBundle\Response;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Ext\DirectBundle\Event\DirectEvents;
use Ext\DirectBundle\Event\ResponseEvent;

class KnpPaginator extends Response implements ResponseInterface
{
    
    public function setContent($paginator)
    {
        if(!($paginator instanceof SlidingPagination))
            throw new \InvalidArgumentException('$paginator must be instance of Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination');
        
        $this->data = $paginator->getItems();
        $this->setTotal($paginator->getTotalItemCount());
        return $this;
    }

    public function extract()
    {
        $event = new ResponseEvent($this, $this->data);
        $this->dispatcher->dispatch(DirectEvents::POST_QUERY_EXECUTE, $event);
        $data = $event->getData();
        return $this->formatResponse($data);
    }
    
}

