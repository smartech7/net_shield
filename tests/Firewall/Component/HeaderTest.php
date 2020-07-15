<?php
/*
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Shieldon\FirewallTest\Component;

class HeaderTest extends \PHPUnit\Framework\TestCase
{
    public function testSetStrict()
    {
        $headerComponent = new \Shieldon\Firewall\Component\Header();
        $headerComponent->setStrict(false);

        $reflection = new \ReflectionObject($headerComponent);
        $t = $reflection->getProperty('strictMode');
        $t->setAccessible(true);
  
        $this->assertEquals('strictMode' , $t->name);
        $this->assertFalse($t->getValue($headerComponent));
    }

    public function testIsDenied()
    {
        $_SERVER['HTTP_TEST_VAR'] = 'This is a test string.';
        reload_request();

        $headerComponent = new \Shieldon\Firewall\Component\Header();
        
        $headerComponent->setDeniedItem('test', 'test-var');

        $result = $headerComponent->isDenied();
        $this->assertTrue($result);

        $_SERVER['HTTP_TEST_VAR'] = 'This is a t2est string.';
        reload_request();

        $result = $headerComponent->isDenied();
        $this->assertFalse($result);

        $headerComponent = new \Shieldon\Firewall\Component\Header();
        $headerComponent->setStrict(true);
        $result = $headerComponent->isDenied();
        $this->assertTrue($result);
    }

    public function testGetHeaders()
    {
        unset($_SERVER);
        unset($_POST);

        $_SERVER['HTTP_TEST_VAR'] = 'This is a test string.';
        $_SERVER['HTTP_TEST_VAR2'] = 'This is a testt string.';
        reload_request();

        $headerComponent = new \Shieldon\Firewall\Component\Header();
        $results = $headerComponent->getHeaders();

        $this->assertSame($results, [
            'test-var' => ['This is a test string.'],
            'test-var2' => ['This is a testt string.'],
        ]);

        unset($_SERVER['HTTP_TEST_VAR']);
        unset($_SERVER['HTTP_TEST_VAR2']);
        reload_request();

        $headerComponent = new \Shieldon\Firewall\Component\Header();
        $results = $headerComponent->getHeaders();
        $this->assertSame($results, []);
    }

    public function testSetDeniedList()
    {
        $list = ['gzip', 'robot'];

        $headerComponent = new \Shieldon\Firewall\Component\Header();
        $headerComponent->setDeniedItems($list);

        $deniedList = $headerComponent->getDeniedItems();

        $this->assertSame($deniedList, $list);
    }


    public function testSetDeniedItem()
    {
        $string = 'gizp';

        $headerComponent = new \Shieldon\Firewall\Component\Header();
        $headerComponent->setDeniedItem($string);

        $deniedList = $headerComponent->getDeniedItems();

        if (in_array($string, $deniedList)) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }

    public function testGetDeniedList()
    {
        $headerComponent = new \Shieldon\Firewall\Component\Header();
        $deniedList = $headerComponent->getDeniedItems();

        $this->assertSame($deniedList, []);
    }

    public function testRemoveItem()
    {
        $string = 'gzip';

        $headerComponent = new \Shieldon\Firewall\Component\Header();
        $headerComponent->setDeniedItem($string);

        $deniedList = $headerComponent->getDeniedItems();

        $this->assertSame($deniedList, ['gzip']);

        $headerComponent->removeDeniedItems();
        $deniedList = $headerComponent->getDeniedItems();

        $this->assertSame($deniedList, []);
    }

    public function testGetDenyStatusCode()
    {
        $headerComponent = new \Shieldon\Firewall\Component\Header();
        $statusCode = $headerComponent->getDenyStatusCode();

        $this->assertSame(83, $statusCode);
    }
}
