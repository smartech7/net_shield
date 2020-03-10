<?php declare(strict_types=1);
/*
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shieldon;

use Shieldon\Firewall;
use Shieldon\Captcha\Foundation;
use Shieldon\Captcha\ImageCaptcha;
use Shieldon\Captcha\Recaptcha;
use Shieldon\Driver\FileDriver;
use Shieldon\Driver\MysqlDriver;
use Shieldon\Driver\RedisDriver;
use Shieldon\Driver\SqliteDriver;
use Shieldon\Log\ActionLogParser;
use Shieldon\Log\ActionLogParsedCache;
use Shieldon\Shieldon;
use Shieldon\FirewallTrait;
use Messenger as MessengerModule;

use function Shieldon\Helper\__;

use PDO;
use PDOException;
use Redis;
use RedisException;
use ReflectionObject;
use SplFileObject;
use function array_push;
use function array_values;
use function class_exists;
use function count;
use function date;
use function define;
use function defined;
use function explode;
use function extract;
use function file_exists;
use function file_put_contents;
use function filter_var;
use function gethostbyaddr;
use function header;
use function is_array;
use function is_dir;
use function is_numeric;
use function is_string;
use function is_writable;
use function mkdir;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function parse_url;
use function password_verify;
use function php_sapi_name;
use function round;
use function session_start;
use function session_status;
use function strtotime;
use function time;
use function umask;

/**
 * Increase PHP execution time. Becasue of taking long time to parse logs in a high-traffic site.
 */
set_time_limit(3600);

/**
 * Increase the memory limit. Becasue the log files may be large in a high-traffic site.
 */

ini_set('memory_limit', '128M');

/**
 * Firewall's Control Panel
 * 
 * Display a Control Panel UI for developers or administrators.
 *
 * @since 3.0.0
 */
class FirewallPanel
{
    use FirewallTrait;

    /**
     * LogPaeser instance.
     *
     * @var object
     */
    protected $parser;

    /**
     * Messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * self: Shieldon | managed: Firewall
     *
     * @var string
     */
    protected $mode = 'self';

    /**
     * Check Page availability.
     *
     * @var array
     */
    protected $pageAvailability = [
        'logs' => false,
    ];


    /**
     * see $this->csrf()
     *
     * @var array
     */
    protected $csrfField = [];

    /**
     * Login as a demo user.
     *
     * @var array
     */
    protected $demoUser = [
        'user' => 'demo',
        'pass' => '$2y$10$MTi1ROPnHEukp5RwGNdxuOSAyhGdpc4sfQpwNCv9yHoVvgl9tz8Xy',
    ];

    /**
     * Language code.
     *
     * @var string
     */
    public $locate = 'en';

    /**
     * Captcha modules.
     *
     * @var Interface
     */
    private $captcha = [];

    /**
     * Constructor.
     *
     * @param object $instance Shieldon | Firewall
     */
    public function __construct($instance) 
    {
        if ($instance instanceof Shieldon) {
            $this->mode = 'self';
            $this->shieldon = $instance;

        } elseif ($instance instanceof Firewall) {
            $this->mode          = 'managed';
            $this->shieldon      = $instance->getShieldon();
            $this->configuration = $instance->getConfiguration();
            $this->directory     = $instance->getDirectory();
            $this->filename      = $instance->getFilename();
        }

        if (! empty($this->shieldon->logger)) {

            // We need to know where the logs stored in.
            $logDirectory = $this->shieldon->logger->getDirectory();

            // Load ActionLogParser for parsing log files.
            $this->parser = new ActionLogParser($logDirectory);

            $this->pageAvailability['logs'] = true;
        }

        if ((php_sapi_name() !== 'cli')) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }

