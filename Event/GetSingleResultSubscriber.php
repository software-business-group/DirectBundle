<?php

namespace Ext\DirectBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\AbstractQuery as ORMAbstractQuery;

/**
 * @author Semyon Velichko <semyon@velichko.net>
 */
class GetSingleResultSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
         return array(DirectEvents::PRE_QUERY_EXECUTE => 'callbackFunction');
    }
    
    public function callbackFunction(ResponseEvent $event)
    {
        $data = $event->getData();
        
        $data = $data->setMaxResults(1)->getSingleResult(ORMAbstractQuery::HYDRATE_ARRAY);
        
        $event->setData($data);
    }
    
}
