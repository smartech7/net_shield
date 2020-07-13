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

namespace Shieldon\Firewall\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shieldon\Psr7\Response;
use InvalidArgumentException;
use function password_verify;
use function strpos;

/**
 * A PSR-15 middleware that provides WWW-Authenticate protection.
 */
class httpAuthentication implements MiddlewareInterface
{
    /**
     * 401 - Unauthorized.
     *
     * @var int
     */
    const HTTP_STATUS_CODE = 401;

    /**
     * The URL list that you want to protect.
     *
     * @var array
     */
    protected $protectedUrlList = [
        [
            // Begin-with URL 
            'url' => '/wp-amdin', 

            // Username
            'user' => 'wp_shieldon_user',

            // Password encrypted by `password_hash()` function.
            // In this case, the uncrypted string is `wp_shieldon_pass`.
            'pass' => '$2y$10$eA/S6rH3JDkYV9nrrUvuMOTh8Q/ts33DdCerbNAUpdwtSl3Xq9cQq'  
        ]
    ];

    /**
     * The text displays on prompted window.
     * Most modern browsers won't show this anymore. You can ignore that.
     *
     * @var string
     */
    protected $realm;

    /**
     * Constructor.
     * 
     * @param array  $protectedUrlList The list that want to be protected.
     * @param string $realm            The welcome message.
     *
     * @return void
     */
    public function __construct(array $protectedUrlList = [], string $realm = 'Welcome to area 51.')
    {
        $this->set($protectedUrlList);
        $this->realm = $realm;
    }

    /**
     * Invoker.
     *
     * @param ServerRequestInterface  $request The PSR-7 server request.
     * @param RequestHandlerInterface $handler The PSR-15 request handler.
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface  $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $currentUrl = $request->getUri()->getPath();
        $serverParams = $request->getServerParams();

        foreach ($this->protectedUrlList as $urlInfo) {

            // If we have set the protection for current URL.
            if (0 === strpos($currentUrl, $urlInfo['url'])) {

                // Prompt a window to ask for username and password.
                if (
                    !isset($serverParams['PHP_AUTH_USER']) ||
                    !isset($serverParams['PHP_AUTH_PW'])
                ) {
                    $authenticate = 'Basic realm="' . $this->realm . '"';
                    return (new Response)->
                        withStatus(self::HTTP_STATUS_CODE)->
                        withHeader('WWW-Authenticate', $authenticate);
                }

                // Identify the username and password for current URL.
                if (
                    $urlInfo['user'] !== $serverParams['PHP_AUTH_USER'] ||
                    !password_verify($serverParams['PHP_AUTH_PW'], $urlInfo['pass'])
                ) {
                    unset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
                    return (new Response)->withStatus(self::HTTP_STATUS_CODE);
                }
            }
        }

        return $handler->handle($request);
    }

    /**
     * Set up the URL list that you want to protect.
     * 
     * @param $protectedUrlList
     *
     * @return void
     */
    public function set(array $protectedUrlList = []): void
    {
        if (!empty($protectedUrlList)) {
            $count = count($protectedUrlList);
            $urlCount = count(array_column($protectedUrlList, 'url'));
            $userCount = count(array_column($protectedUrlList, 'user'));
            $passCount = count(array_column($protectedUrlList, 'pass'));

            if (
                $count !== $urlCount || 
                $count !== $userCount || 
                $count !== $passCount
            ) {
                throw new InvalidArgumentException(
                    'The columns in the array should be fit the specification. '
                );
            }

            $this->protectedUrlList = $protectedUrlList;
        }
    }
}

