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

namespace Shieldon\Firewall\Messenger;

use Shieldon\Messenger\Messenger\MessengerInterface;
use Shieldon\Messenger\Sendgrid;
use function Shieldon\Firewall\__;

/**
 * The get for Sendgrid.
 */
class ItemSendgrid
{
    /**
     * Initialize and get the instance.
     *
     * @param array $setting The configuration of that messanger.
     *
     * @return MessengerInterface
     */
    public static function get(array $setting): MessengerInterface
    {
        $apiKey = $setting['config']['api_key'] ?? '';
        $sender = $setting['config']['sender'] ?? '';
        $recipients = $setting['config']['recipients'] ?? [];

        $instance = new Sendgrid($apiKey);
        $instance->setSubject(__('core', 'messenger_text_mail_subject'));
        $instance->addSender($sender);

        foreach ($recipients as $recipient) {
            $instance->addRecipient($recipient);
        }

        return $instance;
    }
}