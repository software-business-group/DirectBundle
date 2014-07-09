<?php

namespace Ext\DirectBundle\Response;

use Doctrine\ORM\AbstractQuery as ORMAbstractQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Ext\DirectBundle\Event\Subscriber\GetSingleResultSubscriber;

/**
 * Class SingleResult
 *
 * @package Ext\DirectBundle\Response
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class SingleResult extends AbstractQuery implements ResponseInterface
{

    /**
     * @param ORMAbstractQuery|mixed $query
     *
     * @return $this|mixed|void
     */
    public function setContent($query)
    {
        parent::setContent($query);
        $this->addEventSubscriber(new GetSingleResultSubscriber());
    }

}
