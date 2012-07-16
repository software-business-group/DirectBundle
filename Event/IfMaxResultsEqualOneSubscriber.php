<?php

namespace Ext\DirectBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\AbstractQuery as ORMAbstractQuery;

/**
 * @author Semyon Velichko <semyon@velichko.net>
 */
class IfMaxResultsEqualOneSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
         return array(DirectEvents::PRE_QUERY_EXECUTE => 'callbackFunction');
    }
    
    public function callbackFunction(ResponseEvent $event)
    {
        $data = $event->getData();
        
        if(1 === $data->getMaxResults())
            $data->setHydrationMode(ORMAbstractQuery::HYDRATE_SINGLE_SCALAR);
        
        $event->setData($data);
    }
    
}
