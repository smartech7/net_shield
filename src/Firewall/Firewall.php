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

namespace Shieldon\Firewall;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Shieldon\Firewall\Kernel;
use Shieldon\Firewall\Captcha as Captcha;

use Shieldon\Firewall\Middleware as Middleware;

use Shieldon\Firewall\Firewall\Driver\DriverFactory;
use Shieldon\Firewall\Firewall\Messenger\MessengerFactory;
use Shieldon\Firewall\Firewall\Captcha\CaptchaFactory;
use Shieldon\Firewall\Utils\Container;
use Shieldon\Firewall\Log\ActionLogger;
use Shieldon\Firewall\FirewallTrait;
use Shieldon\Firewall\Firewall\XssProtectionTrait;
use Shieldon\Psr15\RequestHandler;
use function Shieldon\Firewall\get_request;
use function Shieldon\Firewall\get_response;

use PDO;
use PDOException;
use Redis;
use RedisException;
use RuntimeException;

use function array_column;
use function defined;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function is_dir;
use function json_decode;
use function json_encode;
use function mkdir;
use function rtrim;
use function strpos;
use function umask;
use function time;
use function strtotime;
use function date;

/**
 * Managed Firewall.
 */
class Firewall
{
    use FirewallTrait;
    use XssProtectionTrait;

    /**
     * Collection of PSR-7 or PSR-15 middlewares.
     *
     * @var array
     */
    protected $middlewares = [];

    /**
     * Constructor.
     */
    public function __construct(?ServerRequestInterface $request = null, ?ResponseInterface $response = null)
    {
        Container::set('firewall', $this);

        $this->kernel = new Kernel($request, $response);
    }

    /**
     * Set up the path of the configuration file.
     *
     * @param string $source The path.
     * @param string $type   The type.
     * 
     * @return void
     */
    public function configure(string $source, string $type = 'json')
    {
        if ($type === 'json') {
            $this->directory = rtrim($source, '\\/');
            $configFilePath = $this->directory . '/' . $this->filename;

            if (file_exists($configFilePath)) {
                $jsonString = file_get_contents($configFilePath);

            } else {
                $jsonString = file_get_contents(__DIR__ . '/../../config.json');

                if (defined('PHP_UNIT_TEST')) {
                    $jsonString = file_get_contents(__DIR__ . '/../../tests/config.json');
                }
            }

            $this->configuration = json_decode($jsonString, true);
            $this->kernel->managedBy('managed');

        } elseif ($type === 'php') {
            $this->configuration = require $source;
            $this->kernel->managedBy('config');
        }

        $this->setup();
    }

    /**
     * Add middlewares and use them before going into Shieldon kernal.
     *
     * @param MiddlewareInterface $middleware A PSR-15 middlewares.
     *
     * @return void
     */
    public function add(MiddlewareInterface $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Setup everything we need.
     *
     * @return void
     */
    public function setup(): void
    {
        $this->status = $this->getOption('daemon');

        $this->setDriver();

        $this->setChannel();

        $this->setIpSource();

        $this->setLogger();

        $this->setFilters();

        $this->setComponents();

        $this->setCaptchas();

        $this->setSessionLimit();

        $this->setCronJob();

        $this->setExcludedUrls();

        $this->setXssProtection();

        $this->setAuthentication();

        $this->setDialogUI();

        $this->setMessengers();

        $this->setMessageEvents();

        $this->setDenyAttempts();

        $this->setIptablesWatchingFolder();
    }

    /**
     * Just, run!
     *
     * @return ResponseInterface
     */
    public function run(): ResponseInterface
    {
        // If settings are ready, let's start monitoring requests.
        if ($this->status) {

            $response = get_request();

            // PSR-15 request handler.
            $requestHandler = new RequestHandler();

            foreach ($this->middlewares as $middleware) {
                $requestHandler->add($middleware);
            }

            $response = $requestHandler->handle($response);

            // Something is detected by Middlewares, return.
            if ($response->getStatusCode() !== 200) {
                return $response;
            }

            $result = $this->kernel->run();

            if ($result !== $this->kernel::RESPONSE_ALLOW) {

                if ($this->kernel->captchaResponse()) {
                    $this->kernel->unban();

                    $response = $response->withHeader('Location', $this->kernel->getCurrentUrl());
                    $response = $response->withStatus(303);

                    return $response;
                }
            }
        }

        return $this->kernel->respond();
    }

    /**
     * Set the channel ID.
     *
     * @return void
     */
    protected function setChannel(): void
    {
        $channelId = $this->getOption('channel_id');

        if ($channelId) {
            $this->kernel->setChannel($channelId);
        }
    }

    /**
     * Set a data driver for the use of Shiedon Firewall.
     * Currently supports File, Redis, MySQL and SQLite.
     *
     * @return void
     */
    protected function setDriver(): void
    {
        $driverType = $this->getOption('driver_type');
        $driverSetting = $this->getOption($driverType, 'drivers');

        if (isset($driverSetting['directory_path'])) {
            $driverSetting['directory_path'] = $driverSetting['directory_path'] ?: $this->directory;
        }

        $driverInstance = DriverFactory::getInstance($driverType, $driverSetting);

        $this->status = false;
        if ($driverInstance !== null) {
            $this->kernel->add($driverInstance);
            $this->status = true;
        }
    }

    /**
     * Set up the action logger.
     *
     * @return void
     */
    protected function setLogger(): void
    {
        $loggerSetting = $this->getOption('action', 'loggers');

        if ($loggerSetting['enable']) {
            if (!empty($loggerSetting['config']['directory_path'])) {
                $this->kernel->add(new ActionLogger($loggerSetting['config']['directory_path']));
            }
        }
    }

    /**
     * If you use CDN, please choose the real IP source.
     *
     * @return void
     */
    protected function setIpSource(): void
    {
        $ipSourceType = $this->getOption('ip_variable_source');
        $serverParams = get_request()->getServerParams();

        /**
         * REMOTE_ADDR: general
         * HTTP_CF_CONNECTING_IP: Cloudflare
         * HTTP_X_FORWARDED_FOR: Google Cloud CDN, Google Load-balancer, AWS.
         * HTTP_X_FORWARDED_HOST: KeyCDN, or other CDN providers not listed here.
         * 
         */
        $key = array_search(true, $ipSourceType);
        $ip = $serverParams[$key];

        if (empty($ip)) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('IP source is not set correctly.');
            // @codeCoverageIgnoreEnd
        }

        $this->kernel->setIp($ip);
    }

