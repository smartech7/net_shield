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

namespace Shieldon\FirewallTest\Log;

class ActionLogParsedCacheTest extends \PHPUnit\Framework\TestCase
{
    public function test__construct()
    {
        try {
            $logger = new \Shieldon\Firewall\Log\ActionLogParsedCache(BOOTSTRAP_DIR . '/../tmp/shieldon');
        } catch(\Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function testSaveAndGet()
    {
        $logger = new \Shieldon\Firewall\Log\ActionLogParsedCache(BOOTSTRAP_DIR . '/../tmp/shieldon');

        foreach (['yesterday', 'last_month', 'this_month', 'past_seven_hours', 'today'] as $period) {
            $data['foo'] = 'bar';
            $logger->save($period, $data);
            $s = $logger->get($period);
            $this->assertSame($s['foo'], $data['foo']);
        }
    }
}