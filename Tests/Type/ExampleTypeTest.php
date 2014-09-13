<?php

namespace Ext\DirectBundle\Tests\Type;

use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class ExampleTypeTest
 *
 * @package Ext\DirectBundle\Tests\Type
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class ExampleTypeTest extends TypeTestCase
{

    /**
     * @return array
     */
    public function getFilter()
    {
        $collection = array(
            'collection' => array('first' => 'first_value', 'second' => 'second_value')
        );

        return array(
            array(
                array(
                    array('property' => 'collection[first]', 'value' => 'first_value'),
                    array('property' => 'collection[second]', 'value' => 'second_value'),
                ), $collection));
    }

    /**
     * @param array $filter
     * @param array $result
     *
     * @dataProvider getFilter
     */
    public function testPrepareFilterDataListener($filter, $result)
    {
        $type = new ExampleType();
        $form = $this->factory->create($type);

        // submit the data to the form directly
        $form->submit($filter);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($form->getData(), $result);
    }

}
