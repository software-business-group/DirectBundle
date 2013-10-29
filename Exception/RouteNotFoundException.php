<?php

namespace Ext\DirectBundle\Exception;

class RouteNotFoundException extends \InvalidArgumentException
{
    protected $message = 'Route not found';
}
