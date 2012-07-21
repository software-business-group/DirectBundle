<?php

namespace Ext\DirectBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Test
{
    private $id;
    
    /**
     * @Assert\NotBlank()
     */
    private $name;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Min(0)
     * @Assert\Max(100)
     */
    private $count;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setCount($count)
    {
        $this->count = $count;
    }
    
    public function getCount()
    {
        return $this->count;
    }
}
