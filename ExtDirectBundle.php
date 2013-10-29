<?php

namespace Ext\DirectBundle;

use Ext\DirectBundle\DependencyInjection\Compiler\CustomCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ExtDirectBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CustomCompilerPass());
    }

}
