<?php


namespace Ext\DirectBundle\Tests\Type;

use Ext\DirectBundle\Event\PrepareFilterDataSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

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
        $collection = $builder->create('collection', FormType::class);
        $collection->add('first', TextType::class);
        $collection->add('second', TextType::class);
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
