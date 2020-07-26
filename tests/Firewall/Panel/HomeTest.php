<?php
/**
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * php version 7.1.0
 * 
 * @category  Web-security
 * @package   Shieldon
 * @author    Terry Lin <contact@terryl.in>
 * @copyright 2019 terrylinooo
 * @license   https://github.com/terrylinooo/shieldon/blob/2.x/LICENSE MIT
 * @link      https://github.com/terrylinooo/shieldon
 * @see       https://shieldon.io
 */

declare(strict_types=1);

namespace Shieldon\FirewallTest\Panel;

class HomeTest extends \Shieldon\FirewallTest\ShieldonTestCase
{
    use RouteTestTrait;

    public function testOverview()
    {
        $this->assertPageOutputContainsString(
            'firewall/panel/home/overview',
            'Overview'
        );
    }

    public function testOverviewFormPostResetDataCircle()
    {
        $_POST['action_type'] = 'reset_data_circle';
        $this->refreshRequest();

        $this->assertPageOutputContainsString(
            'firewall/panel/home/overview',
            'Data circle tables have been reset.'
        );
    }

    public function testOverviewFormPostResetActionLogs()
    {
        $_POST['action_type'] = 'reset_action_logs';
        $this->refreshRequest();

        $this->assertPageOutputContainsString(
            'firewall/panel/home/overview',
            'Action logs have been removed.'
        );
    }

    public function overviewTemplateVarsOfActionLogger()
    {

    }
}