    /**
     * Set the filiters.
     *
     * @return void
     */
    protected function setFilters(): void
    {
        $sessionSetting   = $this->getOption('session', 'filters');
        $cookieSetting    = $this->getOption('cookie', 'filters');
        $refererSetting   = $this->getOption('referer', 'filters');
        $frequencySetting = $this->getOption('frequency', 'filters');

        $filterConfig = [
            'session'   => $sessionSetting['enable'],
            'cookie'    => $cookieSetting['enable'],
            'referer'   => $refererSetting['enable'],
            'frequency' => $frequencySetting['enable'],
        ];

        $this->kernel->setFilters($filterConfig);

        $this->kernel->setProperty('limit_unusual_behavior', [
            'session' => $sessionSetting['config']['quota'] ?? 5,
            'cookie'  => $cookieSetting['config']['quota'] ?? 5,
            'referer' => $refererSetting['config']['quota'] ?? 5,
        ]);

        // if ($frequencySetting['enable']) {
        $frequencyQuota = [
            's' => $frequencySetting['config']['quota_s'] ?? 2,
            'm' => $frequencySetting['config']['quota_m'] ?? 10,
            'h' => $frequencySetting['config']['quota_h'] ?? 30,
            'd' => $frequencySetting['config']['quota_d'] ?? 60,
        ];

        $this->kernel->setProperty('time_unit_quota', $frequencyQuota);

        // if ($cookieSetting['enable']) {
        $cookieName = $cookieSetting['config']['cookie_name'] ?? 'ssjd';
        $cookieDomain = $cookieSetting['config']['cookie_domain'] ?? '';
        $cookieValue = $cookieSetting['config']['cookie_value'] ?? '1';

        $this->kernel->setProperty('cookie_name', $cookieName);
        $this->kernel->setProperty('cookie_domain', $cookieDomain);
        $this->kernel->setProperty('cookie_value', $cookieValue);

        // if ($refererSetting['enable']) {
        $this->kernel->setProperty('interval_check_referer', $refererSetting['config']['time_buffer']);

        // if ($sessionSetting['enable']) {
        $this->kernel->setProperty('interval_check_session', $sessionSetting['config']['time_buffer']);
        
    }

