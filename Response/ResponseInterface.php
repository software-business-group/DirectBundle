<?php

namespace Ext\DirectBundle\Response;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Interface ResponseInterface
 *
 * @package Ext\DirectBundle\Response
 */
interface ResponseInterface
{

    /**
     * @param ResponseFactory $factory
     *
     * @return mixed
     */
    public function setFactory(ResponseFactory $factory);

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function setContent($data);

    /**
     * @return mixed
     */
    public function extract();

    /**
     * @param int $total
     *
     * @return mixed
     */
    public function setTotal($total);

    /**
     * @param boolean $success
     *
     * @return mixed
     */
    public function setSuccess($success);

    /**
     * @param EventSubscriberInterface $subscriber
     *
     * @return mixed
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber);

}
