<?php

namespace Ext\DirectBundle\Router;

/**
 * Class Serializable
 *
 * @package Ext\DirectBundle\Router
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class Serializable implements \Serializable
{

    /**
     * @return string
     */
    public function serialize()
    {
        $class = new \ReflectionClass($this);
        $values = array();
        foreach ($class->getProperties() as $property) {
            $name = $property->getName();
            $value = $this->$name;

            $values[$name] = $value;
        }

        return serialize($values);
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
        $values = unserialize($data);

        foreach ($values as $property => $value) {
            $this->$property = $value;
        }
    }

}
