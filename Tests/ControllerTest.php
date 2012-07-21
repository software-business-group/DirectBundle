<?php
namespace Ext\DirectBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Ext\DirectBundle\Api\Api;

require_once __DIR__.'../../../../../../app/AppKernel.php';

class ControllerTest extends WebTestCase
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
    
}
