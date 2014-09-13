<?php
/**
 * @package Payweb
 * @author Vladimir Dimitrischuck <vevtik@gmail.com>
 */

namespace Ext\DirectBundle\Model;

/**
 * Class SortItem
 */
class SortItem
{

    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var string
     */
    private $direction;

    /**
     * @var string
     */
    private $destinationPath;

    /**
     * @param string $destinationPath
     *
     * @return $this
     */
    public function setDestinationPath($destinationPath)
    {
        $this->destinationPath = $destinationPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationPath()
    {
        return $this->destinationPath;
    }

    /**
     * @param string $direction
     *
     * @return $this
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $propertyName
     *
     * @return $this
     */
    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

}
 