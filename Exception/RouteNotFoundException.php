<?php

namespace Ext\DirectBundle\Exception;

/**
 * Class RouteNotFoundException
 *
 * @package Ext\DirectBundle\Exception
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class RouteNotFoundException extends \InvalidArgumentException
{
    /**
     * @var string
     */
    protected $message = 'Route not found';
}
