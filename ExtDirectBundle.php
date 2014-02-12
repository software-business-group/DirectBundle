<?php

namespace Ext\DirectBundle;

use Ext\DirectBundle\DependencyInjection\Compiler\CustomCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ExtDirectBundle
 *
 * @package Ext\DirectBundle
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class ExtDirectBundle extends Bundle
{

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CustomCompilerPass());
    }

}
