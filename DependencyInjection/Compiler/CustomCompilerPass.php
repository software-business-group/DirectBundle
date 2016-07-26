<?php

namespace Ext\DirectBundle\DependencyInjection\Compiler;

use Ext\DirectBundle\Event\ParamConverterListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
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

        $definition = $container->getDefinition('ext_direct.search.helper');
        foreach ($container->findTaggedServiceIds('ext_direct.search.field_processor') as $id => $attributes) {
            $definition->addMethodCall(
                'addFieldProcessor',
                array(new Reference($id) )
            );
        }

        if (true === $container->getParameter('ext_direct.param_converter')) {
            if (false === $container->hasDefinition('sensio_framework_extra.converter.listener')) {
                throw new \LogicException('SensioFrameworkExtraBundle should be installed');
            }

            $container->addDefinitions(
                array('ext_direct.param_converter.listener' => new Definition(ParamConverterListener::class,
                        array($container->findDefinition('sensio_framework_extra.converter.manager'), true)
                    )
                )
            );

            $container->findDefinition('event_dispatcher')
                ->addMethodCall('addSubscriberService',
                    array('ext_direct.param_converter.listener', ParamConverterListener::class)
                );
        }

    }

}
