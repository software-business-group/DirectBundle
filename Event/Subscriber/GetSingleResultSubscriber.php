<?php

namespace Ext\DirectBundle\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\AbstractQuery as ORMAbstractQuery;
use Ext\DirectBundle\Event\DirectEvents;
use Ext\DirectBundle\Event\ResponseEvent;

/**
 * Class GetSingleResultSubscriber
 *
 * @package Ext\DirectBundle\Event\Subscriber
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class GetSingleResultSubscriber implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
         return array(DirectEvents::PRE_QUERY_EXECUTE => 'callbackFunction');
    }

    /**
     * @param ResponseEvent $event
     */
    public function callbackFunction(ResponseEvent $event)
    {
        $data = $event->getData();

        $data = $data->setMaxResults(1)->getSingleResult(ORMAbstractQuery::HYDRATE_ARRAY);

        $event->setData($data);
    }

}
