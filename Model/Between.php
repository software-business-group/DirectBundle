<?php

namespace Ext\DirectBundle\Model;

/**
 * Class Between
 *
 * @package Ext\DirectBundle\Model
 */
class Between
{
    /**
     * @var mixed $start
     */
    public $begin;

    /**
     * @var mixed $end
     */
    public $end;

    /**
     * @param \DateTime|mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return \DateTime|mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return bool
     */
    public function hasEnd()
    {
        return $this->end !== null;
    }

    /**
     * @param \DateTime|mixed $begin
     */
    public function setBegin($begin)
    {
        $this->begin = $begin;
    }

    /**
     * @return \DateTime|mixed
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * @return bool
     */
    public function hasBegin()
    {
        return $this->begin !== null;
    }

    /**
     * @return bool
     */
    public function hasBetween()
    {
        return $this->hasEnd() && $this->hasBegin();
    }
}
