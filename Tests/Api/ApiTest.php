<?php
namespace Ext\DirectBundle\Tests\Api;

use Ext\DirectBundle\Tests\ControllerTest;
use Ext\DirectBundle\Api\Api;

/**
 * Test class of ExtDirect Api.
 *
 * @author Otavio Fernandes <otavio@neton.com.br>
 */
class ApiTest extends ControllerTest
{
    /**
     * Test Api->__toString() method.
     */
    public function test__toString()
    {
        $client = $this->createClient();
        $config = $this->get('ext_direct.controller')->getConfig();
        
        $config['router']['rules'] += array('getDirectTest' => array('defaults' =>
                                                array('_controller' => 'ExtDirectBundle:Direct:getDirectTest', 'form' => false, 'params' => false)));
        
        $config['router']['rules'] += array('getFormTest' => array('defaults' =>
                                                array('_controller' => 'ExtDirectBundle:Direct:getFormTest', 'form' => true, 'params' => true)));
        
        $config['router']['rules'] += array('getServiceTest' => array('defaults' =>
                                                array('_controller' => 'ext_direct_api_test_service:getServiceTest', 'form' => true, 'params' => true)));
        
        $api = new Api($config);
        $apiString = $api->__toString();
        $apiJson = json_decode($apiString);
        
        $this->assertObjectHasAttribute('actions', $apiJson);
        $this->assertObjectHasAttribute('ExtDirect_Direct', $apiJson->actions);
        $this->assertObjectHasAttribute('ext_direct_api_test_service', $apiJson->actions);
        $this->assertObjectHasAttribute('len', $apiJson->actions->ExtDirect_Direct[0]);
        $this->assertEquals(0, $apiJson->actions->ExtDirect_Direct[0]->len);
        
        $this->assertObjectHasAttribute('len', $apiJson->actions->ExtDirect_Direct[1]);
        $this->assertObjectHasAttribute('formHandler', $apiJson->actions->ExtDirect_Direct[1]);
        $this->assertEquals(1, $apiJson->actions->ExtDirect_Direct[1]->len);
        $this->assertEquals(1, $apiJson->actions->ExtDirect_Direct[1]->formHandler);
        
        $this->assertObjectHasAttribute('len', $apiJson->actions->ext_direct_api_test_service[0]);
        $this->assertObjectHasAttribute('formHandler', $apiJson->actions->ext_direct_api_test_service[0]);
        $this->assertEquals(1, $apiJson->actions->ext_direct_api_test_service[0]->len);
        $this->assertEquals(1, $apiJson->actions->ext_direct_api_test_service[0]->formHandler);

    }
}
