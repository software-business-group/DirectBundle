<?php


namespace Ext\DirectBundle\Tests\Event\Listener;

use Ext\DirectBundle\Event\PrepareFilterDataSubscriber;

/**
 * Class PrepareFilterDataSubscriberTest
 *
 * @package Ext\DirectBundle\Tests\Event
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class PrepareFilterDataSubscriberTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function getFilter()
    {

        $collection = array('form_name' => array(
            'collection' => array('first' => 'first_value', 'second' => 'second_value')
        ));

        return array(
            array(
                'form_name',
                array(
                    array('property' => 'form_name[collection][first]', 'value' => 'first_value'),
                    array('property' => 'form_name[collection][second]', 'value' => 'second_value'),
                ), $collection));
    }

    /**
     * @param string $formName
     * @param array  $filter
     * @param array  $result
     *
     * @dataProvider getFilter
     */
    public function testTransform($formName, array $filter, array $result)
    {
        $listener = new PrepareFilterDataSubscriber();

        $prepared = $listener->prepare($filter);

        $this->assertArrayHasKey($formName, $prepared);
        $this->assertEquals($result, $prepared);
    }

}