    /**
     * Set the components.
     *
     * @return void
     */
    protected function setComponents(): void
    {
        $componentConfig = [
            'Ip' => $this->getOption('ip', 'components'),
            'Rdns' => $this->getOption('rdns', 'components'),
            'Header' => $this->getOption('header', 'components'),
            'UserAgent' => $this->getOption('user_agent', 'components'),
            'TrustedBot' => $this->getOption('trusted_bot', 'components'),
        ];

        foreach ($componentConfig as $className => $config) {
            $class = 'Shieldon\Firewall\Component\\' . $className;

            if ($config['enable']) {
                $componentInstance = new $class();

                if ($className === 'Ip') {
                    $this->kernel->add($componentInstance);

                    // Need Ip component to be loaded before calling this method.
                    $this->applyComponentIpManager();
                    
                } elseif ($config['strict_mode']) {
                    $componentInstance->setStrict(true);
                    $this->kernel->add($componentInstance);
                }
            }
        }
    }

    /**
     * Set the Captcha modules.
     *
     * @return void
     */
    protected function setCaptchas(): void
    {
        $recaptchaSetting = $this->getOption('recaptcha', 'captcha_modules');
        $imageSetting = $this->getOption('image', 'captcha_modules');

        $captchaList = [
            'recaptcha',
            'image',
        ];

        foreach ($captchaList as $captcha) {
            $setting = $this->getOption($captcha, 'captcha_modules');

            if (is_array($setting)) {

                // Initialize messenger instances from the factory/
                if (CaptchaFactory::check($captcha, $setting)) {
    
                    $this->kernel->add(
                        CaptchaFactory::getInstance(
                            // The ID of the captcha module in the configuration.
                            $captcha, 
                            // The settings of the captcha module in the configuration.
                            $setting    
                        )
                    );
                }
            }

            unset($setting);
        }

        if ($recaptchaSetting['enable']) {

            $googleRecaptcha = [
                'key'     => $recaptchaSetting['config']['site_key'],
                'secret'  => $recaptchaSetting['config']['secret_key'],
                'version' => $recaptchaSetting['config']['version'],
                'lang'    => $recaptchaSetting['config']['lang'],
            ];

            $this->kernel->add(new Captcha\Recaptcha($googleRecaptcha));
        }

        if ($imageSetting['enable']) {

            $type = $imageSetting['config']['type'] ?? 'alnum';
            $length = $imageSetting['config']['length'] ?? 8;

            switch ($type) {
                case 'numeric':
                    $imageCaptchaConfig['pool'] = '0123456789';
                    break;

                case 'alpha':
                    $imageCaptchaConfig['pool'] = '0123456789abcdefghijklmnopqrstuvwxyz';
                    break;

                case 'alnum':
                default:
                    $imageCaptchaConfig['pool'] = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            }

            $imageCaptchaConfig['word_length'] = $length;

            $this->kernel->add(new Captcha\ImageCaptcha($imageCaptchaConfig));
        }
    }

    /**
     * Set the messenger modules.
     *
     * @return void
     */
    protected function setMessengers(): void
    {
        // // The ID list of the messenger modules.
        $messengerList = [
            'telegram',
            'line_notify',
            'sendgrid',
            'native_php_mail',
            'smtp',
            'mailgun',
            'rocket_chat',
            'slack',
            'slack_webhook',
        ];

        foreach ($messengerList as $messenger) {
            $setting = $this->getOption($messenger, 'messengers');

            if (is_array($setting)) {

                // Initialize messenger instances from the factory/
                if (MessengerFactory::check($messenger, $setting)) {
    
                    $this->kernel->add(
                        MessengerFactory::getInstance(
                            // The ID of the messenger module in the configuration.
                            $messenger, 
                            // The settings of the messenger module in the configuration.
                            $setting    
                        )
                    );
                }
            }

            unset($setting);
        }
    }

    /**
     * Set message events.
     *
     * @return void
     */
    protected function setMessageEvents(): void
    {
        $setting = $this->getOption('failed_attempts_in_a_row', 'events');

        $notifyDataCircle = false;
        $notifySystemFirewall = false;

        if ($setting['data_circle']['messenger']) {
            $notifyDataCircle = true;
        }

        if ($setting['system_firewall']['messenger']) {
            $notifyDataCircle = true;
        }

        $this->kernel->setProperty('deny_attempt_notify', [
            'data_circle' => $notifyDataCircle,
            'system_firewall' => $notifySystemFirewall,
        ]);
    }

