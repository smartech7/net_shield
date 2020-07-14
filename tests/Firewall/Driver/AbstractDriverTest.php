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

namespace Shieldon\FirewallTest\Driver;

class AbstractDriverTest extends \PHPUnit\Framework\TestCase
{
    public function testHas()
    {
        $mock = $this->getMockForAbstractClass('Shieldon\Firewall\Driver\AbstractDriver');
        $this->assertFalse($mock->has('22.22.22.22'));
    }
}
