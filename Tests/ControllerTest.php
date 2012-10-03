<?php
namespace Ext\DirectBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Ext\DirectBundle\Api\Api;

class ControllerTest extends WebTestCase
{
    
    public function __construct() {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
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
        return static::$kernel->getContainer()->get($serviceId);
    }
    
}