    /**
     * Set deny attempts.
     *
     * @return void
     */
    protected function setDenyAttempts(): void
    {
        $setting = $this->getOption('failed_attempts_in_a_row', 'events');

        $enableDataCircle = false;
        $enableSystemFirewall = false;

        if ($setting['data_circle']['enable']) {
            $enableDataCircle = true;
        }

        if ($setting['system_firewall']['enable']) {
            $enableSystemFirewall = true;
        }

        $this->kernel->setProperty('deny_attempt_enable', [
            'data_circle' => $enableDataCircle,
            'system_firewall' => $enableSystemFirewall,
        ]);

        $this->kernel->setProperty('deny_attempt_buffer', [
            'data_circle' => $setting['data_circle']['buffer'] ?? 10,
            'system_firewall' => $setting['data_circle']['buffer'] ?? 10,
        ]);

        // Check the time of the last failed attempt. @since 0.2.0
        $recordAttempt = $this->getOption('record_attempt');

        $detectionPeriod = $recordAttempt['detection_period'] ?? 5;
        $timeToReset = $recordAttempt['time_to_reset'] ?? 1800;

        $this->kernel->setProperty('record_attempt_detection_period', $detectionPeriod);
        $this->kernel->setProperty('reset_attempt_counter', $timeToReset);
    }

    /**
     * Set iptables working folder.
     *
     * @return void
     */
    protected function setIptablesWatchingFolder(): void
    {
        $iptablesSetting = $this->getOption('config', 'iptables');
        $this->kernel->setProperty('iptables_watching_folder',  $iptablesSetting['watching_folder']);
    }

    /**
     * Set the online session limit.
     *
     * @return void
     */
    protected function setSessionLimit(): void
    {
        $sessionLimitSetting = $this->getOption('online_session_limit');

        if ($sessionLimitSetting['enable']) {

            $onlineUsers = $sessionLimitSetting['config']['count'] ?? 100;
            $alivePeriod = $sessionLimitSetting['config']['period'] ?? 300;

            $this->kernel->limitSession($onlineUsers, $alivePeriod);
        }
    }

    /**
     * Set the cron job.
     * This is triggered by the pageviews, not system cron job.
     *
     * @return void
     */
    protected function setCronJob(): void 
    {
        if (!$this->status) {
            return;
        }

        $cronjobSetting = $this->getOption('reset_circle', 'cronjob');

        if ($cronjobSetting['enable']) {

            $nowTime = time();

            $lastResetTime = $cronjobSetting['config']['last_update'];

            if (!empty($lastResetTime) ) {
                $lastResetTime = strtotime($lastResetTime);
            } else {
                // @codeCoverageIgnoreStart
                $lastResetTime = strtotime(date('Y-m-d 00:00:00'));
                // @codeCoverageIgnoreEnd
            }

            if (($nowTime - $lastResetTime) > $cronjobSetting['config']['period']) {

                $updateResetTime = date('Y-m-d 00:00:00');

                // Update new reset time.
                $this->setConfig('cronjob.reset_circle.config.last_update', $updateResetTime);
                $this->updateConfig();

                // Remove all logs.
                $this->kernel->driver->rebuild();
            }
        }
    }

    /**
     * Set the URLs that want to be excluded from Shieldon protection.
     *
     * @return void
     */
    protected function setExcludedUrls(): void
    {
        $excludedUrls = $this->getOption('excluded_urls');

        if (!empty($excludedUrls)) {
            $list = array_column($excludedUrls, 'url');

            $this->kernel->setExcludedUrls($list);
        }
    }



    /**
     * WWW-Athentication.
     *
     * @return void
     */
    protected function setAuthentication(): void
    {
        $authenticateList = $this->getOption('www_authenticate');

        if (is_array($authenticateList)) {
            $this->add(new Middleware\httpAuthentication($authenticateList));
        }
    }

    /**
     * Apply the denied list and the allowed list to Ip Component.
     */
    protected function applyComponentIpManager()
    {
        $ipList = $this->getOption('ip_manager');

        $allowedList = [];
        $deniedList = [];

        if (!empty($ipList)) {
            foreach ($ipList as $ip) {

                if (0 === strpos($this->kernel->getCurrentUrl(), $ip['url']) ) {
    
                    if ('allow' === $ip['rule']) {
                        $allowedList[] = $ip['ip'];
                    }
    
                    if ('deny' === $ip['rule']) {
                        $deniedList[] = $ip['ip'];
                    }
                }
            }
        }

        if (!empty($allowedList)) {
            $this->kernel->component['Ip']->setAllowedItems($allowedList);
        }

        if (!empty($deniedList)) {
            $this->kernel->component['Ip']->setDeniedItems($deniedList);
        }
    }

    /**
     * Set dialog UI.
     *
     * @return void
     */
    protected function setDialogUI()
    {
        $ui = $this->getOption('dialog_ui');

        if (!empty($ui)) {
            get_session()->set('shieldon_ui_lang', $ui['lang']);
            $this->kernel->setDialogUI($this->getOption('dialog_ui'));
        }
    }

  
}
