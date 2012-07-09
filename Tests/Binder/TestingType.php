<?php

namespace Ext\DirectBundle\Tests\Binder;

use Ext\DirectBundle\Binder\Type\BinderType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class TestingType extends BinderType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        // обязательное по конфигу и по модели
        $builder->add('first', 'text', array('required' => true));
        // обязательное только по модели
        $builder->add('second', 'text');
        // обязательное только ко по конфигу
        $builder->add('third', 'text', array('required' => true));
        // необязательное
        $builder->add('fourth', 'text');
    }
    
    public function getName()
    {
        return 'testing';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Ext\DirectBundle\Tests\Binder\TestingEntity');
    }
}
