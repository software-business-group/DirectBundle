<?php

namespace Ext\DirectBundle\Event;

/**
 * Class DirectEvents
 *
 * @package Ext\DirectBundle\Event
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
final class DirectEvents
{

    const PRE_QUERY_EXECUTE = 'ext_direct.pre_query_execute';

    const POST_QUERY_EXECUTE = 'ext_direct.post_query_execute';

    const CONTROLLER = 'ext_direct.controller';

}
