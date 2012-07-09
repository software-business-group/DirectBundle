<?php
namespace Ext\DirectBundle\Tests\Binder;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Ext\DirectBundle\Binder;
use Ext\DirectBundle\Tests\Binder as Test;
use Symfony\Component\HttpFoundation;

class BinderFactoryTest extends WebTestCase
{    
    public function testBindRequest()
    {
        $client = static::createClient();
        $client->request('POST', '/direct_bundle_test', array('first' => '123', 'second' => '321'));
    }
}
