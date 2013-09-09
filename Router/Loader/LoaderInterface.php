<?php

namespace Ext\DirectBundle\Router\Loader;

interface LoaderInterface
{

    public function supports($resource, $type);
    public function load($resource);

}
