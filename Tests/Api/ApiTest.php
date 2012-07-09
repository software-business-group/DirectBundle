<?php
namespace Ext\DirectBundle\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Ext\DirectBundle\Api\Api;

require_once __DIR__.'../../../../../../../app/AppKernel.php';

/**
 * Test class of ExtDirect Api.
 *
 * @author Otavio Fernandes <otavio@neton.com.br>
 */
class ApiTest extends WebTestCase
{
    
    protected static $kernel;
    protected static $container;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    
    public function __construct() {
        self::$kernel = new \AppKernel('test', true);
        self::$kernel->boot();
        self::$container = self::$kernel->getContainer();
        $this->em = self::$container ->get('doctrine.orm.entity_manager');
    }
    
    /**
     * Enter description here ...
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager ()
    {
        return $this->em;
    }
    
    public function get($serviceId)
    {
        return self::$container->get($serviceId);
    }
    
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
        
        $api = new Api($config);
        $apiString = $api->__toString();
        $apiJson = json_decode($apiString);
        
        $this->assertObjectHasAttribute('actions', $apiJson);
        $this->assertObjectHasAttribute('ExtDirect_Direct', $apiJson->actions);
        $this->assertObjectHasAttribute('len', $apiJson->actions->ExtDirect_Direct[0]);
        $this->assertEquals(0, $apiJson->actions->ExtDirect_Direct[0]->len);
        
        $this->assertObjectHasAttribute('len', $apiJson->actions->ExtDirect_Direct[1]);
        $this->assertObjectHasAttribute('formHandler', $apiJson->actions->ExtDirect_Direct[1]);
        $this->assertEquals(1, $apiJson->actions->ExtDirect_Direct[1]->len);
        $this->assertEquals(1, $apiJson->actions->ExtDirect_Direct[1]->formHandler);
    }
}
