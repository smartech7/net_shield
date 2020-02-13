<?php

return [

    'reason_manual_ban' => 'Added manually by administrator.',
    'reason_is_search_engine' => 'Search engine bot.',
    'reason_is_google' => 'Google bot.',
    'reason_is_bing' => 'Bing bot.',
    'reason_is_yahoo' => 'Yahoo bot.',
    'reason_too_many_sessions' => 'Too many sessions.',
    'reason_too_many_accesses' => 'Too many accesses.',
    'reason_empty_js_cookie' => 'Cannot create JS cookies.',
    'reason_empty_referer' => 'Empty referrer.',
    'reason_reached_limit_day' => 'Daily limit reached.',
    'reason_reached_limit_hour' => 'Hourly limit reached.',
    'reason_reached_limit_minute' => 'Minutely limit reached.',
    'reason_reached_limit_second' => 'Secondly limit reached.',

    // Menu
    'nav_locale' => 'Locale',
    'menu_firewall' => 'Firewall',
    'menu_status' => 'Status',
    'menu_overview' => 'Overflow',
    'menu_settings' => 'Settings',
    'menu_ip_manager' => 'IP Manager',
    'menu_xss_protection' => 'XSS Protection',
    'menu_authentication' => 'Authentication',
    'menu_exclusion' => 'Exclusion',
    'menu_last_month' => 'Last month',
    'menu_this_month' => 'This month',
    'menu_last_7_days' => 'Last 7 days',
    'menu_yesterday' => 'Yesterday',
    'menu_today' => 'Today',
    'menu_ip_filter_logs' => 'Filter Logs',
    'menu_ip_rules' => 'IP Rules',
    'menu_sessions' => 'Sessions',
    'menu_logs' => 'Logs',
    'menu_data_circle' => 'Data Circle',
    'menu_operation_status' => 'Operation',

    // Message
    'error_mysql_connection' => 'Cannot access to your MySQL database, please check your settings.',
    'error_mysql_driver_not_supported' => 'Your system doesn’t support MySQL driver.',
    'error_sqlite_driver_not_supported' => 'Your system doesn’t support SQLite driver.',
    'error_sqlite_directory_not_writable' => 'SQLite data driver requires the storage directory writable.',
    'error_redis_driver_not_supported' => 'Your system doesn’t support Redis driver.',
    'error_file_directory_not_writable' => 'File data driver requires the storage directory writable.',
    'error_logger_directory_not_writable' => 'Action Logger requires the storage directory writable.',
    'success_settings_saved' => 'Settings saved',

    // Others.
    'field_not_visible' => 'Cannot view this field in demo mode.',
    'permission_required' => 'Permission required.',

    // Header status bar.
    'channel' => 'Channel',
    'mode' => 'Mode',
    'logout' => 'Logout',

    // Setting - authentication page.
    'auth_heading' => 'Authentication',
    'auth_description' => 'The HTTP WWW-Authenticate response header defines the authentication method that should be used to gain access to a resource.',
    'auth_label_url_path' => 'URL Path',
    'auth_label_username' => 'Username',
    'auth_label_password' => 'Password',
    'auth_btn_submit' => 'Submit',
    'auth_label_encrypted' => 'encrypted',
    'auth_label_remove' => 'Remove',

    // Setting - exclusion page.
    'excl_heading' => 'Exclusion',
    'excl_description' => 'Please enter the begin with URLs you want them excluded from Shieldon protection.',

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
    'log_heading_pageviews' => 'Pageviews',
    'log_note_captcha_last_month' => 'CAPTCHA statistic last month.',
    'log_note_pageview_last_month' => 'Total pageviews last month.',
    'log_note_captcha_last_7_days' => 'CAPTCHA statistic last 7 days.',
    'log_note_pageview_last_7_days' => 'Total pageviews last 7 days.',
    'log_note_pageview_this_month' => 'Total pageviews this month.',
    'log_note_captcha_this_month' => 'CAPTCHA statistic this month.',
    'log_note_captcha_today' => 'CAPTCHA statistic today.',
    'log_note_pageview_today' => 'Total pageviews today.',
    'log_note_pageview_yesterday' => 'Total pageviews yesterday.',
    'log_note_captcha_yesterday' => 'CAPTCHA statistic today.',

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
    'overview_note_useragent' => 'Analyze user-agent information from visitors.',
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
    'overview_note_session' => 'Detect whether multiple sessions created by the same visitor.',
    'overview_label_session' => 'Session',
    'overview_note_cookie' => 'Check whether visitors can create cookie by JavaScript.',
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
    'table_description_rule_1' => 'It is the place where the Shieldon temporarily allows or denies users in the current cycle.',
    'table_description_rule_2' => 'All processes are automatic and instant, you can ignore that.',
    'table_description_rule_3' => 'Rule table will be reset when new cycle begins.',
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
    'setting_note_data_driver' => 'Start protecting your website by implementing Shieldon. Shieldon Firewall only works when this option is enabled.',
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
    'setting_note_filter_session' => 'Detect whether multiple sessions created by the same visitor.',
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
    'setting_text_leave_blank' => 'Just leave it blank to apply default.',

    // 2019/11/11
    'menu_iptables_manager' => 'Manager',
    'menu_iptables_status' => 'Status',
    'menu_messenger' => 'Messenger',
    'messenger_heading_events' => 'Events',
    'messenger_desc_events' => 'When they occur, what are the events that you would like to receive notifications sent by Messenger modules.',
    'messenger_label_event_1' => 'Ban a user permanently in current data cycle.',
    'messenger_desc_event_1' => 'This event happens typically when a user fails too many times due to invalid CAPTCHA in a row.',
    'messenger_label_event_2' => 'Ban a user permanently in system firwall',
    'messenger_desc_event_2' => 'This event happens typically when a user is already banned permanently in curent data cycle, but they are still access the warning pages too many times in a row, we can confirm that they are malicious bots.',
    'messenger_desc_event_3' => 'This option will take effect only when you have set the system-layer firewall correctly.',
    'messenger_heading_telegram' => 'Telegram',
    'messenger_label_api_key' => 'API Key',
    'messenger_label_channel' => 'Channel',
    'messenger_heading_line_notify' => 'Line Notify',
    'messenger_label_access_token' => 'Access Token',
    'messenger_heading_sendgrid' => 'SendGrid',
    'messenger_label_sender' => 'Sender',
    'messenger_label_recipients' => 'Recipients',
    'tab_heading_events' => 'Events',
    'tab_heading_modules' => 'Modules',
    'setting_heading_deny_attempts' => 'Deny Attempts',
    'setting_desc_deny_attempts' => 'Say goodbye to bad behavior visitors.',
    'error_ip6tables_directory_not_writable' => 'iptables watching folder requires the storage directory writable.',
    'setting_label_system_firewall' => 'System Firewall',
    'setting_note_install_iptables' => 'Make sure you have installed <strong>iptables</strong> and <strong>ip6tables</strong> already in your server, and employ <strong>fiewall.sh</strong> in <strong>crontab</strong> correctly.',
    'setting_label_watching_folder' => 'Watching Folder',
    'setting_label_cronjob' => 'Cron Job',
    'setting_note_cronjob' => 'Please use this code into the crontab file on your server.',
    'setting_note_iptables' => '<strong>firewall.sh</strong> will watch the changes in this folder to employ command to iptables.',
    'setting_label_deny_attempt_buffer' => 'Buffer',
    'setting_desc_deny_attempt_buffer' => 'How many fails in a row will trigger this event.',
    'iptable_heading' => 'Iptables Manager',
    'iptable_description_1' => 'This is Web Interface of <strong>iptables</strong>, be careful of using this function.',
    'iptable_description_2' => 'You can only manage incoming requests here.',
    'iptable_description_3' => 'After you reboot your server, the rules here will be disappeared. Using <strong>iptables-save</strong> youself to keep the rules exist.',
    'ip6table_description_1' => 'This is Web Interface of <strong>ip6tables</strong>, be careful of using this function.',
    'ip6table_description_2' => 'You can only manage incoming requests here.',
    'ip6table_description_3' => 'After you reboot your server, the rules here will be disappeared. Using <strong>ip6tables-save</strong> youself to keep the rules exist.',
    'iptables_label_subnet' => 'Subnet',
    'iptables_label_port' => 'Port',
    'iptables_label_port_custom' => 'Custom',
    'iptables_label_protocol' => 'Protocol',
    'iptables_label_protocol_all' => 'All',
    'iptables_label_protocol_tcp' => 'TCP',
    'iptables_label_protocol_udp' => 'UDP',
    'iptables_label_action_allow' => 'allow',
    'iptables_label_action_deny' => 'deny',
    'tab_heading_iptables_status' => 'Status',
    'iptable_status_description' => 'The following text is the result of command <code>iptables -L</code>.',
    'ip6table_status_description' => 'The following text is the result of command <code>ip6tables -L</code>.',

    // 2019/11/12
    'overview_reset_data_circle' => 'Reset Data Circle',
    'overview_reset_action_logs' => 'Reset Action Logs',
    'overview_thread_rows' => 'Rows',
    'overview_thread_table' => 'Table',
    'overview_text_reset_data_circle_1' => 'Would you like to reset current data circle?',
    'overview_text_reset_data_circle_2' => 'Performing this action will remove all data from current data circle and rebuild data tables.',
    'overview_text_reset_action_logs' => 'Would you like to remove all action logs?',
    'reset_data_circle' => 'Data circle tables have been reset.',
    'reset_action_logs' => 'Action logs have been removed.',

    // 2019/11/13
    'overview_heading_messenger' => 'Messenger Modules',
    'overview_label_telegram' => 'Telegram',
    'overview_note_telegram' => 'Send notifications to your Telegram channel.',
    'overview_label_line_notofy' => 'Line Notify',
    'overview_note_line_notify' => 'Send notifications to your Line group.',
    'overview_label_sendgrid' => 'SendGrid',
    'overview_note_sendgrid' => 'Send notifications to your email through SendGrid API.',

    // 2019/12/13
    'log_label_cache_time' => 'Report generated time',

    // @since 0.1.8
    'reason_invalid_ip' => 'Invalid IP address.',
    'reason_deny_ip' => 'Denied by IP component.',
    'reason_allow_ip' => 'Allowed by IP component.',
    'reason_component_ip' => 'Denied by IP component.',
    'reason_component_rdns' => 'Denied by RDNS component.',
    'reason_component_header' => 'Denied by Header component.',
    'reason_component_useragent' => 'Denied by User-agent component.',
    'reason_component_trusted_robot' => 'Identified as fake search engine.',

    // @since 0.2.0
    'operation_note_useragent' => 'Block requests without user-agent information.',
    'operation_note_rdns' => 'Block requests without RDNS record.',
    'operation_note_header' => 'Block requests without common header information.',
    'operation_note_trustedbot' => 'Block requests which are identified as fake search engine.',
    'operation_note_ip' => 'Block requests by the rules set by IP Manager.',

    'setting_label_record_attempt_detection_period' => 'Detection Period',
    'setting_desc_record_attempt_detection_period' => 'Check the time difference between now and last failed attempt. Failure attempts within the time difference are recorded. A bigger value of this filed means more strict. (unit: second)',
    'setting_label_record_attempt_reset' => 'Reset',
    'setting_desc_record_attempt_reset' => 'Reset counter after n seconds.',

    // @since 0.3.0
    'messenger_label_host' => 'Host',
    'messenger_label_port' => 'Port',
    'messenger_label_user' => 'User',
    'messenger_label_pass' => 'Password',
    'messenger_label_type' => 'Type',
    'messenger_heading_mailgun' => 'MailGun',
    'messenger_heading_smtp' => 'SMTP',
    'messenger_heading_php_mail' => 'Native PHP Mail',
    'messenger_label_webhook_url' => 'Webhook URL',
    'messenger_label_bot_token' => 'Bot Token',
    'messenger_heading_slack' => 'Slack',
    'messenger_heading_slack_webhook' => 'Slack Webhook',
    'messenger_heading_rocket_chat' => 'Rocket Chat',
    'messenger_label_server_url' => 'Server URL',
    'login_heading_login' => 'Login to Firewall Panel.',
    'login_btn_login' => 'Login',
    'login_message_invalid_captcha' => 'Invalid Captcha',
    'login_message_invalid_user_or_pass' => 'Invalid username or password.',

    // @since 1.0.0
    'test_msg_title' => 'Testing Message from Host: ',
    'test_msg_body' => 'Messenger module {0} has been tested and confirmed successfully.',
];
