<?php

namespace Ext\DirectBundle\Annotation;

/**
 * Class Writer
 *
 * @package Ext\DirectBundle\Annotation
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 * @Annotation
 * @Target("METHOD")
 */
class Writer extends Base
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $root;

    /**
     * @param string $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}
