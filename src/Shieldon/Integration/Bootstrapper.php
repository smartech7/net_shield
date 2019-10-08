<?php declare(strict_types=1);
/*
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

namespace Shieldon\Integration;

use Shieldon\Firewall;
use Shieldon\FirewallPanel;

/**
 * The most easy way for implementing Shieldon Firewall in your PHP project.
 * This way does not follow the design pattern whatever what framwork you are using.
 * it is why it is so easy...
 * 
 * @since 3.0.1
 */
class Bootstrapper
{
    /**
     * Shieldon controller.
     *
     * @param string $storage    The absolute path of the storage where stores Shieldon generated data.
     * @param string $requestURI The entry URL of Firewall Panel.
     *
     * @return void
     */
    public function __construct($storage = '', $requestURI = '')
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            // Prevent possible issues occur in CLI command line.
            // Therefore we have to check out the REQUEST_URI variable that exists or not first.

            if ('' === $storage) {

                // shieldon folder is placed above wwwroot for best security, this folder must be writable.
                $storage = dirname($_SERVER['SCRIPT_FILENAME']) . '/../shieldon';
            }

            $firewall = new Firewall($storage);

            if (! empty($requestURI) && 0 === strpos($_SERVER['REQUEST_URI'], $requestURI)) {

                // Get into the Firewall Panel.
                $controlPanel = new FirewallPanel($firewall);
                $controlPanel->entry();
                exit;
            }

            $firewall->restful();
            $firewall->run();
        }
    }
}
