<?php

namespace Ext\DirectBundle\Tests\Binder;

use Symfony\Component\Validator\Constraints as Assert;

class TestingEntity
{
    /**
     * @Assert\NotBlank()
     */
    private $first;
    
    /**
     * @Assert\NotBlank()
     */
    private $second;
    
    private $third;
    
    private $fourth;
    
    public function setFirst($first)
    {
        $this->first = $first;
    }
    
    public function getFirst()
    {
        return $this->first;
    }
    
    public function setSecond($second)
    {
        $this->second = $second;
    }
    
    public function getSecond()
    {
        return $this->second;
    }
    
    public function setThird($third)
    {
        $this->third = $third;
    }
    
    public function getThird()
    {
        return $this->third;
    }
    
    public function setFourth($fourth)
    {
        $this->fourth = $fourth;
    }
    
    public function getFourth()
    {
        return $this->fourth;
    }
}