        // Flash message, use it when redirecting page.
        if (! empty($_SESSION['flash_messages'])) {
            $this->messages = $_SESSION['flash_messages'];
            unset($_SESSION['flash_messages']);
        }
    }

     // @codeCoverageIgnoreStart

    /**
     * Display pages.
     *
     * @param string $slug
     *
     * @return void
     */
    public function entry(): void
    {
        $this->locate = 'en';

        if (! empty($_SESSION['SHIELDON_PANEL_LANG'])) {
            $this->locate = $_SESSION['SHIELDON_PANEL_LANG'];
        }

        $slug = $_GET['so_page'] ?? '';

        if ('logout' === $slug) {

            if (isset($_SESSION['SHIELDON_USER_LOGIN'])) {
                unset($_SESSION['SHIELDON_USER_LOGIN']);
            }

            if (isset($_SESSION['SHIELDON_PANEL_LANG'])) {
                unset($_SESSION['SHIELDON_PANEL_LANG']);
            }

            header('Location: ' . $this->url('login'));
            exit;
        }

        $this->httpAuth();

        if (isset($_POST) && 'demo' === $this->mode) {
            unset($_POST);
        }

        switch($slug) {

            case 'overview':
                $this->overview();
                break;

            case 'operation_status':
                $this->operationStatus();
                break;

            case 'settings':
                $this->setting();
                break;

            case 'ip_manager':
                $this->ipManager();
                break;

            case 'exclusion':
                $this->exclusion();
                break;

            case 'authentication':
                $this->authentication();
                break;

            case 'xss_protection':
                $this->xssProtection();
                break;

            case 'session_table':
                $this->sessionTable();
                break;

            case 'ip_log_table':
                $this->ipLogTable();
                break;

            case 'ip_rule_table':
                $this->ruleTable();
                break;

            case 'action_log':
                $this->actionLog();
                break;

            case 'messenger':
                $this->messenger();
                break;

            case 'iptables':
                $this->iptables('IPv4');
                break;

            case 'iptables_status':
                $this->iptablesStatus('IPv4');
                break;

            case 'ip6tables':
                $this->iptables('IPv6');
                break;

            case 'ip6tables_status':
                $this->iptablesStatus('IPv6');
                break;

            case 'ajax_change_locale':
                $this->ajaxChangeLocale();
                break;

            case 'ajax_test_messenger_modules':
                $this->ajaxTestMessengerModules();
                break;

            case 'login':
                $this->login();
                break;

            case 'export_settings':
                $this->exportSettings();
                break;

            case 'import_settings':
                $this->importSettings();
                break;

            default:
                header('Location: ' . $this->url('login'));
                break;
        }

        if (isset($_GET)) unset($_GET);
        if (isset($_POST)) unset($_POST);
    }

    /**
     * Most popular PHP framework has a built-in CSRF protection such as Laravel.
     * We need to pass the CSRF token for our form actions.
     *
     * @param string|array $csrfparams
     *
     * @return void
     */
    public function csrf(...$csrfparams): void
    {
        if (1 === count($csrfparams)) {

            foreach ($csrfparams as $key => $value) {

                $this->csrfField[] = [
                    'name' => $key,
                    'value' => $value,
                ];
            }

        } elseif (2 === count($csrfparams)) {

            if (! empty($csrfparams[0]) && is_string($csrfparams[0])) {
                $csrfKey = $csrfparams[0];
            }
    
            if (! empty($csrfparams[1]) && is_string($csrfparams[1])) {
                $csrfValue = $csrfparams[1];
            }

            if (! empty($csrfKey)) {
                $this->csrfField[] = [
                    'name' => $csrfKey,
                    'value' => $csrfValue,
                ];
            }
        }
    }

    /**
     * Output HTML input element with CSRF token.
     *
     * @return void
     */
    public function _csrf(): void
    {
        if (! empty($this->csrfField)) {
            foreach ($this->csrfField as $value) {
                echo '<input type="hidden" name="' . $value['name'] . '" value="' . $value['value'] . '" id="csrf-field">';
            }
        }
    }

    /**
     * In demo mode, user's submitting action will not take any effect.
     *
     * @param string $user
     * @param string $pass
     *
     * @return void
     */
    public function demo(string $user = '', string $pass = ''): void
    {
        if (! empty($user)) {
            $this->demoUser['user'] = $user;
        }

        if (! empty($pass)) {
            $this->demoUser['pass'] = $pass;
        }

        $this->mode = 'demo';
    }

    /**
     * Setting page.
     *
     * @return void
     */
    protected function setting(): void
    {
        $data[] = [];

        if (isset($_POST['tab'])) {
            unset($_POST['tab']);
            $this->saveConfig();
        }

        $this->renderPage('panel/setting', $data);
    }

    /**
     * Login reminder
     *
     * @return void
     */
    protected function login(): void
    {
        $this->applyCaptchaForms();

        $login = false;
        $data['error'] = '';

        if (isset($_POST['s_user']) && isset($_POST['s_pass'])) {

            $admin = $this->getConfig('admin');

            if (
                // Default password, unencrypted.
                $admin['user']  === $_POST['s_user'] && 
                'shieldon_pass' === $_POST['s_pass'] &&
                'shieldon_pass' === $admin['pass']
            ) {
                $login = true;

            } elseif (
                // User has already changed password, encrypted.
                $admin['user'] === $_POST['s_user'] && 
                password_verify($_POST['s_pass'], $admin['pass'])
            ) {
                $login = true;
    
            } else {
                $data['error'] = __('panel', 'login_message_invalid_user_or_pass', 'Invalid username or password.');
            }

            // Check the response from Captcha modules.
            foreach ($this->captcha as $captcha) {
                if (! $captcha->response()) {
                    $login = false;
                    $data['error'] = __('panel', 'login_message_invalid_captcha', 'Invalid Captcha code.');
                }
            }
        }

        if ($login) {
            // Mark as logged user.
            $_SESSION['SHIELDON_USER_LOGIN'] = true;

            // Redirect to overview page if logged in successfully.
            header('Location: ' . $this->url('overview'));
        }

        // Start to prompt a login form is not logged.
        define('SHIELDON_VIEW', true);

        // `$ui` will be used in `css-default.php`. Do not remove it.
        $ui = [
            'background_image' => '',
            'bg_color'         => '#ffffff',
            'header_bg_color'  => '#212531',
            'header_color'     => '#ffffff',
            'shadow_opacity'   => '0.2',
        ];

        $data['css'] = require $this->shieldon::SHIELDON_DIR . '/../../templates/frontend/css/default.php';
        unset($ui);

        $this->loadView('frontend/login', $data, true);
    }

    /**
     * Shieldon operating information.
     *
     * @return void
     */
    protected function overview(): void
    {
        if (isset($_POST['action_type'])) {

            switch ($_POST['action_type']) {

                case 'reset_data_circle':
                    $this->setConfig('cronjob.reset_circle.config.last_update', date('Y-m-d H:i:s'));
                    $this->shieldon->driver->rebuild();
                    sleep(2);

                    unset($_POST['action_type']);

                    $this->saveConfig();

                    $this->responseMessage('success',
                        __(
                            'panel',
                            'reset_data_circle',
                            'Data circle tables have been reset.'
                        )
                    );
                    break;

                case 'reset_action_logs':
                    $this->shieldon->logger->purgeLogs();
                    sleep(2);

                    $this->responseMessage('success',
                        __(
                            'panel',
                            'reset_action_logs',
                            'Action logs have been removed.'
                        )
                    );
                    break;

                default:
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Logger
        |--------------------------------------------------------------------------
        |
        | All logs were recorded by ActionLogger.
        | Get the summary information from those logs.
        |
        */

        $data['action_logger'] = false;

        if (! empty($this->shieldon->logger)) {
            $loggerInfo = $this->shieldon->logger->getCurrentLoggerInfo();
            $data['action_logger'] = true;
        }

        $data['logger_started_working_date'] = 'No record';
        $data['logger_work_days'] = '0 day';
        $data['logger_total_size'] = '0 MB';

        if (! empty($loggerInfo)) {

            $i = 0;
            ksort($loggerInfo);

            foreach ($loggerInfo as $filename => $size) {
                $filename = (string) $filename;
                if (false === strpos($filename, '.json')) {
                    if (0 === $i) {
                        $data['logger_started_working_date'] = date('Y-m-d', strtotime($filename));
                    }
                    $i += (int) $size;
                }
            }

            $data['logger_work_days'] = count($loggerInfo);
            $data['logger_total_size'] = round($i / (1024 * 1024), 5) . ' MB';
        }

        /*
        |--------------------------------------------------------------------------
        | Data circle
        |--------------------------------------------------------------------------
        |
        | A data circle includes the primary data tables of Shieldon.
        | They are ip_log_table, ip_rule_table and session_table.
        |
        */

        // Data circle.
        $data['rule_list'] = $this->shieldon->driver->getAll('rule');
        $data['ip_log_list'] = $this->shieldon->driver->getAll('filter_log');
        $data['session_list'] = $this->shieldon->driver->getAll('session');

        /*
        |--------------------------------------------------------------------------
        | Shieldon status
        |--------------------------------------------------------------------------
        |
        | 1. Components.
        | 2. Filters.
        | 3. Configuration.
        | 4. Captcha modules.
        | 5. Messenger modules.
        |
        */

        $data['components'] = [
            'Ip'         => (! empty($this->shieldon->component['Ip']))         ? true : false,
            'TrustedBot' => (! empty($this->shieldon->component['TrustedBot'])) ? true : false,
            'Header'     => (! empty($this->shieldon->component['Header']))     ? true : false,
            'Rdns'       => (! empty($this->shieldon->component['Rdns']))       ? true : false,
            'UserAgent'  => (! empty($this->shieldon->component['UserAgent']))  ? true : false,
        ];

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('enableCookieCheck');
        $t->setAccessible(true);
        $enableCookieCheck = $t->getValue($this->shieldon);

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('enableSessionCheck');
        $t->setAccessible(true);
        $enableSessionCheck = $t->getValue($this->shieldon);

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('enableFrequencyCheck');
        $t->setAccessible(true);
        $enableFrequencyCheck = $t->getValue($this->shieldon);

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('enableRefererCheck');
        $t->setAccessible(true);
        $enableRefererCheck = $t->getValue($this->shieldon);

        $data['filters'] = [
            'cookie'    => $enableCookieCheck,
            'session'   => $enableSessionCheck,
            'frequency' => $enableFrequencyCheck,
            'referer'   => $enableRefererCheck,
        ];

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('properties');
        $t->setAccessible(true);
        $properties = $t->getValue($this->shieldon);
        
        $data['configuration'] = $properties;

        $data['driver'] = [
            'mysql'  => ($this->shieldon->driver instanceof MysqlDriver),
            'redis'  => ($this->shieldon->driver instanceof RedisDriver),
            'file'   => ($this->shieldon->driver instanceof FileDriver),
            'sqlite' => ($this->shieldon->driver instanceof SqliteDriver),
        ];

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('captcha');
        $t->setAccessible(true);
        $captcha = $t->getValue($this->shieldon);

        $data['captcha'] = [
            'recaptcha'    => (isset($captcha['Recaptcha']) ? true : false),
            'imagecaptcha' => (isset($captcha['ImageCaptcha']) ? true : false),
        ];

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('messengers');
        $t->setAccessible(true);
        $messengers = $t->getValue($this->shieldon);

        $operatingMessengers = [
            'telegram'     => false,
            'linenotify'   => false,
            'sendgrid'     => false,
            'mailgun'      => false,
            'smtp'         => false,
            'slack'        => false,
            'slackwebhook' => false,
            'rocketchat'   => false,
            'mail'         => false,
        ];

        foreach ($messengers as $messenger) {
            $class = get_class($messenger);
            $class = strtolower(substr($class, strrpos($class, '\\') + 1));

            if (isset($operatingMessengers[$class])) {
                $operatingMessengers[$class] = true;
            }
        }

        $data['messengers'] = $operatingMessengers;

        $this->renderPage('panel/overview', $data);
    }

    /**
     * Operation status.
     *
     * @return void
     */
    protected function operationStatus(): void
    {
        $data['components'] = [
            'Ip'         => (! empty($this->shieldon->component['Ip']))         ? true : false,
            'TrustedBot' => (! empty($this->shieldon->component['TrustedBot'])) ? true : false,
            'Header'     => (! empty($this->shieldon->component['Header']))     ? true : false,
            'Rdns'       => (! empty($this->shieldon->component['Rdns']))       ? true : false,
            'UserAgent'  => (! empty($this->shieldon->component['UserAgent']))  ? true : false,
        ];

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('enableCookieCheck');
        $t->setAccessible(true);
        $enableCookieCheck = $t->getValue($this->shieldon);

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('enableSessionCheck');
        $t->setAccessible(true);
        $enableSessionCheck = $t->getValue($this->shieldon);

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('enableFrequencyCheck');
        $t->setAccessible(true);
        $enableFrequencyCheck = $t->getValue($this->shieldon);

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('enableRefererCheck');
        $t->setAccessible(true);
        $enableRefererCheck = $t->getValue($this->shieldon);

        $data['filters'] = [
            'cookie'    => $enableCookieCheck,
            'session'   => $enableSessionCheck,
            'frequency' => $enableFrequencyCheck,
            'referer'   => $enableRefererCheck,
        ];

        $ruleList = $this->shieldon->driver->getAll('rule');

        $data['component_ip'] = 0;
        $data['component_trustedbot'] = 0;
        $data['component_rdns'] = 0;
        $data['component_header'] = 0;
        $data['component_useragent'] = 0;

        $data['filter_frequency'] = 0;
        $data['filter_referer'] = 0;
        $data['filter_cookie'] = 0;
        $data['filter_session'] = 0;

        // Components.
        $data['rule_list']['ip'] = [];
        $data['rule_list']['trustedbot'] = [];
        $data['rule_list']['rdns'] = [];
        $data['rule_list']['header'] = [];
        $data['rule_list']['useragent'] = [];

        // Filters.
        $data['rule_list']['frequency'] = [];
        $data['rule_list']['referer'] = [];
        $data['rule_list']['cookie'] = [];
        $data['rule_list']['session'] = [];

        foreach ($ruleList as $ruleInfo) {
    
            switch ($ruleInfo['reason']) {
                case $this->shieldon::REASON_DENY_IP:
                case $this->shieldon::REASON_COMPONENT_IP:
                    $data['component_ip']++;
                    $data['rule_list']['ip'][] = $ruleInfo;
                    break;

                case $this->shieldon::REASON_COMPONENT_RDNS:
                    $data['component_rdns']++;
                    $data['rule_list']['rdns'][] = $ruleInfo;
                    break;
                
                case $this->shieldon::REASON_COMPONENT_HEADER:
                    $data['component_header']++;
                    $data['rule_list']['header'][] = $ruleInfo;
                    break;

                case $this->shieldon::REASON_COMPONENT_USERAGENT:
                    $data['component_useragent']++;
                    $data['rule_list']['useragent'][] = $ruleInfo;
                    break;

                case $this->shieldon::REASON_COMPONENT_TRUSTED_ROBOT:
                    $data['component_trustedbot']++;
                    $data['rule_list']['trustedbot'][] = $ruleInfo;
                    break;

                case $this->shieldon::REASON_TOO_MANY_ACCESSES:
                case $this->shieldon::REASON_REACHED_LIMIT_DAY:
                case $this->shieldon::REASON_REACHED_LIMIT_HOUR:
                case $this->shieldon::REASON_REACHED_LIMIT_MINUTE:
                case $this->shieldon::REASON_REACHED_LIMIT_SECOND:
                    $data['filter_frequency']++;
                    $data['rule_list']['frequency'][] = $ruleInfo;
                    break;

                case $this->shieldon::REASON_EMPTY_REFERER:
                    $data['filter_referer']++;
                    $data['rule_list']['referer'][] = $ruleInfo;
                    break;

                case $this->shieldon::REASON_EMPTY_JS_COOKIE:
                    $data['filter_cookie']++;
                    $data['rule_list']['cookie'][] = $ruleInfo;
                    break;

                case $this->shieldon::REASON_TOO_MANY_SESSIONS:
                    $data['filter_session']++;
                    $data['rule_list']['session'][] = $ruleInfo;
                    break;
            }
        }

        $reasons = [
            $this->shieldon::REASON_MANUAL_BAN           => __('panel', 'reason_manual_ban', 'Added manually by administrator'),
            $this->shieldon::REASON_IS_SEARCH_ENGINE     => __('panel', 'reason_is_search_engine', 'Search engine bot'),
            $this->shieldon::REASON_IS_GOOGLE            => __('panel', 'reason_is_google', 'Google bot'),
            $this->shieldon::REASON_IS_BING              => __('panel', 'reason_is_bing', 'Bing bot'),
            $this->shieldon::REASON_IS_YAHOO             => __('panel', 'reason_is_yahoo', 'Yahoo bot'),
            $this->shieldon::REASON_TOO_MANY_SESSIONS    => __('panel', 'reason_too_many_sessions', 'Too many sessions'),
            $this->shieldon::REASON_TOO_MANY_ACCESSES    => __('panel', 'reason_too_many_accesses', 'Too many accesses'),
            $this->shieldon::REASON_EMPTY_JS_COOKIE      => __('panel', 'reason_empty_js_cookie', 'Cannot create JS cookies'),
            $this->shieldon::REASON_EMPTY_REFERER        => __('panel', 'reason_empty_referer', 'Empty referrer'),
            $this->shieldon::REASON_REACHED_LIMIT_DAY    => __('panel', 'reason_reached_limit_day', 'Daily limit reached'),
            $this->shieldon::REASON_REACHED_LIMIT_HOUR   => __('panel', 'reason_reached_limit_hour', 'Hourly limit reached'),
            $this->shieldon::REASON_REACHED_LIMIT_MINUTE => __('panel', 'reason_reached_limit_minute', 'Minutely limit reached'),
            $this->shieldon::REASON_REACHED_LIMIT_SECOND => __('panel', 'reason_reached_limit_second', 'Secondly limit reached'),

            // @since 0.1.8
            $this->shieldon::REASON_INVALID_IP              => __('panel', 'reason_invalid_ip', 'Invalid IP address.'),
            $this->shieldon::REASON_DENY_IP                 => __('panel', 'reason_deny_ip', 'Denied by IP component.'),
            $this->shieldon::REASON_ALLOW_IP                => __('panel', 'reason_allow_ip', 'Allowed by IP component.'),
            $this->shieldon::REASON_COMPONENT_IP            => __('panel', 'reason_component_ip', 'Denied by IP component.'),
            $this->shieldon::REASON_COMPONENT_RDNS          => __('panel', 'reason_component_rdns', 'Denied by RDNS component.'),
            $this->shieldon::REASON_COMPONENT_HEADER        => __('panel', 'reason_component_header', 'Denied by Header component.'),
            $this->shieldon::REASON_COMPONENT_USERAGENT     => __('panel', 'reason_component_useragent', 'Denied by User-agent component.'),
            $this->shieldon::REASON_COMPONENT_TRUSTED_ROBOT => __('panel', 'reason_component_trusted_robot', 'Identified as fake search engine.'),
        ];

        $types = [
            $this->shieldon::ACTION_DENY             => 'DENY',
            $this->shieldon::ACTION_ALLOW            => 'ALLOW',
            $this->shieldon::ACTION_TEMPORARILY_DENY => 'CAPTCHA',
        ];

        $data['reason_mapping'] = $reasons;
        $data['type_mapping'] = $types;

        $this->renderPage('panel/operation_status', $data);
    }

    /**
     * IP manager.
     *
     * @return void
     */
    protected function ipManager(): void
    {
        if (isset($_POST['ip']) && filter_var(explode('/', $_POST['ip'])[0], FILTER_VALIDATE_IP)) {

            $url = $_POST['url'];
            $ip = $_POST['ip'];
            $rule = $_POST['action'];
            $order = (int) $_POST['order'];

            if ($order > 0) {
                $order--;
            }

            $ipList = $this->getConfig('ip_manager');

            if ('allow' === $rule || 'deny' === $rule) {

                $newIpList = [];

                if (! empty($ipList)) {
                    foreach ($ipList as $i => $ipInfo) {
                        $key = $i + 1;
                        if ($order === $i) {
                            $newIpList[$key] = $ipInfo;

                            $newIpList[$i]['url'] = $url;
                            $newIpList[$i]['ip'] = $ip;
                            $newIpList[$i]['rule'] = $rule;
                        } else {
                            $newIpList[$key] = $ipInfo;
                        }
                    }
                } else {
                    $newIpList[0]['url'] = $url;
                    $newIpList[0]['ip'] = $ip;
                    $newIpList[0]['rule'] = $rule;
                }

                $newIpList = array_values($newIpList);

                $this->setConfig('ip_manager', $newIpList);

            } elseif ('remove' === $rule) {
                unset($ipList[$order]);
                $ipList = array_values($ipList);
                $this->setConfig('ip_manager', $ipList);
            }

            if (isset($_POST['url']))    unset($_POST['url']);
            if (isset($_POST['ip']))     unset($_POST['ip']);
            if (isset($_POST['action'])) unset($_POST['action']);
            if (isset($_POST['order']))  unset($_POST['order']);

            $this->saveConfig();
        }

        $data['ip_list'] = $this->getConfig('ip_manager');

        $this->renderPage('panel/ip_manager', $data);
    }

    /**
     * Exclude the URLs that they don't need protection.
     *
     * @return void
     */
    protected function exclusion(): void
    {
        if (isset($_POST['url'])) {

            $url = $_POST['url'] ?? '';
            $action = $_POST['action'] ?? '';
            $order = (int) $_POST['order'];

            $excludedUrls = $this->getConfig('excluded_urls');

            if ('add' === $action) {
                array_push($excludedUrls, [
                    'url' => $url
                ]);

            } elseif ('remove' === $action) {
                unset($excludedUrls[$order]);

                $excludedUrls = array_values($excludedUrls);
            }

            $this->setConfig('excluded_urls', $excludedUrls);

            if (isset($_POST['url']))    unset($_POST['url']);
            if (isset($_POST['action'])) unset($_POST['action']);
            if (isset($_POST['order']))  unset($_POST['order']);

            $this->saveConfig();
        }

        $data['exclusion_list'] = $this->getConfig('excluded_urls');

        $this->renderPage('panel/exclusion', $data);
    }

    /**
     * WWW-Authenticate.
     *
     * @return void
     */
    protected function authentication(): void
    {
        if (isset($_POST['url']) && isset($_POST['user']) && isset($_POST['pass'])) {

            $url = $_POST['url'] ?? '';
            $user = $_POST['user'] ?? '';
            $pass = $_POST['pass'] ?? '';
            $action = $_POST['action'] ?? '';
            $order = (int) $_POST['order'];

            $authenticatedList = $this->getConfig('www_authenticate');

            if ('add' === $action) {
                array_push($authenticatedList, [
                    'url' => $url,
                    'user' => $user,
                    'pass' => password_hash($pass, PASSWORD_BCRYPT),
                ]);

            } elseif ('remove' === $action) {
                unset($authenticatedList[$order]);
                $authenticatedList = array_values($authenticatedList);
            }

            $this->setConfig('www_authenticate', $authenticatedList);

            if (isset($_POST['url']))    unset($_POST['url']);
            if (isset($_POST['user']))   unset($_POST['user']);
            if (isset($_POST['pass']))   unset($_POST['pass']);
            if (isset($_POST['action'])) unset($_POST['action']);
            if (isset($_POST['order']))  unset($_POST['order']);

            $this->saveConfig();
        }

        $data['authentication_list'] = $this->getConfig('www_authenticate');

        $this->renderPage('panel/authentication', $data);
    }

    /**
     * XSS Protection.
     *
     * @return void
     */
    protected function xssProtection(): void
    {
        if (isset($_POST['xss'])) {
            unset($_POST['xss']);

            $type = $_POST['type'] ?? '';
            $variable = $_POST['variable'] ?? '';
            $action = $_POST['action'] ?? '';
            $order = (int) $_POST['order'];

            // Check variable name. Should be mixed with a-zA-Z and underscore.
            if (! ctype_alnum(str_replace('_', '', $variable))) {

                // Ignore the `add` process.
                $action = 'undefined';
            }

            $xssProtectedList = $this->getConfig('xss_protected_list');

            if (empty($xssProtectedList)) {
                $xssProtectedList = [];
            }

            if ('add' === $action) {

                switch ($type) {
                    case 'post':
                    case 'get':
                    case 'cookie':
                        array_push($xssProtectedList, ['type' => $type, 'variable' => $variable]);
                        break;

                    default:
                    // endswitch.
                }

            } elseif ('remove' === $xssProtectedList) {
                unset($xssProtectedList[$order]);
                $xssProtectedList = array_values($xssProtectedList);
            }

            $this->setConfig('xss_protected_list', $xssProtectedList);

            if (isset($_POST['type']))     unset($_POST['type']);
            if (isset($_POST['variable'])) unset($_POST['variable']);
            if (isset($_POST['action']))   unset($_POST['action']);
            if (isset($_POST['order']))    unset($_POST['order']);

            $this->saveConfig();
        }

        $data['xss_protected_list'] = $this->getConfig('xss_protected_list');

        $this->renderPage('panel/xss_protection', $data);
    }

    /**
     * Dsiplay action logs.
     *
     * @return void
     */
    protected function actionLog(): void
    {
        $tab = $_GET['tab'] ?? 'today';

        switch ($tab) {
            case 'yesterday':
            case 'this_month':
            case 'last_month':
            case 'past_seven_days':
            case 'today':
                $type = $tab;
                break;

            default:
                $type = 'today';
        }

        $data['ip_details'] = [];
        $data['period_data'] = [];
        
        $lastCachedTime = '';

        if (! empty($this->parser)) {

            $logCacheHandler = new ActionLogParsedCache($this->parser->getDirectory());

            $ipDetailsCachedData = $logCacheHandler->get($type);

            // If we have cached data then we don't need to parse them again.
            // This will save a lot of time in parsing logs.
            if (! empty($ipDetailsCachedData)) {

                $data['ip_details'] = $ipDetailsCachedData['ip_details'];
                $data['period_data'] = $ipDetailsCachedData['period_data'];
                $lastCachedTime = date('Y-m-d H:i:s', $ipDetailsCachedData['time']);
    
                if ('today' === $type ) {
                    $ipDetailsCachedData = $logCacheHandler->get('past_seven_hours');
                    $data['past_seven_hours'] = $ipDetailsCachedData['period_data'];
                }

            } else {

                $this->parser->prepare($type);

                $data['ip_details'] = $this->parser->getIpData();
                $data['period_data'] = $this->parser->getParsedPeriodData();

                $logCacheHandler->save($type, $data);
    
                if ('today' === $type ) {
                    $this->parser->prepare('past_seven_hours');
                    $data['past_seven_hours'] = $this->parser->getParsedPeriodData();

                    $logCacheHandler->save('past_seven_hours', [
                        'period_data' => $data['past_seven_hours']
                    ]);
                }
            }
        }

        $data['page_availability'] = $this->pageAvailability['logs'];
        $data['last_cached_time'] = $lastCachedTime;

        $data['page_url'] = $this->url('action_log');

        $this->renderPage('panel/action_log_' . $type, $data);
    }

    /**
     * Rule table for current cycle.
     *
     * @param string
     *
     * @return void
     */
    protected function ruleTable(): void
    {
        if (isset($_POST['ip'])) {

            $ip = $_POST['ip'];
            $action = $_POST['action'];

            $actionCode['temporarily_ban'] = $this->shieldon::ACTION_TEMPORARILY_DENY;
            $actionCode['permanently_ban'] = $this->shieldon::ACTION_DENY;
            $actionCode['allow'] = $this->shieldon::ACTION_ALLOW;

            switch ($action) {
                case 'temporarily_ban':
                case 'permanently_ban':
                case 'allow':
                    $logData['log_ip'] = $ip;
                    $logData['ip_resolve'] = gethostbyaddr($ip);
                    $logData['time'] = time();
                    $logData['type'] = $actionCode[$action];
                    $logData['reason'] = $this->shieldon::REASON_MANUAL_BAN;

                    $this->shieldon->driver->save($ip, $logData, 'rule');
                    break;

                case 'remove':
                    $this->shieldon->driver->delete($ip, 'rule');
                    break;
            }
        }

        $reasons = [
            $this->shieldon::REASON_MANUAL_BAN           => __('panel', 'reason_manual_ban', 'Added manually by administrator'),
            $this->shieldon::REASON_IS_SEARCH_ENGINE     => __('panel', 'reason_is_search_engine', 'Search engine bot'),
            $this->shieldon::REASON_IS_GOOGLE            => __('panel', 'reason_is_google', 'Google bot'),
            $this->shieldon::REASON_IS_BING              => __('panel', 'reason_is_bing', 'Bing bot'),
            $this->shieldon::REASON_IS_YAHOO             => __('panel', 'reason_is_yahoo', 'Yahoo bot'),
            $this->shieldon::REASON_TOO_MANY_SESSIONS    => __('panel', 'reason_too_many_sessions', 'Too many sessions'),
            $this->shieldon::REASON_TOO_MANY_ACCESSES    => __('panel', 'reason_too_many_accesses', 'Too many accesses'),
            $this->shieldon::REASON_EMPTY_JS_COOKIE      => __('panel', 'reason_empty_js_cookie', 'Cannot create JS cookies'),
            $this->shieldon::REASON_EMPTY_REFERER        => __('panel', 'reason_empty_referer', 'Empty referrer'),
            $this->shieldon::REASON_REACHED_LIMIT_DAY    => __('panel', 'reason_reached_limit_day', 'Daily limit reached'),
            $this->shieldon::REASON_REACHED_LIMIT_HOUR   => __('panel', 'reason_reached_limit_hour', 'Hourly limit reached'),
            $this->shieldon::REASON_REACHED_LIMIT_MINUTE => __('panel', 'reason_reached_limit_minute', 'Minutely limit reached'),
            $this->shieldon::REASON_REACHED_LIMIT_SECOND => __('panel', 'reason_reached_limit_second', 'Secondly limit reached'),

            // @since 0.1.8
            $this->shieldon::REASON_INVALID_IP              => __('panel', 'reason_invalid_ip', 'Invalid IP address.'),
            $this->shieldon::REASON_DENY_IP                 => __('panel', 'reason_deny_ip', 'Denied by IP component.'),
            $this->shieldon::REASON_ALLOW_IP                => __('panel', 'reason_allow_ip', 'Allowed by IP component.'),
            $this->shieldon::REASON_COMPONENT_IP            => __('panel', 'reason_component_ip', 'Denied by IP component.'),
            $this->shieldon::REASON_COMPONENT_RDNS          => __('panel', 'reason_component_rdns', 'Denied by RDNS component.'),
            $this->shieldon::REASON_COMPONENT_HEADER        => __('panel', 'reason_component_header', 'Denied by Header component.'),
            $this->shieldon::REASON_COMPONENT_USERAGENT     => __('panel', 'reason_component_useragent', 'Denied by User-agent component.'),
            $this->shieldon::REASON_COMPONENT_TRUSTED_ROBOT => __('panel', 'reason_component_trusted_robot', 'Identified as fake search engine.'),
        ];

        $types = [
            $this->shieldon::ACTION_DENY             => 'DENY',
            $this->shieldon::ACTION_ALLOW            => 'ALLOW',
            $this->shieldon::ACTION_TEMPORARILY_DENY => 'CAPTCHA',
        ];

        $data['rule_list'] = $this->shieldon->driver->getAll('rule');

        $data['reason_mapping'] = $reasons;
        $data['type_mapping'] = $types;

        $this->renderPage('panel/table_rules', $data);
    }

    /**
     * IP log table for current cycle.
     *
     * @param string
     *
     * @return void
     */
    protected function ipLogTable(): void
    {
        $data['ip_log_list'] = $this->shieldon->driver->getAll('filter_log');

        $this->renderPage('panel/table_filter_logs', $data);
    }

    /**
     * Session table for current cycle.
     *
     * @param string
     *
     * @return void
     */
    protected function sessionTable(): void
    {
        $data['session_list'] = $this->shieldon->driver->getAll('session');

        $data['is_session_limit'] = false;
        $data['session_limit_count'] = 0;
        $data['session_limit_period'] = 0;
        $data['online_count'] = 0;
        $data['expires'] = 0;

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('isLimitSession');
        $t->setAccessible(true);
        $isLimitSession = $t->getValue($this->shieldon);

        $data['is_session_limit'] = (empty($isLimitSession) ? false : true);
        $data['session_limit_count'] = ($isLimitSession[0] ?? 0);
        $data['session_limit_period'] = round(($isLimitSession[1] ?? 0) / 60, 0);
        $data['online_count'] = count($data['session_list']);
        $data['expires'] = (int) $data['session_limit_period'] * 60;

        $this->renderPage('panel/table_sessions', $data);
    }

    /**
     * Messenger setting page.
     *
     * @return void
     */
    protected function messenger(): void
    {
        $data[] = [];

        if (isset($_POST['tab'])) {
            unset($_POST['tab']);
            $this->saveConfig();
        }

        $this->renderPage('panel/messenger', $data);
    }

    /**
     * System layer firwall - iptables
     *
     * @return void
     */
    protected function iptables(string $type = 'IPv4'): void
    {
        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('properties');
        $t->setAccessible(true);
        $properties = $t->getValue($this->shieldon);

        $iptablesWatchingFolder = $properties['iptables_watching_folder'];

        // The iptables log files.
        $ipCommandFile = $iptablesWatchingFolder . '/ipv4_command.log';

        if ('IPv6' === $type) {
            $ipCommandFile = $iptablesWatchingFolder . '/ipv6_command.log';
        }

        $iptablesQueueFile = $iptablesWatchingFolder . '/iptables_queue.log';

        if (
               (isset($_POST['ip'])       && (filter_var(explode('/', $_POST['ip'])[0], FILTER_VALIDATE_IP)))
            && (isset($_POST['port'])     && (is_numeric($_POST['port']) || ($_POST['port'] === 'all') || ($_POST['port'] === 'custom')))
            && (isset($_POST['subnet'])   && (is_numeric($_POST['subnet']) || ($_POST['subnet'] === 'null')))
            && (isset($_POST['protocol']) && (in_array($_POST['protocol'], ['tcp', 'udp', 'all'])))
            && (isset($_POST['action'])   && (in_array($_POST['action'], ['allow', 'deny'])))
        ) {
            $ip       = $_POST['ip'];
            $port     = $_POST['port'];
            $subnet   = $_POST['subnet'];
            $protocol = $_POST['protocol'];
            $action   = $_POST['action'];
            $cPort    = $_POST['port_custom'] ?? 'all';

            $isRemoval = false;

            if (isset($_POST['remove']) && $_POST['remove'] === 'yes') {
                $isRemoval = true;
            }

            if ('custom' === $port) {
                $port = $cPort;
            }

            $ipv = '4';

            if ('IPv6' === $type) {
                $ipv = '6';
            }

            $applyCommand = "add,$ipv,$ip,$subnet,$port,$protocol,$action";

            if ($isRemoval) {
                $originCommandString = "add,$ipv,$ip,$subnet,$port,$protocol,$action";

                // Delete line from the log file.
                $fileArr = file($ipCommandFile);
                unset($fileArr[array_search(trim($originCommandString), $fileArr)]);

                $t = [];
                $i = 0;
                foreach ($fileArr as $f) {
                    $t[$i] = trim($f);
                    $i++;
                }
                file_put_contents($ipCommandFile, implode(PHP_EOL, $t));

                $applyCommand = "delete,$ipv,$ip,$subnet,$port,$protocol,$action";
            }

            // Add a command to the watching file.
            file_put_contents($iptablesQueueFile, $applyCommand . "\n", FILE_APPEND | LOCK_EX);

            if (! $isRemoval) {

                // Becase we need system cronjob done, and then the web page will show the actual results.
                sleep(10);
            } else {
                sleep(1);
            }
        }

        $data[] = [];

        $ipCommand = '';

        if (file_exists($ipCommandFile)) {
            $file = new SplFileObject($ipCommandFile);

            $ipCommand = [];

            while (!$file->eof()) {
                $line = trim($file->fgets());
                $ipInfo = explode(',', $line);

                if (! empty($ipInfo[4])) {
                    $ipCommand[] = $ipInfo;
                }
            }
        }

        $data['ipCommand'] = $ipCommand;
        $data['type'] = $type;

        $this->renderPage('panel/iptables_manager', $data);
    }

    /**
     * System layer firwall - iptables Status
     * iptables -L
     *
     * @return void
     */
    protected function iptablesStatus(string $type = 'IPv4'): void
    {
        $data[] = [];

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('properties');
        $t->setAccessible(true);
        $properties = $t->getValue($this->shieldon);

        $iptablesWatchingFolder = $properties['iptables_watching_folder'];

        // The iptables log files.
        $ipStatusFile = $iptablesWatchingFolder . '/ipv4_status.log';

        if ('IPv6' === $type) {
            $ipStatusFile = $iptablesWatchingFolder . '/ipv6_status.log';
        }
        
        $ipStatus = '';

        if (file_exists($ipStatusFile)) {
            $ipStatus = file_get_contents($ipStatusFile);
        }

        $data['ipStatus'] = $ipStatus;
        $data['type'] = $type;

        $this->renderPage('panel/iptables_status', $data);
    }

    /**
     * System layer firwall - ip6tables
     *
     * @return void
     */
    protected function ip6tables(): void
    {
        $data[] = [];

        if (isset($_POST['tab'])) {
            unset($_POST['tab']);
            $this->saveConfig();
        }

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('properties');
        $t->setAccessible(true);
        $properties = $t->getValue($this->shieldon);

        $iptablesWatchingFolder = $properties['iptables_watching_folder'];

        // The iptables log files.
        $ipv6CommandFile = $iptablesWatchingFolder . '/ipv6_command.log';
        $ipv6Command = '';

        if (file_exists($ipv6CommandFile)) {
            $file = new SplFileObject($ipv6CommandFile);

            $ipv6Command = [];
            while (!$file->eof()) {
                $line = trim($file->fgets());
                $ipInfo = explode(',', $line);

                if (! empty($ipInfo[4])) {
                    $ipv6Command[] = $ipInfo;
                }
            }
        }

        $data['ipv6Command'] = $ipv6Command;

        $this->renderPage('panel/ip6tables_manager', $data);
    }

    /**
     * System layer firwall - ip6tables
     * ip6tables -L
     *
     * @return void
     */
    protected function ip6tablesStatus(): void
    {
        $data[] = [];

        $reflection = new ReflectionObject($this->shieldon);
        $t = $reflection->getProperty('properties');
        $t->setAccessible(true);
        $properties = $t->getValue($this->shieldon);

        $iptablesWatchingFolder = $properties['iptables_watching_folder'];

        // The iptables log files.
        $ipv6StatusFile = $iptablesWatchingFolder . '/ipv6_status.log';
        $ipv6Status = '';

        if (file_exists($ipv6StatusFile)) {
            $ipv6Status = file_get_contents($ipv6StatusFile);
        }

        $data['ipv6Status'] = $ipv6Status;

        $this->renderPage('panel/ip6tables_status', ['data' => $data]);
    }

    /**
     * Save the configuration settings to the JSON file.
     *
     * @return void
     */
    private function saveConfig(): void
    {
        $configFilePath = $this->directory . '/' . $this->filename;

        foreach ($this->csrfField as $csrfInfo) {
            if (! empty($csrfInfo['name'])) {
                unset($_POST[$csrfInfo['name']]);
            }
        }

        if (empty($_POST) || ! is_array($_POST) || 'managed' !== $this->mode) {
            return;
        }

        foreach ($_POST as $postKey => $postData) {
            if (is_string($postData)) {
                if ($postData === 'on') {
                    $this->setConfig(str_replace('__', '.', $postKey), true);
                } elseif ($postData === 'off') {
                    $this->setConfig(str_replace('__', '.', $postKey), false);
                } else {
                    if ($postKey === 'ip_variable_source') {
                        $this->setConfig('ip_variable_source.REMOTE_ADDR', false);
                        $this->setConfig('ip_variable_source.HTTP_CF_CONNECTING_IP', false);
                        $this->setConfig('ip_variable_source.HTTP_X_FORWARDED_FOR', false);
                        $this->setConfig('ip_variable_source.HTTP_X_FORWARDED_HOST', false);
                        $this->setConfig('ip_variable_source.' . $postData, true);

                    } elseif ($postKey === 'dialog_ui__shadow_opacity') {
                        $this->setConfig('dialog_ui.shadow_opacity', (string) $postData);

                    } elseif ($postKey === 'admin__pass') {
                        if (strlen($_POST['admin__pass']) < 58) {
                            $this->setConfig('admin.pass', password_hash($postData, PASSWORD_BCRYPT));
                        }
                    } else if ($postKey === 'messengers__sendgrid__config__recipients') {
                        $this->setConfig(
                            'messengers.sendgrid.config.recipients',
                            preg_split('/\r\n|[\r\n]/',
                            $postData)
                        );
                    } else {
                        if (is_numeric($postData)) {
                            $this->setConfig(str_replace('__', '.', $postKey), (int) $postData);
                        } else  {
                            $this->setConfig(str_replace('__', '.', $postKey), $postData);
                        }
                    }
                }
            }
        }

        //  Start checking the availibility of the data driver settings.
        $isDataDriverFailed = false;

        switch ($this->configuration['driver_type']) {

            case 'mysql':

                if (class_exists('PDO')) {
                    $db = [
                        'host'    => $this->getConfig('drivers.mysql.host'),
                        'dbname'  => $this->getConfig('drivers.mysql.dbname'),
                        'user'    => $this->getConfig('drivers.mysql.user'),
                        'pass'    => $this->getConfig('drivers.mysql.pass'),
                        'charset' => $this->getConfig('drivers.mysql.charset'),
                    ];

                    try {
                        $pdo = new PDO(
                            'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'] . ';charset=' . $db['charset'],
                            (string) $db['user'],
                            (string) $db['pass']
                        );
                    } catch(PDOException $e) {
                        $isDataDriverFailed = true;
                        $this->responseMessage('error', 
                            __(
                                'panel',
                                'error_mysql_connection',
                                'Cannot access to your MySQL database, please check your settings.'
                            )
                        );
                    }
                } else {
                    $isDataDriverFailed = true;
                    $this->responseMessage('error',
                        __(
                            'panel',
                            'error_mysql_driver_not_supported',
                            'Your system doesn’t support MySQL driver.'
                        )
                    );
                }

                break;

            case 'sqlite':

                $sqliteDir = rtrim($this->getConfig('drivers.sqlite.directory_path'), '\\/ ');

                if (empty($sqliteDir)) {
                    $sqliteDir = $this->directory . '/data_driver_sqlite';
                }

                $sqliteFilePath = $sqliteDir . '/shieldon.sqlite3';
                $this->setConfig('drivers.sqlite.directory_path', $sqliteDir);
                
                if (! file_exists($sqliteFilePath)) {
                    if (! is_dir($sqliteDir)) {
                        $originalUmask = umask(0);
                        @mkdir($sqliteDir, 0777, true);
                        umask($originalUmask);
                    }
                }

                if (class_exists('PDO')) {
                    try {
                        $pdo = new PDO('sqlite:' . $sqliteFilePath);
                    } catch(PDOException $e) {
                        $isDataDriverFailed = true;
                        $this->responseMessage('error', $e->getMessage());
                    }
                } else {
                    $isDataDriverFailed = true;
                    $this->responseMessage('error',
                        __(
                            'panel',
                            'error_sqlite_driver_not_supported',
                            'Your system doesn’t support SQLite driver.'
                        )
                    );
                }

                if (! is_writable($sqliteFilePath)) {
                    $isDataDriverFailed = true;
                    $this->responseMessage('error',
                        __(
                            'panel',
                            'error_sqlite_directory_not_writable',
                            'SQLite data driver requires the storage directory writable.'
                        )
                    );
                }

                break;

            case 'redis':

                if (class_exists('Redis')) {
                    try {
                        $redis = new Redis();
                        $redis->connect(
                            (string) $this->getConfig('drivers.redis.host'), 
                            (int)    $this->getConfig('drivers.redis.port')
                        );
                    } catch(RedisException $e) {
                        $isDataDriverFailed = true;
                        $this->responseMessage('error', $e->getMessage());
                    }
                } else {
                    $isDataDriverFailed = true;
                    $this->responseMessage('error',
                        __(
                            'panel',
                            'error_redis_driver_not_supported',
                            'Your system doesn’t support Redis driver.'
                        )
                    );
                }

                break;

            case 'file':
            default:

                $fileDir = rtrim($this->getConfig('drivers.file.directory_path'), '\\/ ');

                if (empty($fileDir)) {
                    $fileDir = $this->directory . '/data_driver_file';
                    $this->setConfig('drivers.file.directory_path', $fileDir);
                }

                $this->setConfig('drivers.file.directory_path', $fileDir);

                if (! is_dir($fileDir)) {
                    $originalUmask = umask(0);
                    @mkdir($fileDir, 0777, true);
                    umask($originalUmask);
                }

                if (! is_writable($fileDir)) {
                    $isDataDriverFailed = true;
                    $this->responseMessage('error',
                        __(
                            'panel',
                            'error_file_directory_not_writable',
                            'File data driver requires the storage directory writable.'
                        )
                    );
                }
            // endswitch
        }

        // Check Action Logger settings.
        $enableActionLogger = $this->getConfig('loggers.action.enable');
        $actionLogDir = rtrim($this->getConfig('loggers.action.config.directory_path'), '\\/ ');

        if ($enableActionLogger) {
            if (empty($actionLogDir)) {
                $actionLogDir = $this->directory . '/action_logs';
            }

            $this->setConfig('loggers.action.config.directory_path', $actionLogDir);
    
            if (! is_dir($actionLogDir)) {
                $originalUmask = umask(0);
                @mkdir($actionLogDir, 0777, true);
                umask($originalUmask);
            }
    
            if (! is_writable($actionLogDir)) {
                $isDataDriverFailed = true;
                $this->responseMessage('error',
                    __(
                        'panel',
                        'error_logger_directory_not_writable',
                        'Action Logger requires the storage directory writable.'
                    )
                );
            }
        }

        // System firewall.
        $enableIptables = $this->getConfig('iptables.enable');
        $iptablesWatchingFolder = rtrim($this->getConfig('iptables.config.watching_folder'), '\\/ ');

        if ($enableIptables) {
            if (empty($iptablesWatchingFolder)) {
                $iptablesWatchingFolder = $this->directory . '/iptables';
            }

            $this->setConfig('iptables.config.watching_folder', $iptablesWatchingFolder);

            if (! is_dir($iptablesWatchingFolder)) {
                $originalUmask = umask(0);
                @mkdir($iptablesWatchingFolder, 0777, true);
                umask($originalUmask);

                // Create default log files.
                if (is_writable($iptablesWatchingFolder)) {
                    fopen($iptablesWatchingFolder . '/iptables_queue.log', 'w+');
                    fopen($iptablesWatchingFolder . '/ipv4_status.log',    'w+');
                    fopen($iptablesWatchingFolder . '/ipv6_status.log',    'w+');
                    fopen($iptablesWatchingFolder . '/ipv4_command.log',   'w+');
                    fopen($iptablesWatchingFolder . '/ipv6_command.log',   'w+');
                }
            }
    
            if (! is_writable($iptablesWatchingFolder)) {
                $isDataDriverFailed = true;
                $this->responseMessage('error',
                    __(
                        'panel',
                        'error_ip6tables_directory_not_writable',
                        'iptables watching folder requires the storage directory writable.'
                    )
                );
            }
        }

        // Only update settings while data driver is correctly connected.
        if (! $isDataDriverFailed) {
            file_put_contents($configFilePath, json_encode($this->configuration));

            $this->responseMessage('success',
                __(
                    'panel',
                    'success_settings_saved',
                    'Settings saved.'
                )
            );
        }
    }

    /**
     * Export settings.
     *
     * @return void
     */
    protected function exportSettings()
    {
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=shieldon-' . date('YmdHis') . '.json');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        echo json_encode($this->configuration);
    }

    /**
     * Import settings.
     *
     * @return void
     */
    protected function importSettings()
    {
        if (! empty($_FILES['json_file']['tmp_name'])) {
            $importedFileContent = file_get_contents($_FILES['json_file']['tmp_name']);
        }

        if (! empty($importedFileContent)) {
            $jsonData = json_decode($importedFileContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->responseMessage('error',
                    __(
                        'panel',
                        'error_invalid_json_file',
                        'Invalid JSON file.'
                    )
                );

                $_SESSION['flash_messages'] = $this->messages;
                header('Location: ' . $this->url('settings'));
                exit;
            }

            $checkFileVaild = true;

            foreach (array_keys($this->configuration) as $key) {
                if (! isset($jsonData[$key])) {
                    $checkFileVaild = false;
                }
            }

            if ($checkFileVaild) {
                foreach (array_keys($jsonData) as $key) {
                    if (isset($this->configuration[$key])) {
                        unset($this->configuration[$key]);
                    }
                }

                $this->configuration = $this->configuration + $jsonData;

                // Save settings into a configuration file.
                $configFilePath = $this->directory . '/' . $this->filename;
                file_put_contents($configFilePath, json_encode($this->configuration));

                $this->responseMessage('success',
                    __(
                        'panel',
                        'success_json_imported',
                        'JSON file imported successfully.'
                    )
                );

                $_SESSION['flash_messages'] = $this->messages;
                header('Location: ' . $this->url('settings'));
                exit;
            }
        }

        $this->responseMessage('error',
            __(
                'panel',
                'error_invalid_config_file',
                'Invalid Shieldon configuration file.'
            )
        );

        $_SESSION['flash_messages'] = $this->messages;
        header('Location: ' . $this->url('settings'));
        exit;
    }

    /**
     * Echo the setting string to the template.
     *
     * @param string $field   Field.
     * @param mixed  $defailt Default value.
     *
     * @return void
     */
    protected function _(string $field, $default = ''): void
    {
        if (is_string($this->getConfig($field)) || is_numeric($this->getConfig($field))) {

            if ('demo' === $this->mode) {

                // Hide sensitive data because of security concerns.
                $hiddenForDemo = [
                    'drivers.redis.auth',
                    'drivers.file.directory_path',
                    'drivers.sqlite.directory_path',
                    'drivers.mysql.dbname',
                    'drivers.mysql.user',
                    'drivers.mysql.pass',
                    'captcha_modules.recaptcha.config.site_key',
                    'captcha_modules.recaptcha.config.secret_key',
                    'loggers.action.config.directory_path',
                    'admin.user',
                    'admin.pass',
                    'admin.last_modified',
                    'messengers.telegram.config.api_key',
                    'messengers.telegram.config.channel',
                    'messengers.sendgrid.config.api_key',
                    'messengers.sendgrid.config.sender',
                    'messengers.sendgrid.config.recipients',
                    'messengers.line_notify.config.access_token',
                    'iptables.config.watching_folder',
                    'ip6tables.config.watching_folder',
                ];

                if (in_array($field, $hiddenForDemo)) {
                    echo __('panel', 'field_not_visible', 'Cannot view this field in demo mode.');
                } else {
                    echo (! empty($this->getConfig($field))) ? $this->getConfig($field) : $default;
                }

            } else {
                echo (! empty($this->getConfig($field))) ? $this->getConfig($field) : $default;
            }
        } elseif (is_array($this->getConfig($field))) {

            if ('demo' === $this->mode) {
                $hiddenForDemo = [
                    'messengers.sendgrid.config.recipients'
                ];

                if (in_array($field, $hiddenForDemo)) {
                    echo __('panel', 'field_not_visible', 'Cannot view this field in demo mode.');
                } else {
                    echo implode("\n", $this->getConfig($field));
                }

            } else {
                echo implode("\n", $this->getConfig($field));
            }
        }
    }

    /**
     * Use on HTML checkbox and radio elements.
     *
     * @param string $value
     * @param mixed  $valueChecked
     * @param bool   $isConfig
     *
     * @return void
     */
    protected function checked(string $value, $valueChecked, bool $isConfig = true): void
    {
        if ($isConfig) {
            if ($this->getConfig($value) === $valueChecked) {
                echo 'checked';
            } else {
                echo '';
            }
        } else {
            if ($value === $valueChecked) {
                echo 'checked';
            } else {
                echo '';
            }
        }
    }

    /**
     * Echo correspondence string on Messenger setting page.
     *
     * @param string $moduleName
     * @param string $echoType
     *
     * @return void
     */
    protected function _m(string $moduleName, string $echoType = 'css'): void
    {
        if ('css' === $echoType) {
            echo $this->getConfig('messengers.' . $moduleName . '.confirm_test') ? 'success' : '';
        }

        if ('icon' === $echoType) {
            echo $this->getConfig('messengers.' . $moduleName . '.confirm_test') ? '<i class="fas fa-check"></i>' : '<i class="fas fa-exclamation"></i>';
        }
    }

    /**
     * Use on HTML select elemets.
     *
     * @param string $value
     * @param mixed $valueChecked
     *
     * @return void
     */
    protected function selected(string $value, $valueChecked): void
    {
        if ($this->getConfig($value) === $valueChecked) {
            echo 'selected';
        } else {
            echo '';
        }
    }

    /**
     * Load view file.
     *
     * @param string $page
     * @param array  $data
     * @param bool   $echo
     *
     * @return string|void
     */
    private function loadView(string $page, array $data = [], $echo = false)
    {
        if (! defined('SHIELDON_VIEW')) {
            define('SHIELDON_VIEW', true);
        }

        $viewFilePath =  __DIR__ . '/../../templates/' . $page . '.php';
    
        if (! empty($data)) {
            extract($data);
        }

        $result = '';
    
        if (file_exists($viewFilePath)) {
            ob_start();
            require $viewFilePath;
            $result = ob_get_contents();
            ob_end_clean();
        }

        if ($echo) {
            echo $result;
            exit;
        }

        return $result;
    }

    /**
     * Include a view file.
     *
     * @param string $page
     * @param array $data
     *
     * @return void
     */
    private function _include(string $page, array $data = []): void
    {
        if (! defined('SHIELDON_VIEW')) {
            define('SHIELDON_VIEW', true);
        }

        foreach ($data as $k => $v) {
            ${$k} = $v;
        }

        require __DIR__ . '/../../templates/' . $page . '.php';
    }

    /**
     * Render the web page.
     *
     * @param string $page
     * @param array $data
     *
     * @return void
     */
    private function renderPage(string $page, array $data): void
    {
        $channelName = $this->shieldon->driver->getChannel();

        if (empty($channelName)) {
            $channelName = 'default';
        }

        $content['channel_name'] = $channelName;
        $content['mode_name'] = $this->mode;
        $content['page_url'] = $this->url();
        $content['title'] = $data['title'] ?? '';
        $content['content'] = $this->loadView($page, $data);

        $this->loadView('panel/template', $content, true);
    }

    /**
     * Response message to front.
     *
     * @param string $type
     * @param string $text
     *
     * @return void
     */
    private function responseMessage(string $type, string $text): void
    {
        $class = $type;

        if ($type == 'error') {
            $class = 'danger';
        }

        array_push($this->messages, [
            'type' => $type,
            'text' => $text,
            'class' => $class,
        ]);
    }

    /**
     * Providing the Dasboard URLs.
     *
     * @param string $page Page tab.
     * @param string $tab  Tab.
     *
     * @return string
     */
    private function url(string $page = '', string $tab = ''): string
    {
        $httpProtocal = 'http://';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $httpProtocal = 'https://';
        }

        $path = parse_url($httpProtocal . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $url = $httpProtocal . $_SERVER['HTTP_HOST'] . $path;
        $soPage = (! empty($page)) ? '?so_page=' . $page : '';
        $soTab = (! empty($tab)) ? '&tab=' . $tab : '';

        return $url . $soPage . $soTab;
    }

    /**
     * Prompt an authorization login.
     *
     * @return void
     */
    private function httpAuth(): void
    {
        if ('demo' === $this->mode || 'self' === $this->mode) {
            $admin = $this->demoUser;
        }

        if (! isset($_SESSION['SHIELDON_USER_LOGIN'])) {
            $this->login();
        }
    }

    /**
     * Switch supported language.
     *
     * @return void
     */
    private function ajaxChangeLocale(): void
    {
        $_SESSION['SHIELDON_PANEL_LANG'] = $_GET['langCode'] ?? 'en';

        $response['status'] = 'success';
        $response['lang_code'] = $_GET['langCode'];
        $response['session_lang_code'] = $_SESSION['SHIELDON_PANEL_LANG'];
 
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        exit;
    }

    /**
     * Test messenger modules.
     *
     * @return void
     */
    private function ajaxTestMessengerModules(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $moduleName = $_GET['module'] ?? '';

        $response = [];
        $response['status'] = 'error';
        $response['result']['moduleName'] = $moduleName;

        $testMsgTitle = __('panel', 'test_msg_title', 'Testing Message from Host: ') . $_SERVER['SERVER_ADDR'];
        $testMsgBody = __('panel', 'test_msg_body', 'Messenger module "{0}" has been tested and confirmed successfully.', [$moduleName]);
    
        switch($moduleName) {

            case 'telegram':
                $apiKey = $_GET['apiKey'] ?? '';
                $channel = $_GET['channel'] ?? '';
                if (! empty($apiKey) && ! empty($channel)) {
                    $messenger = new MessengerModule\Telegram($apiKey, $channel);
                    if ($messenger->send($testMsgBody)) {
                        $response['status'] = 'success';
                    }
                }
                break;

            case 'line-notify':
                $accessToken = $_GET['accessToken'] ?? '';
                if (! empty($accessToken)) {
                    $messenger = new MessengerModule\LineNotify($accessToken);
                    if ($messenger->send($testMsgBody)) {
                        $response['status'] = 'success';
                    }
                }
                break;

            case 'slack':
                $botToken = $_GET['botToken'] ?? '';
                $channel = $_GET['channel'] ?? '';
                if (! empty($botToken) && ! empty($channel)) {
                    $messenger = new MessengerModule\Slack($botToken, $channel);
                    if ($messenger->send($testMsgBody)) {
                        $response['status'] = 'success';
                    }
                }
                break;

            case 'slack-webhook':
                $webhookUrl = $_GET['webhookUrl'] ?? '';
                if (! empty($webhookUrl)) {
                    $messenger = new MessengerModule\SlackWebhook($webhookUrl);
                    if ($messenger->send($testMsgBody)) {
                        $response['status'] = 'success';
                    }
                }
                break;

            case 'rocket-chat':
                $serverUrl = $_GET['serverUrl'] ?? '';
                $userId = $_GET['userId'] ?? '';
                $accessToken = $_GET['accessToken'] ?? '';
                $channel = $_GET['channel'] ?? '';

                if (
                       ! empty($serverUrl) 
                    && ! empty($userId)
                    && ! empty($accessToken)
                    && ! empty($channel)
                ) {
                    $messenger = new MessengerModule\RocketChat($accessToken, $userId, $serverUrl, $channel);
                    if ($messenger->send($testMsgBody)) {
                        $response['status'] = 'success';
                    }
                }
                break;

            case 'smtp':
                $type = $_GET['type'] ?? '';
                $host = $_GET['host'] ?? '';
                $user = $_GET['user'] ?? '';
                $pass = $_GET['pass'] ?? '';
                $port = $_GET['port'] ?? '';

                $sender = $_GET['sender'] ?? '';
                $recipients = $_GET['recipients'] ?? '';

                if (
                    (! filter_var($host, FILTER_VALIDATE_IP) && ! filter_var($host, FILTER_VALIDATE_DOMAIN))
                    || ! is_numeric($port)
                    || empty($user)
                    || empty($pass) 
                ) {
                    $response['result']['message'] = 'Invalid fields.';
                    echo json_encode($response);
                    exit;
                }

                if ('ssl' === $type || 'tls' === $type) {
                    $host = $type . '://' . $host;
                }

                if (! empty($sender) && $recipients) {
                    $recipients = str_replace("\r", '|', $recipients);
                    $recipients = str_replace("\n", '|', $recipients);
                    $recipients = explode('|', $recipients);

                    $messenger = new MessengerModule\Smtp($user, $pass, $host, (int) $port);

                    foreach($recipients as $recipient) {
                        if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                            $messenger->addRecipient($recipient);
                        }
                    }

                    if (filter_var($sender, FILTER_VALIDATE_EMAIL)) {
                        $messenger->addSender($sender);
                    }

                    $messenger->setSubject($testMsgTitle);

                    if ($messenger->send($testMsgBody)) {
                        $response['status'] = 'success';
                    }
                }
                break;

            case 'native-php-mail':
                $sender = $_GET['sender'] ?? '';
                $recipients = $_GET['recipients'] ?? '';

                if (! empty($sender) && ! empty($recipients)) {
                    $recipients = str_replace("\r", '|', $recipients);
                    $recipients = str_replace("\n", '|', $recipients);
                    $recipients = explode('|', $recipients);

                    $messenger = new MessengerModule\Mail();

                    foreach($recipients as $recipient) {
                   
                        if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                            $messenger->addRecipient($recipient);
                        }
                    }

                    if (filter_var($sender, FILTER_VALIDATE_EMAIL)) {
                        $messenger->addSender($sender);
                    }

                    $messenger->setSubject($testMsgTitle);

                    if ($messenger->send($testMsgBody)) {
                        $response['status'] = 'success';
                    }
                }
                break;

            case 'sendgrid':
                $apiKey = $_GET['apiKey'] ?? '';
                $sender = $_GET['sender'] ?? '';
                $recipients = $_GET['recipients'] ?? '';

                if (! empty($sender) && ! empty($recipients) && ! empty($apiKey)) {
                    $recipients = str_replace("\r", '|', $recipients);
                    $recipients = str_replace("\n", '|', $recipients);
                    $recipients = explode('|', $recipients);

                    $messenger = new MessengerModule\Sendgrid($apiKey);

                    foreach($recipients as $recipient) {
                        if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                            $messenger->addRecipient($recipient);
                        }
                    }

                    if (filter_var($sender, FILTER_VALIDATE_EMAIL)) {
                        $messenger->addSender($sender);
                    }

                    $messenger->setSubject($testMsgTitle);

                    if ($messenger->send($testMsgBody)) {
                        $response['status'] = 'success';
                    }
                }
                break;

            case 'mailgun':
                $apiKey = $_GET['apiKey'] ?? '';
                $domain = $_GET['domain'] ?? '';
                $sender = $_GET['sender'] ?? '';
                $recipients = $_GET['recipients'] ?? '';

                if (! empty($sender) && ! empty($recipients) && ! empty($apiKey) && ! empty($domain)) {
                    $recipients = str_replace("\r", '|', $recipients);
                    $recipients = str_replace("\n", '|', $recipients);
                    $recipients = explode('|', $recipients);

                    $messenger = new MessengerModule\Mailgun($apiKey, $domain);

                    foreach($recipients as $recipient) {
                        if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                            $messenger->addRecipient($recipient);
                        }
                    }

                    if (filter_var($sender, FILTER_VALIDATE_EMAIL)) {
                        $messenger->addSender($sender);
                    }

                    $messenger->setSubject($testMsgTitle);

                    if ($messenger->send($testMsgBody)) {
                        $response['status'] = 'success';
                    }
                }
                break;

            default:
                $response['status'] = 'undefined';
        }

        $moduleName = str_replace('-', '_', $moduleName);

        $postKey = 'messengers__' . $moduleName . '__confirm_test';

        if ('success' === $response['status']) {
            $_POST[$postKey] = 'on';
            $this->saveConfig();
        } elseif ('error' === $response['status']) {
            $_POST[$postKey] = 'off';
            $this->saveConfig();
        }

        $response['result']['postKey'] = $postKey;

        echo json_encode($response);
        exit;
    }

    /**
     * Set the Captcha modules.
     *
     * @return void
     */
    protected function applyCaptchaForms(): void
    {
        $this->captcha[] = new Foundation();

        $recaptchaSetting = $this->getConfig('captcha_modules.recaptcha');
        $imageSetting = $this->getConfig('captcha_modules.image');

        if ($recaptchaSetting['enable']) {

            $googleRecaptcha = [
                'key'     => $recaptchaSetting['config']['site_key'],
                'secret'  => $recaptchaSetting['config']['secret_key'],
                'version' => $recaptchaSetting['config']['version'],
                'lang'    => $recaptchaSetting['config']['lang'],
            ];

            $this->captcha[] = new Recaptcha($googleRecaptcha);
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

            $this->captcha[] = new ImageCaptcha($imageCaptchaConfig);
        }
    }

    // @codeCoverageIgnoreEnd
}

