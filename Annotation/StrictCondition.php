<?php

namespace Ext\DirectBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class StrictCondition
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @package Ext\DirectBundle\Annotation
 */
final class StrictCondition extends Annotation
{
    /**
     * @var bool
     */
    public $strict = true;

    /**
     * @var string
     */
    public $repositoryName;
    /**
     * @var string
     */
    public $method;
}
