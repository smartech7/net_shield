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

namespace Shieldon\Firewall\Panel\Sandbox;

use Shieldon\Messenger\RocketChat as RocketChatTest;

/**
 * The sandbox for RocketChat.
 */
class RocketChat
{
    /**
     * Invoker.
     *
     * @param array $args The arguments.
     *
     * @return bool
     */
    public function __invoke(array $args): bool
    {
        return $this->sandbox($args[0], $args[1]);
    }

    /**
     * Test RocketChat.
     *
     * @param array $getParams The GET params passed from tryMessenger method.
     * @param array $message   The message title and body.
     *
     * @return bool
     */
    private function sandbox($getParams, $message)
    {
        $serverUrl = $getParams['serverUrl'] ?? '';
        $userId = $getParams['userId'] ?? '';
        $accessToken = $getParams['accessToken'] ?? '';
        $channel = $getParams['channel'] ?? '';

        if (
            !empty($serverUrl) &&
            !empty($userId) &&
            !empty($accessToken) &&
            !empty($channel)
        ) {

            $messenger = new RocketChatTest(
                $accessToken,
                $userId,
                $serverUrl,
                $channel
            );

            if ($messenger->send($message['body'])) {
                // @codeCoverageIgnoreStart
                return true;
                // @codeCoverageIgnoreEnd
            }
        }
        return false;
    }
}
