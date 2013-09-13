<?php


namespace Ext\DirectBundle\Router;

/**
 * Class Rule
 * @package Ext\DirectBundle\Router
 * @author Semyon Velichko <semyon@velichko.net>
 */
class Rule
{

    /**
     * @var string
     */
    private $alias;

    /**
     * @var array
     */
    private $defaults = array();

    /**
     * @var array
     */
    private $reader = array(
        'type' => 'json',
        'root' => null,
        'successProperty' => 'success',
        'totalProperty' => 'total'
    );

    /**
     * @var array
     */
    private $writer = array(
        'type' => 'json',
        'root' => null
    );

    /**
     * @var bool
     */
    private $isWithParams;

    /**
     * @var bool
     */
    private $isFormHandler;

    /**
     * @param $alias
     * @param $defaults
     * @param bool $isWithParams
     * @param bool $isFormHandler
     */
    public function __construct($alias, $defaults, $isWithParams = true, $isFormHandler = false)
    {
        $this->setAlias($alias);
        $this->setDefaults($defaults);
        $this->setIsWithParams($isWithParams);
        $this->setIsFormHandler($isFormHandler);
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param $defaults
     * @throws \InvalidArgumentException
     */
    public function setDefaults($defaults)
    {
        if(!isset($defaults['controller']))
            throw new \InvalidArgumentException('The controller does not defined');

        $this->defaults = $defaults;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @return string|object
     */
    public function getController()
    {
        return $this->defaults['controller'];
    }

    /**
     * @param boolean $isFormHandler
     */
    public function setIsFormHandler($isFormHandler)
    {
        $this->isFormHandler = $isFormHandler;
    }

    /**
     * @return boolean
     */
    public function getIsFormHandler()
    {
        return $this->isFormHandler === true;
    }

    /**
     * @param $isWithParams
     */
    public function setIsWithParams($isWithParams)
    {
        $this->isWithParams = $isWithParams;
    }

    /**
     * @return bool
     */
    public function getIsWithParams()
    {
        return $this->isWithParams === true;
    }

    /**
     * @return array
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * @return array
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasReaderParam($key)
    {
        return isset($this->reader[$key]);
    }

    /**
     * @param $key
     * @param $value
     * @throws \InvalidArgumentException
     */
    public function setReaderParam($key, $value)
    {
        if(!array_key_exists($key, $this->reader))
            throw new \InvalidArgumentException(
                sprintf('This (%s) reader param does not supported', $key)
            );

        $this->reader[$key] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasWriterParam($key)
    {
        return isset($this->reader[$key]);
    }

    /**
     * @param $key
     * @param $value
     * @throws \InvalidArgumentException
     */
    public function setWriterParam($key, $value)
    {
        if(!array_key_exists($key, $this->writer))
            throw new \InvalidArgumentException(
                sprintf('This (%s) reader param does not supported', $key)
            );

        $this->writer[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getReaderParam($key)
    {
        if($this->hasReaderParam($key))
            throw new \InvalidArgumentException(
                sprintf('This (%s) reader param  not exist', $key)
            );

        return $this->reader[$key];
    }

    /**
     * @param $root
     */
    public function setReaderRoot($root)
    {
        $this->setReaderParam('root', $root);
    }

    /**
     * @param $property
     */
    public function setReaderSuccessProperty($property)
    {
        $this->setReaderParam('successProperty', $property);
    }

    /**
     * @param $property
     */
    public function setReaderTotalProperty($property)
    {
        $this->setReaderParam('totalProperty', $property);
    }

}