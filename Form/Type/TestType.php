<?php

namespace Ext\DirectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TestType extends AbstractType
{
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name');
        $builder->add('count');
    }
    
    public function getName()
    {
        return 'TestType';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Ext\DirectBundle\Entity\Test',
            'csrf_protection' => false
        );
    }
}
