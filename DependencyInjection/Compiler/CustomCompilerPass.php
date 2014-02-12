<?php

namespace Ext\DirectBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CustomCompilerPass
 *
 * @package Ext\DirectBundle\DependencyInjection\Compiler
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class CustomCompilerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ext_direct.file.loader')) {
            return;
        }

        $definition = $container->getDefinition(
            'ext_direct.file.loader'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'ext_direct.loader'
        );

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addLoader',
                array(new Reference($id))
            );
        }
        $definition->addMethodCall('loadInitialResource');

    }

}
