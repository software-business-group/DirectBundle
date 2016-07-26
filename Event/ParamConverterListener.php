<?php


namespace Ext\DirectBundle\Event;

use \Sensio\Bundle\FrameworkExtraBundle\EventListener\ParamConverterListener as BasicParamConverterListener;

/**
 * @inheritdoc
 */
class ParamConverterListener extends BasicParamConverterListener
{

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            DirectEvents::CONTROLLER => 'onKernelController'
        );
    }

}
