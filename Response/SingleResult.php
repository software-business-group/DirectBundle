<?php

namespace Ext\DirectBundle\Response;

use Doctrine\ORM\AbstractQuery as ORMAbstractQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Ext\DirectBundle\Event\DirectEvents;
use Ext\DirectBundle\Event\ResponseEvent;
use Ext\DirectBundle\Event\GetSingleResultSubscriber;

/**
 * @author Semyon Velichko <semyon@velichko.net>
 */
class SingleResult extends AbstractQuery implements ResponseInterface
{
    
    public function setContent($query)
    {
        parent::setContent($query);
        $this->addEventSubscriber(new GetSingleResultSubscriber());
    }
    
}
