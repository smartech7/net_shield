<?php

return [

    'reason_manual_ban' => '被管理員手動加入',
    'reason_is_search_engine' => '搜尋引擎機器人',
    'reason_is_google' => 'Google 機器人',
    'reason_is_bing' => 'Bing 機器人',
    'reason_is_yahoo' => 'Yahoo 機器人',
    'reason_too_many_sessions' => '太多工作階段',
    'reason_too_many_accesses' => '太多連線',
    'reason_empty_js_cookie' => '無法建立 JS cookie',
    'reason_empty_referer' => '空的來源網址',
    'reason_reached_limit_day' => '達成每日限制',
    'reason_reached_limit_hour' => '達到每小時限制',
    'reason_reached_limit_minute' => '達到每分限制',
    'reason_reached_limit_second' => '達到每秒限制',

    // Message
    'error_mysql_connection' => '無法連接您的 MySQL 資料庫，請檢查設定值。',
    'error_mysql_driver_not_supported' => '您的系統不支援 MySQL 驅動器。',
    'error_sqlite_driver_not_supported' => '您的系統不支援 SQLite 驅動器。',
    'error_sqlite_directory_not_writable' => 'SQLite 資料驅動器需要儲存目錄可寫入。',
    'error_redis_driver_not_supported' => '您的系統不支援 Redis 驅動器。',
    'error_file_directory_not_writable' => '檔案資料驅動器需要儲存目錄可寫入。',
    'error_logger_directory_not_writable' => '動作日誌記錄器需要儲存目錄可寫入。',
    'success_settings_saved' => '設定值已儲存。',

    // Others.
    'field_not_visible' => '無法在示範模式檢視此欄位。',
    'permission_required' => '需要權限。',

    // Header status bar.
    'channel' => '頻道',
    'mode' => '模式',
    'logout' => '登出',

    // Setting - authentication page.
    'auth_heading' => '認證方式',
    'auth_description' => '回應頭部字段 HTTP WWW-Authenticate 定義了獲取對資源的連接權限應該被使用的認證方式。',
    'auth_label_url_path' => '網址路徑',
    'auth_label_username' => '使用者',
    'auth_label_password' => '密碼',
    'auth_btn_submit' => '送出',
    'auth_label_encrypted' => '已加密',
    'auth_label_remove' => '移除',

    // Setting - exclusion page.
    'excl_heading' => '排除',
    'excl_description' => '請輸入要排除掉 Shieldon 保護的開頭網址。',

    // IP Manager
    'ipma_heading' => 'IP Manager',
    'ipma_description' => 'IP Manager is not like Rule Table (effective period depends on the data cycle), everything you have done here is permanent.',
    'ipma_label_ip' => 'IP',
    'ipma_label_order' => 'Order',
    'ipma_label_rule' => 'Rule',
    'ipma_label_action' => 'Action',
    'ipma_label_plz_select' => 'Please select',
    'ipma_label_remove_ip' => 'Remove this IP',
    'ipma_label_allow_ip' => 'Allow this IP',
    'ipma_label_deny_ip' => 'Deny this IP',

    // Log
    'log_heading_captchas' => 'CAPTCHAs',
    'log_note_captcha_last_month' => 'CAPTCHA statistic last month',
    'log_heading_pageviews' => 'Pageviews',
    'log_note_pageview_last_month' => 'Total pageviews last month',

    'log_label_last_month' => 'Last month',
    'log_label_this_month' => 'This month',
    'log_label_last_7_days' => 'Last 7 days',
    'log_label_yesterday' => 'Yesterday',
    'log_label_today' => 'Today',
    'log_msg_no_logger' => 'Sorry, you have to implement ActionLogger to use this function.',
    'log_label_in_queue' => 'In queue',
    'log_label_in_blacklist' => 'In blacklist',
    'log_label_captcha' => 'CAPTCHA',
    'log_label_pageviews' => 'Pageviews',
    'log_label_session' => 'Sessions',
    'log_label_solved' => 'solved',
    'log_label_failed' => 'failed',
    'log_label_displays' => 'displays',
    'log_label_timezone' => 'Timezone',
    'log_note_captcha_last_7_days',

    // Overview
    'overview_heading_data_circle' => 'Data Circle',
    'overview_label_mysql' => 'MySQL',
    'overview_text_rows' => 'rows',
    'overview_note_sql_db' => 'SQL database.',
    'overview_note_memory_db' =>  'In-memory dadabase.',
    'overview_label_redis' => 'Redis',
    'overview_btn_document' => 'Document',
    'overview_btn_close' => 'Close',
    'overview_btn_save' => 'SAVE',
    'overview_note_image_captcha' => 'A simple text-in-image Captcha.',
    'overview_label_image_captcha' => 'Image Captcha',
    'overview_note_recaptcha' => 'Provided by Google.',
    'overview_label_recaptcha' => 'reCAPTCHA',
    'overview_heading_captcha' => 'Captcha Modules',
    'overview_note_action_logger' => 'Record every visitor’s behavior.',
    'overview_label_action_logger' => 'Action Logger',
    'overview_heading_logger' => 'Logger',
    'overview_text_since' => 'since',
    'overview_text_days' => 'days',
    'overview_text_size' => 'size',
    'overview_note_useragent' => 'Analysis user-agent information from visitors.',
    'overview_label_useragent' => 'User Agent',
    'overview_note_rdns' => 'Identify IP resolved hostname (RDNS) from visitors.',
    'overview_label_rdns' => 'RDNS',
    'overview_note_header' => 'Analyze header information from visitors.',
    'overview_label_header' => 'Header',
    'overview_note_trustedbot' => 'Allow popular search engines crawl your website.',
    'overview_label_trustedbot' => 'Trusted Bot',
    'overview_note_ip' => 'Advanced IP address mangement.',
    'overview_label_ip' => 'IP',
    'overview_heading_components' => 'Components',
    'overview_note_referer' => 'Check HTTP referrer information.',
    'overview_label_referer' => 'Referrer',
    'overview_note_frequency' => 'Check how often does a visitor view the pages.',
    'overview_label_frequency' => 'Frequency',
    'overview_note_session' => 'Detect multiple sessions created by the same visitor.',
    'overview_label_session' => 'Session',
    'overview_note_cookie' => 'Check if visitors can create cookie by JavaScript.',
    'overview_label_cookie' => 'Cookie',
    'overview_heading_filters' => 'Filters',
    'overview_label_sqlite' => 'SQLite',
    'overview_note_file_system' => 'File system.',
    'overview_label_file' => 'File',
    'overview_text_more_usages' => 'For more usages please check out that document.',

    // IP log table.
    'table_heading_ip_log' => 'IP Log Table',
    'table_description_ip_log_1' => 'This is where the Shieldon records the users’ strange behavior.',
    'table_description_ip_log_2' => 'All processes are automatic and instant, you can ignore that.',
    'table_description_ip_log_3' => 'IP log table will be all cleared after new cycle begins.',
    'table_label_resolved_hostname' => 'Resolved hostname',
    'table_label_last_visit' => 'Last visit',
    'table_label_flags' => 'Flags',

    // Rule table.
    'table_heading_rule' => 'Rule Table',
    'table_description_rule_1' => 'This is where the Shieldon temporarily allows or denys users in current cycle.',
    'table_description_rule_2' => 'All processes are automatic and instant, you can ignore that.',
    'table_description_rule_3' => 'Rule table will be reset after new cycle begins.',
    'table_label_deny_ip_temporarily' => 'Deny this IP temporarily',
    'table_label_deny_ip_permanently' => 'Deny this IP permanently',
    'table_ip_placeholder' => 'Please fill in an IP address..',
    'table_label_type' => 'Type',
    'table_label_reason' => 'Reason',
    'table_label_time' => 'Time',
    'table_label_remove' => 'Remove',

    // Session table.
    'table_heading_session' => 'Session Table',
    'table_label_remain_seconds' => 'Remain Seconds',
    'table_label_priority' => 'Priority',
    'table_label_status' => 'Status',
    'table_label_session_id' => 'Session ID',
    'table_text_allowable' => 'Allowable',
    'table_text_expired' => 'Expired',
    'table_text_waiting' => 'Waiting',
    'table_description_session_1' => 'Read-time logs for <strong>Online Session Controll</strong>. All processes are automatic and instant, you can ignore that.',
    'table_description_session_2' => 'Notice this is only working when you have enabled that function.',
    'table_heading_limit' => 'Limit',
    'table_note_limit' => 'Online session limit.',
    'table_heading_period' => 'Period',
    'table_note_period' => 'Keep-alive period. (minutes)',
    'table_heading_online' => 'Online',
    'table_note_online' => 'Online session limit.',

    // Xss protection.
    'xss_heading' => 'XSS Protection',
    'xss_description' => 'Prevent Cross site scripting (XSS) attacks.',
    'xss_label_variable' => 'Variable',
    'xss_text_eradicate_injection' => 'Eradicate potential injection string for single variable.',
    'xss_label_single_variable' => 'Single variable',
    'xss_label_variable_name' => 'Variable Name',
    'xss_text_update_above_settings' => 'Update above settings.',
    'xss_text_filter_cookie_variables' => 'Filter all COOKIE method variables.',
    'xss_text_filter_get_variables' => 'Filter all GET method variables.',
    'xss_text_filter_post_variables' => 'Filter all POST method variables.',

    // Tab
    'tab_heading_adminlogin' => 'Admin Login',
    'tab_heading_dialogui' => 'Dialog UI',
    'tab_heading_captchas' => 'CAPTCHAs',
    'tab_heading_filters' => 'Filters',
    'tab_heading_components' => 'Components',
    'tab_heading_daemon' => 'Daemon',

    // Setting - admin login.
    'setting_heading_adminlogin' => ' Admin Login',
    'setting_label_last_modified' => 'Last Modified',
    'setting_label_password' => 'Password',
    'setting_label_user' => 'User',

    // Setting - captcha.
    'setting_heading_recaptcha' => 'reCAPTCHA',
    'setting_label_recaptcha_key' => 'Site Key',
    'setting_note_recaptcha_key' => 'Enter Google reCaptcha site key for your webiste.',
    'setting_label_recaptcha_secret' => 'Secret Key',
    'setting_note_recaptcha_secret' => 'Enter Google reCahptcha secret key for your webiste.',
    'setting_label_recaptcha_version' => 'Version',
    'setting_note_recaptcha_version' => 'Please use corresponding key for that version you choose, otherwise it won’t work.',
    'setting_label_recaptcha_lang' => 'Language Code',
    'setting_note_recaptcha_lang' => 'ISO 639 - ISO 3166 code. For example, zh-TW stands for Tranditional Chinese of Taiwan.',
    'setting_note_image_captcha_1' => 'Alpha-numeric string with lower and uppercase characters.',
    'setting_note_image_captcha_2' => 'A string with lower and uppercase letters only.',
    'setting_note_image_captcha_3' => 'Numeric string only.',
    'setting_note_image_captcha_length' => 'How many characters do you like to display on CAPTCHA.',
    'setting_label_length' => 'Length',
    'setting_heading_image_captcha' => 'Image',

    // Setting - component.
    'setting_heading_component_ip' => 'IP',
    'setting_note_component_ip' => 'Activate the IP Manager by enabling this option.',
    'setting_heading_component_tb' => 'Trusted Bots',
    'setting_note_component_tb_1' => 'Allow popular search engines to crawl your website.',
    'setting_note_component_tb_2' => 'Notice: Turning this option off will impact your SEO because the bots will be going to the checking process.',
    'setting_label_strict_mode' => 'Strict Mode',
    'setting_note_component_tb_3' => 'IP resolved hostname and IP address must match with each other.',
    'setting_heading_component_header' => 'Header',
    'setting_note_component_header_1' => 'Analyze header information from visitors.',
    'setting_note_component_header_2' => 'Deny all vistors without common header information.',
    'setting_heading_component_useragent' => 'User Agent',
    'setting_note_component_useragent_1' => 'Analyze user-agent information from visitors.',
    'setting_note_component_useragent_2' => 'Visitors with empty user-agent information will be blocked.',
    'setting_heading_component_rdns' => 'Reverse DNS',
    'setting_note_component_rdns_1' => 'In general, an IP from Internet Service Provider (ISP) often have the RDNS set. This option only works when strict mode is on.',
    'setting_note_component_rdns_2' => 'Visitors with an empty RDNS record will be blocked.',

    // Setting - daemon
    'setting_heading_enable' => 'Enable',
    'setting_label_data_driver' => 'Data Driver',
    'setting_note_data_driver' => 'Start protecting your website by implementing Shieldon. This plugin only works when this option is enabled.',
    'setting_label_driver_file' => 'File system',
    'setting_label_driver_mysql' => 'MySQL',
    'setting_label_driver_redis' => 'Redis',
    'setting_label_driver_sqlite' => 'SQLite',
    'setting_label_mysql_host' => 'Host',
    'setting_label_mysql_dbname' => 'DB Name',
    'setting_label_mysql_user' => 'User',
    'setting_label_mysql_password' => 'Password',
    'setting_label_mysql_charset' => 'Charset',
    'setting_label_redis_host' => 'Host',
    'setting_label_redis_port' => 'Port',
    'setting_label_redis_auth' => 'Auth',
    'setting_note_redis_host' => 'Required only if a password is needed.',
    'setting_label_directory' => 'Directory',
    'setting_note_directory' => 'Please fill in the absolute path of the directory where you store the data.',
    'setting_label_reset_data_cycle' => 'Reset Data Cycle',
    'setting_note_reset_data_cycle' => 'Clear all logs every day at 0:00 a.m. automatically. Turning this option on will improve performance.',
    'setting_label_ip_source' => 'IP Source',
    'setting_note_ip_source' => 'Is your website behind the CDN service? If you use CDN, you have to set this setting correctly; otherwise, all IP addresses from CDN servers will probably get banned.',
    'setting_label_session_limit' => 'Session Limit',
    'setting_note_session_limit_1' => 'When the online user amount has reached the limitation, other users not in the queue have to line up!',
    'setting_note_session_limit_2' => 'The following image is an example.',
    'setting_label_online_limit' => 'Online Limit',
    'setting_note_online_limit' => 'The maximum online user limit.',
    'setting_label_alive_period' => 'Keep Alive Period',
    'setting_note_alive_period' => 'Unit: minute.',
    'setting_label_logs' => 'Logs',
    'setting_label_action_logger' => 'Action Logger',
    'setting_heading_dailogui' => 'Dialog UI',
    'setting_label_language' => 'Language',
    'setting_label_background_image' => 'Background Image',
    'setting_note_background_image' => 'Please add a full URL or relative path of the image.',
    'setting_label_background_color' => 'Background Color',
    'setting_note_background_color' => 'You can specify a background color if you don’t want to use a background image.',
    'setting_label_dialog_header' => 'Dialog Header',
    'setting_text_for_example' => 'For example',
    'setting_label_font_color' => 'Font Color',
    'setting_label_shadow_opacity' => 'Shadow Opacity',
    'setting_note_shadow_opacity' => 'The range from 0 to 1, for example, 0.2 stands for 20% opacity.',
    'setting_heading_filter_frequency' => 'Frequency',
    'setting_note_filter_frequency_1' => 'Don’t worry about human visitors, and if they reach the limitation and get banned, they can easily continue surfing your website by solving the CAPTCHA.',
    'setting_note_filter_frequency_2' => 'The following image is an example.',
    'setting_label_secondly_limit' => 'Secondly Limit',
    'setting_label_minutely_limit' => 'Minutely Limit',
    'setting_label_hourly_limit' => 'Hourly Limit',
    'setting_label_daily_limit' => 'Daily Limit',
    'setting_note_secondly_limit' => 'Page views per vistor per second.',
    'setting_note_minutely_limit' => 'Page views per vistor per minute.',
    'setting_note_hourly_limit' => 'Page views per vistor per hour.',
    'setting_note_daily_limit' => 'Page views per vistor per day.',
    'setting_label_filter_cookie' => 'Cookie',
    'setting_label_filter_session' => 'Session',
    'setting_note_filter_session' => 'Detect multiple sessions created by the same visitor.',
    'setting_label_filter_referer' => 'Referrer',
    'setting_note_filter_referer' => 'Check HTTP referer information.',
    'setting_label_quota' => 'Quota',
    'setting_note_quota' => 'A visitor reached this limit will get banned temporarily.',
    'setting_label_buffered_time' => 'Buffered Time',
    'setting_note_buffered_time' => 'Start using this filter after n seconds after the first time visiting your website.',
    'setting_label_cookie_name' => 'Cookie Name',
    'setting_note_cookie_name' => 'English characters only.',
    'setting_label_cookie_value' => 'Cookie Value',
    'setting_label_cookie_domain' => 'Cookie Domain',
    'setting_text_leave_blank' => 'Just leave it blank.'
];
