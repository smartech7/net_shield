<?php declare(strict_types=1);
/*
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shieldon\Component;

use Shieldon\IpTrait;

use function implode;
use function preg_match;

/**
 * UserAgent
 */
class UserAgent extends ComponentProvider
{
    use IpTrait;

    const STATUS_CODE = 84;

    /**
     * Robot's user-agent text.
     * 
     * @var string
     */
    private $userAgent = '';

    /**
     * Constructor.
     * 
     * @param bool $strictMode
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Those robots are considered as bad behavior. Therefore we list them here.
        $this->deniedList = [

            // Web information crawlers
            'domain',         // Block all domain name information crawlers.
            'copyright',      // Block all copyright information crawlers.

            // SEO backlink crawlers
            'Ahrefs',         // http://ahrefs.com/robot/
            'roger',          // rogerbot (SEOMOZ)
            'moz.com',        // Block all SEOMOZ crawlers
            'MJ12bot',        // Majestic crawlers
            'findlinks',      // http://wortschatz.uni-leipzig.de/findlinks
            'Semrush',        // http://www.semrush.com/bot.html

            // Others
            'archive',        // Wayback machine
        ];

        $this->userAgent = $this->request->getHeaderLine('user-agent');
    }

    /**
     * {@inheritDoc}
     */
    public function isDenied(): bool
    {
        if (! empty($this->deniedList)) {
            if (preg_match('/(' . implode('|', $this->deniedList). ')/i', $this->userAgent)) {
                return true;
            }
        }

        if ($this->strictMode) {

            // If strict mode is on, this value can not be empty.
            if (empty($this->userAgent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Unique deny status code.
     *
     * @return int
     */
    public function getDenyStatusCode(): int
    {
        return self::STATUS_CODE;
    }
}