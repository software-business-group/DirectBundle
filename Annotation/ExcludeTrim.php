<?php

namespace Ext\DirectBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class ExcludeTrim
 *
 * @Annotation
 * @Target("PROPERTY")
 *
 * @package Ext\DirectBundle\Annotation
 */
final class ExcludeTrim extends Annotation
{
    public $exclude = true;
}
