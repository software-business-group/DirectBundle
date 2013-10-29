<?php

namespace Ext\DirectBundle\Router\Loader;

/**
 * Class AbstractLoader
 * @package Ext\DirectBundle\Router
 * @author Semyon Velichko <semyon@velichko.net>
 */
abstract class AbstractLoader
{

    /**
     * @var FileLoader
     */
    protected $fileLoader;

    abstract public function supports($resource, $type = null);
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
