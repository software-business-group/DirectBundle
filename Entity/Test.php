<?php

namespace Ext\DirectBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Test
 *
 * @package Ext\DirectBundle\Entity
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class Test
{
    /**
     * @var int
     */
    private $id;

    /**
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min= 0 , max = 100)
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
