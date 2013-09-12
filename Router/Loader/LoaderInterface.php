<?php

namespace Ext\DirectBundle\Router\Loader;

interface LoaderInterface
{

    public function supports($resource, $type = null);
    public function load($resource, $type = null);

}
