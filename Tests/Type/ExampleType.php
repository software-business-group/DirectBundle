<?php


namespace Ext\DirectBundle\Tests\Type;

use Ext\DirectBundle\Event\PrepareFilterDataSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

/**
 * Class ExampleType
 *
 * @package Ext\DirectBundle\Tests\Type
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class ExampleType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $collection = $builder->create('collection', 'form');
        $collection->add('first', 'text');
        $collection->add('second', 'text');
        $builder->add($collection);
        $subscriber = new PrepareFilterDataSubscriber();
        $builder->addEventSubscriber($subscriber);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'example_type';
    }
}
