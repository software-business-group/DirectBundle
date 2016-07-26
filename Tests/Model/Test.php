<?php

namespace Ext\DirectBundle\Tests\Model;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\ReadOnly;

/**
 * Class Test
 *
 * @package Ext\DirectBundle\Tests\Model
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 *
 * @ExclusionPolicy("all")
 */
class Test
{
    /**
     * @var int
     *
     * @Expose
     * @ReadOnly
     */
    private $id;

    /**
     * @Assert\NotBlank()
     *
     * @Expose
     * @ReadOnly
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min= 0 , max = 100)
     *
     * @Expose
     * @ReadOnly
     */
    private $count;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }
}
