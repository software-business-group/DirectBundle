<?php

namespace Ext\DirectBundle\Router\Loader;

class FileLoader
{

    private $loaders = array();

    /**
     * @param LoaderInterface $loader
     */
    public function addLoader(LoaderInterface $loader)
    {
        $this->loaders[] = $loader;
    }

    /**
     * @return array
     */
    public function getLoaders()
    {
        return $this->loaders;
    }

    /**
     * @param $index
     * @return LoaderInterface
     */
    private function getLoader($index)
    {
        return $this->loaders[$index];
    }

    /**
     * @param $resource
     * @param null|string $type
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public function load($resource, $type = null)
    {
        foreach(array_keys($this->getLoaders()) as $index)
        {
            $loader = $this->getLoader($index);
            if($loader->supports($resource, $type))
                return $loader->load($resource);
        }
        throw new \UnexpectedValueException();
    }

}