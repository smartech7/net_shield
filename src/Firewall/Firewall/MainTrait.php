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

namespace Shieldon\Firewall\Firewall;

use Shieldon\Firewall\Firewall\Driver\DriverFactory;
use Shieldon\Firewall\Firewall\Captcha\CaptchaFactory;
use Shieldon\Firewall\Log\ActionLogger;
use Shieldon\Firewall\Middleware as Middleware;
use function Shieldon\Firewall\get_request;
use function Shieldon\Firewall\get_session;

use RuntimeException;
use function strpos;
use function time;

/*
 * Main Trait for Firwall class.
 */
trait MainTrait
{
    /**
     * Fetch value from configuration.
     *
     * @param string $option
     * @param string $section
     *
     * @return mixed
     */
    abstract function getOption(string $option, string $section = '');

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
            $this->kernel->setDriver($driverInstance);
            $this->status = true;
        }
    }

    /**
     * Filters
     *
     * (1) Session.
     * (2) Cookie generated by JavaScript code.
     * (3) HTTP referrer information.
     * (4) Pageview frequency.
     *
     * @return void
     */
    protected function setFilters(): void
    {
        $filters = [
            'session',
            'cookie',
            'referer',
            'frequency',
        ];

        foreach ($filters as $filter) {
            $setting = $this->getOption($filter, 'filters');
            $settings[$filter] = $setting;
            $filterConfig[$filter] = $setting['enable'];
            $filterLimit[$filter] = $setting['config']['quota']; // 5
            unset($setting);
        }
        unset($filterLimit['frequency']);

        $this->kernel->setFilters($filterConfig);
        $this->kernel->setProperty('limit_unusual_behavior', $filterLimit);

        $frequencyQuota = [
            's' => $settings['frequency']['config']['quota_s'],
            'm' => $settings['frequency']['config']['quota_m'],
            'h' => $settings['frequency']['config']['quota_h'],
            'd' => $settings['frequency']['config']['quota_d'],
        ];

        $this->kernel->setProperty('time_unit_quota', $frequencyQuota);

        $this->kernel->setProperty('cookie_name', $settings['cookie']['config']['cookie_name']);      // ssjd
        $this->kernel->setProperty('cookie_domain', $settings['cookie']['config']['cookie_domain']);  // ''
        $this->kernel->setProperty('cookie_value', $settings['cookie']['config']['cookie_value']);    // 1

        $this->kernel->setProperty('interval_check_referer', $settings['referer']['config']['time_buffer']);
        $this->kernel->setProperty('interval_check_session', $settings['referer']['config']['time_buffer']);
    }

    /**
     * Components
     * 
     * (1) Ip
     * (2) Rdns
     * (3) Header
     * (4) User-agent
     * (5) Trusted bot
     *
     * @return void
     */
    protected function setComponents(): void
    {
        $componentConfig = [
            'Ip'         => $this->getOption('ip', 'components'),
            'Rdns'       => $this->getOption('rdns', 'components'),
            'Header'     => $this->getOption('header', 'components'),
            'UserAgent'  => $this->getOption('user_agent', 'components'),
            'TrustedBot' => $this->getOption('trusted_bot', 'components'),
        ];

        foreach ($componentConfig as $className => $config) {
            $class = 'Shieldon\Firewall\Component\\' . $className;

            if ($config['enable']) {
                $componentInstance = new $class();
                $componentInstance->setStrict($config['strict_mode']);

                if ($className === 'Ip') {
                    // Need Ip component to be loaded before calling this method.
                    $this->applyComponentIpManager();
                }

                $this->kernel->setComponent($componentInstance);
            }
        }
    }

    /**
     * Captcha modules.
     * 
     * (1) Google ReCaptcha
     * (2) Simple image captcha.
     *
     * @return void
     */
    protected function setCaptchas(): void
    {
        $captchaList = [
            'recaptcha',
            'image',
        ];

        foreach ($captchaList as $captcha) {
            $setting = (array) $this->getOption($captcha, 'captcha_modules');

            // Initialize messenger instances from the factory/
            if (CaptchaFactory::check($captcha, $setting)) {

                $this->kernel->setCaptcha(
                    CaptchaFactory::getInstance(
                        // The ID of the captcha module in the configuration.
                        $captcha, 
                        // The settings of the captcha module in the configuration.
                        $setting    
                    )
                );
            }

            unset($setting);
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
                $this->kernel->setLogger(new ActionLogger($loggerSetting['config']['directory_path']));
            }
        }
    }

    /**
     * Apply the denied list and the allowed list to Ip Component.
     */
    protected function applyComponentIpManager()
    {
        $ipList = (array) $this->getOption('ip_manager');

        $allowedList = [];
        $deniedList = [];

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

        $this->kernel->component['Ip']->setAllowedItems($allowedList);
        $this->kernel->component['Ip']->setDeniedItems($deniedList);
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
     * Set deny attempts.
     *
     * @return void
     */
    protected function setDenyTooManyAttempts(): void
    {
        $setting = $this->getOption('failed_attempts_in_a_row', 'events');

        $this->kernel->setProperty('deny_attempt_enable', [
            'data_circle' => $setting['data_circle']['enable'],         // false   
            'system_firewall' => $setting['system_firewall']['enable'], // false   
        ]);

        $this->kernel->setProperty('deny_attempt_buffer', [
            'data_circle' => $setting['data_circle']['buffer'],         // 10
            'system_firewall' => $setting['system_firewall']['buffer'], // 10
        ]);

        // Check the time of the last failed attempt.
        $recordAttempt = $this->getOption('record_attempt');

        $this->kernel->setProperty('record_attempt_detection_period', $recordAttempt['detection_period']); // 5
        $this->kernel->setProperty('reset_attempt_counter', $recordAttempt['time_to_reset']);              // 1800
    }

    /**
     * Set iptables working folder.
     *
     * @return void
     */
    protected function setIptablesBridgeDirectory(): void
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

            $onlineUsers = $sessionLimitSetting['config']['count']  ?? 100;
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
    protected function setPageAuthentication(): void
    {
        $authenticateList = $this->getOption('www_authenticate');

        if (is_array($authenticateList)) {
            $this->add(new Middleware\httpAuthentication($authenticateList));
        }
    }

    /**
     * Set dialog UI.
     *
     * @return void
     */
    protected function setDialogUserInterface()
    {
        $ui = $this->getOption('dialog_ui');

        if (!empty($ui)) {
            get_session()->set('shieldon_ui_lang', $ui['lang']);
            $this->kernel->setDialog($this->getOption('dialog_ui'));
        }
    }
}
