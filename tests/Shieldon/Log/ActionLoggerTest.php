<?php declare(strict_types=1);
/*
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shieldon\Log;


class ActionLoggerTest extends \PHPUnit\Framework\TestCase
{
    public function test__construct()
    {
        try {
            $logger = new ActionLogger(BOOTSTRAP_DIR . '/../tmp/shieldon');

            $info = $logger->getCurrentLoggerInfo();

            $this->assertIsArray($info);

        } catch(\Exception $e) {
            $this->assertTrue(false);
        }

        if ($logger instanceof ActionLogger) {
            $this->assertTrue(true);
        }

        
    }

    public function testAdd() 
    {
        $shieldon = new \Shieldon\Shieldon();
        $logger = new ActionLogger(BOOTSTRAP_DIR . '/../tmp/shieldon/test_logs', '19890604');

        $data['ip'] = '127.0.0.1';
        $data['session_id'] = md5((string) time());
        $data['action_code'] = $shieldon::ACTION_TEMPORARILY_DENY;
        $data['timesamp'] = time();

        $logger->add($data);

        $data['ip'] = '127.0.0.1';
        $data['session_id'] = md5((string) time());
        $data['action_code'] = $shieldon::ACTION_UNBAN;
        $data['timesamp'] = time() + 4;

        $logger->add($data);

        $results = $logger->get('19890604');

        $this->assertEquals($data['ip'], $results[1]['ip']);
        $this->assertEquals($data['action_code'], $results[1]['action_code']);
        $this->assertEquals($data['timesamp'], $results[1]['timesamp']);

        $results = $logger->get('19890604', date('Ymd'));

        $logger->purgeLogs();
    }

    public function testGet() {
        // This method has been tested in testAdd.
    }

    public function testCheckDirectory()
    {
        $logger = new ActionLogger(BOOTSTRAP_DIR . '/../tmp/shieldon');

        $reflection = new \ReflectionObject($logger);
        $methodCreateDirectory = $reflection->getMethod('checkDirectory');
        $methodCreateDirectory->setAccessible(true);

        $result = $methodCreateDirectory->invokeArgs($logger, []);

        $this->assertTrue($result);
    }

    public function testPurgeLogs()
    {
        // This method has been tested in testAdd.
    }
}