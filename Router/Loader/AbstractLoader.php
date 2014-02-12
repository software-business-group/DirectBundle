<?php

namespace Ext\DirectBundle\Router\Loader;

/**
 * Class AbstractLoader
 *
 * @package Ext\DirectBundle\Router\Loader
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
abstract class AbstractLoader
{

    /**
     * @var FileLoader
     */
    protected $fileLoader;

    /**
     * @param mixed $resource
     * @param mixed $type
     *
     * @return mixed
     */
    abstract public function supports($resource, $type = null);

    /**
     * @param mixed $resource
     */
    abstract public function load($resource);

    /**
     * @param FileLoader $fileLoader
     */
    public function setFileLoader(FileLoader $fileLoader)
    {
        $this->fileLoader = $fileLoader;
    }

    /**
     * @return FileLoader
     */
    protected function getFileLoader()
    {
        return $this->fileLoader;
    }

}
