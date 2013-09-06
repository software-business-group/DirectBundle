<?php

namespace Ext\DirectBundle\Tests\Router;

use Ext\DirectBundle\Router\Rule;

/**
 * Class ValidRules
 * @package Ext\DirectBundle\Tests\Router
 * @author Semyon Velichko <semyon@velichko.net>
 */
class ValidRules
{

    public static function getValidRulesByArray()
    {
        return array(
            'testArrayResponse' => array(
                'defaults' => array('controller' => 'ExtDirectBundle:ForTesting:testArrayResponse'),
                'isWithParams' => true
            ),
            'testObjectResponse' => array(
                'defaults' => array('controller' => 'ExtDirectBundle:ForTesting:testObjectResponse'),
                'isWithParams' => true
            ),
            'testResponseWithConfiguredReader' => array(
                'defaults' => array('controller' => 'ExtDirectBundle:ForTesting:testResponseWithConfiguredReader'),
                'isWithParams' => true,
                'reader' => array('successProperty' => 'successProperty', 'totalProperty' => 'totalProperty')
            ),
            'testFormHandlerResponse' => array(
                'defaults' => array('controller' => 'ExtDirectBundle:ForTesting:testFormHandlerResponse'),
                'isWithParams' => true,
                'isFormHandler' => true,
                'reader' => array('root' => 'data')
            ),
            'testFormValidationResponse' => array(
                'defaults' => array('controller' => 'ExtDirectBundle:ForTesting:testFormValidationResponse'),
                'isWithParams' => true,
                'isFormHandler' => true
            ),
            'testFormEntityValidationResponse' => array(
                'defaults' => array('controller' => 'ExtDirectBundle:ForTesting:testFormEntityValidationResponse'),
                'isWithParams' => true,
                'isFormHandler' => true
            ),
            'testServiceAction' => array(
                'defaults' => array('controller' => 'ext_direct_test_service:testActionAsService'),
                'isWithParams' => true
            ),
            'testException' => array(
                'defaults' => array('controller' => 'ExtDirectBundle:ForTesting:testException')
            ),
            'testCallable' => array(
                'defaults' => array('controller' => function() { return 'OK'; })
            )
        );
    }

    /**
     * @return array
     */
    public static function getValidRules()
    {
        $rules = array();
        foreach(self::getValidRulesByArray() as $alias => $value)
        {
            $Rule = new Rule(
                $alias,
                $value['defaults'],
                (isset($value['isWithParams'])?$value['isWithParams']:null),
                (isset($value['isFormHandler'])?$value['isFormHandler']:null)
            );

            if(isset($value['reader']))
                foreach($value['reader'] as $key => $param)
                    $Rule->setReaderParam($key, $param);

            $rules[] = array($Rule);
        }
        return $rules;
    }


}