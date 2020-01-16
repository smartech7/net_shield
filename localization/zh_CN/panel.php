<?php

return [

    'reason_manual_ban' => '被管理员手动加入。',
    'reason_is_search_engine' => '搜寻引擎机器人。',
    'reason_is_google' => 'Google 机器人。',
    'reason_is_bing' => '必应机器人。',
    'reason_is_yahoo' => 'Yahoo 机器人。',
    'reason_too_many_sessions' => '太多工作阶段。',
    'reason_too_many_accesses' => '太多连线。',
    'reason_empty_js_cookie' => '无法建立 JS cookie。',
    'reason_empty_referer' => '空的来源网址。',
    'reason_reached_limit_day' => '达到每日限制。',
    'reason_reached_limit_hour' => '达到每小时限制。',
    'reason_reached_limit_minute' => '达到每分限制。',
    'reason_reached_limit_second' => '达到每秒限制。',

    // Menu
    'nav_locale' => '语系',
    'menu_firewall' => '防火墙',
    'menu_status' => '状态',
    'menu_overview' => '总览',
    'menu_settings' => '设置',
    'menu_ip_manager' => 'IP 管理员',
    'menu_xss_protection' => 'XSS 防护',
    'menu_authentication' => '网页认证',
    'menu_exclusion' => '例外清单',
    'menu_last_month' => '上月',
    'menu_this_month' => '本月',
    'menu_last_7_days' => '过去 7 日',
    'menu_yesterday' => '昨日',
    'menu_today' => '今天',
    'menu_ip_filter_logs' => '过滤器日志',
    'menu_ip_rules' => 'IP 规则',
    'menu_sessions' => '工作阶段',
    'menu_logs' => '日志',
    'menu_data_circle' => '资料周期',
    'menu_operation_status' => '运行状态',
    
    // 信息
    'error_mysql_connection' => '无法连接您的 MySQL 资料库，请检查设置值。',
    'error_mysql_driver_not_supported' => '您的系统不支持 MySQL 驱动器。',
    'error_sqlite_driver_not_supported' => '您的系统不支持 SQLite 驱动器。',
    'error_sqlite_directory_not_writable' => 'SQLite 资料驱动器需要储存目录可写入。',
    'error_redis_driver_not_supported' => '您的系统不支持 Redis 驱动器。',
    'error_file_directory_not_writable' => '档案资料驱动器需要存储目录可写入。',
    'error_logger_directory_not_writable' => '动作记录器需要存储目录可写入。',
    'success_settings_saved' => '设定值已储存。',
    
    // 其他。
    'field_not_visible' => '无法在示范模式查看此栏位。',
    'permission_required' => '需要权限。',
    
    //标头状态栏。
    'channel' => '频道',
    'mode' => '模式',
    '登出' => '登出',
    
    //设置-身份验证页面。
    'auth_heading' => '认证方式',
    'auth_description' => '响应段HTTP HTTP WWW-Authenticate定义了获取对资源的连接权限应该被使用的认证方式。',
    'auth_label_url_path' => '网址路径',
    'auth_label_username' => '用户',
    'auth_label_password' => '密码',
    'auth_btn_submit' => '送出',
    'auth_label_encrypted' => '已加密',
    'auth_label_remove' => '可移除',
    
    //设置-例外清单除页面。
    'excl_heading' => '例外清单',
    'excl_description' => '请输入要排除掉Shieldon保护的起始网址。',
    
    // IP管理器
    'ipma_heading' => 'IP管理员',
    'ipma_description' => 'IP管理员不像规格表（有效期决定资料周期），任何动作都是永久性的。',
    'ipma_label_ip' => 'IP',
    'ipma_label_order' => '顺序',
    'ipma_label_rule' => '规则',
    'ipma_label_action' => '动作',
    'ipma_label_plz_select' => '请选择',
    'ipma_label_remove_ip' => '可移除这个 IP',
    'ipma_label_allow_ip' => '提供这 个IP',
    'ipma_label_deny_ip' => '拒绝这个 IP',
    
    //日志
    'log_heading_captchas' => 'CAPTCHA 验证',
    'log_heading_pageviews' => '网页查看数',
    'log_note_captcha_last_month' => 'CAPTCHA 上月份统计',
    'log_note_pageview_last_month' => '上月份总网页查看数',
    'log_note_captcha_last_7_days' => 'CAPTCHA 过去 7 日统计',
    'log_note_pageview_last_7_days' => '过去 7 日网页检视总数',
    'log_note_pageview_this_month' => '本月份网页检视总数',
    'log_note_captcha_this_month' => 'CAPTCHA 本月份统计',
    'log_note_captcha_today' => 'CAPTCHA 今日统计',
    'log_note_pageview_today' => '今日网页检视总数',
    'log_note_pageview_yesterday' => '昨日网页检视总数',
    'log_note_captcha_yesterday' => 'CAPTCHA 昨日统计',
    
    'log_label_last_month' => '上月',
    'log_label_this_month' => '本月',
    'log_label_last_7_days' => '过去 7 日',
    'log_label_yesterday' => '昨日',
    'log_label_today' => '今日',
    'log_msg_no_logger' => '不好意思，您必须执行动作记录器才能使用这个功能。',
    'log_label_in_queue' => '排队中',
    'log_label_in_blacklist' => '在黑名单中',
    'log_label_captcha' => 'CAPTCHA验证',
    'log_label_pageviews' => '网页查看',
    'log_label_session' => '工作阶段',
    'log_label_solved' => '已解决',
    'log_label_failed' => '已失败',
    'log_label_displays' => '显示次数',
    'log_label_timezone' => '时区',
    
    //概述
    'overview_heading_data_circle' => '资料周期',
    'overview_label_mysql' => 'MySQL',
    'overview_text_rows' => '行',
    'overview_note_sql_db' => 'SQL资料库。',
    'overview_note_memory_db' => '使用记忆体的资料库。',
    'overview_label_redis' => 'Redis',
    'overview_btn_document' => '文件',
    'overview_btn_close' => '关闭',
    'overview_btn_save' => '储存',
    'overview_note_image_captcha' => '一个简易型的文字图片 CAPTCHA 验证。',
    'overview_label_image_captcha' => '图片验证码',
    'overview_note_recaptcha' => '此功能服务由 Google 提供。',
    'overview_label_recaptcha' => 'reCAPTCHA',
    'overview_heading_captcha' => 'CAPTCHA 验证模组',
    'overview_note_action_logger' => '记录每一位访客的行为。',
    'overview_label_action_logger' => '动作记录器',
    'overview_heading_logger' => '记录器',
    'overview_text_since' => '自',
    'overview_text_days' => '天',
    'overview_text_size' => '尺寸',
    'overview_note_useragent' => '分析来自访客的 User-agent　资讯。',
    'overview_label_useragent' => '用户代理',
    'overview_note_rdns' => '识别来自访客的IP反查主机名 (RDNS)。',
    'overview_label_rdns' => 'RDNS',
    'overview_note_header' => '分析来自访客的标题栏资讯。',
    'overview_label_header' => '头部长度',
    'overview_note_trustedbot' => '允许受欢迎的搜寻引擎检索您的网站',
    'overview_label_trustedbot' => '信赖的机器人',
    'overview_note_ip' => '进阶的IP位址管理功能。',
    'overview_label_ip' => 'IP',
    'overview_heading_components' => '组件',
    'overview_note_referer' => '检查HTTP来源网址资讯。',
    'overview_label_referer' => '来源网址',
    'overview_note_frequency' => '检查访客有多连续检阅网页。',
    'overview_label_frequency' => '频率',
    'overview_note_session' => '发现是否多个工作阶段由同一位置访客产生。',
    'overview_label_session' => '工作阶段',
    'overview_note_cookie' => '检查是否可以通过 JavaScript 由 JavaScript 产生 cookie。',
    'overview_label_cookie' => 'Cookie',
    'overview_heading_filters' => '过滤器',
    'overview_label_sqlite' => 'SQLite',
    'overview_note_file_system' => '档案系统。',
    'overview_label_file' => '档案',
    'overview_text_more_usages' => '更多用法细节请查阅文件。',

    // IP日志表。
    'table_heading_ip_log' => 'IP 日志表',
    'table_description_ip_log_1' => '这里是 Shieldon 记录用户的行为有异的地方。',
    'table_description_ip_log_2' => '所有过程是自动化且即时性的，您可以忽略。',
    'table_description_ip_log_3' => 'IP 日志表在新的资料周期开始后会被清除。',
    'table_label_resolved_hostname' => '反解主机名',
    'table_label_last_visit' => '上次造访',
    'table_label_flags' => '旗标',
    
    // 规则表。
    'table_heading_rule' => '规则表',
    'table_description_rule_1' => '这是Shieldon在目前的资料周期内，暂时性地允许或拒绝用户的地方。',
    'table_description_rule_2' => '所有过程是自动化且即时性的，您可以忽略。',
    'table_description_rule_3' => '规则表在新的资料周期开始时将被重设。',
    'table_label_deny_ip_temporarily' => '暂时性地拒绝此 IP',
    'table_label_deny_ip_permanently' => '永久性地拒绝此 IP',
    'table_ip_placeholder' => '请填进一个IP位址',
    'table_label_type' => '类型',
    'table_label_reason' => '理由',
    'table_label_time' => '时间',
    'table_label_remove' => '可移除',
    
    // 会话表。
    'table_heading_session' => '工作阶段表',
    'table_label_remain_seconds' => '剩余秒数',
    'table_label_priority' => '优先权',
    'table_label_status' => '状态',
    'table_label_session_id' => '工作阶段ID',
    'table_text_allowable' => '准许的',
    'table_text_expired' => '已终止',
    'table_text_waiting' => '等待中',
    'table_description_session_1' => '针对<strong>在线工作阶段控制管</strong>的即时记录。所有过程是自动化且即时性的，您可以忽略。',
    'table_description_session_2' => '注意这个只有在您有启用时才会运作。',
    'table_heading_limit' => '限制',
    'table_note_limit' => '在线工作阶段限制',
    'table_heading_period' => '期间',
    'table_note_period' => '存续期间。（分钟）',
    'table_heading_online' => '在线',
    'table_note_online' => '在线工作阶段限制。',
    
    // Xss保护。
    'xss_heading' => 'XSS 防护',
    'xss_description' => '预防跨站脚本攻击。',
    'xss_label_variable' => '变数',
    'xss_text_eradicate_injection' => '针对单一变数消除潜在的注入字串。',
    'xss_label_single_variable' => '单一变数',
    'xss_label_variable_name' => '变数名称',
    'xss_text_update_above_settings' => '更新以上设置值。',
    'xss_text_filter_cookie_variables' => '过滤全部 COOKIE 型态的变数。',
    'xss_text_filter_get_variables' => '过滤全部 GET 型态的变数。',
    'xss_text_filter_post_variables' => '过滤全部 POST 型态的变数。',
    
    // 标签
    'tab_heading_adminlogin' => '管理登录',
    'tab_heading_dialogui' => '补充介面',
    'tab_heading_captchas' => 'CAPTCHA 验证',
    'tab_heading_filters' => '过滤器',
    'tab_heading_components' => '元件',
    'tab_heading_daemon' => '守护进程',
    
    // 设置-管理员登录。
    'setting_heading_adminlogin' => '管理登录',
    'setting_label_last_modified' => '最近一次修改',
    'setting_label_password' => '密码',
    'setting_label_user' => '用户',
    
    // 设置-验证码。
    'setting_heading_recaptcha' => 'reCAPTCHA验证',
    'setting_label_recaptcha_key' => '网站金钥',
    'setting_note_recaptcha_key' => '输入您的网站的 Google reCaptcha 网站金钥。',
    'setting_label_recaptcha_secret' => '密钥',
    'setting_note_recaptcha_secret' => '输入您的网站的 Google reCaptcha 密钥。',
    'setting_label_recaptcha_version' => '版本',
    'setting_note_recaptcha_version' => '请输入相对应版本的金钥否则无法正常运作。',
    'setting_label_recaptcha_lang' => '语言代码',
    'setting_note_recaptcha_lang' => 'ISO 639-ISO 3166 代码。举例，zh-TW 代表台湾的繁体中文。',
    'setting_note_image_captcha_1' => '混合数字及英文小写字母及大写英文字母的字串。',
    'setting_note_image_captcha_2' => '只有小写英文字母和大写英文字母的字串。',
    'setting_note_image_captcha_3' => '只有数字型字串。',
    'setting_note_image_captcha_length' => '您想显示多少字元长度的 CAPTCHA 验证？',
    'setting_label_length' => '长度',
    'setting_heading_image_captcha' => '图片型验证码',
    
    // 设置-组件。
    'setting_heading_component_ip' => 'IP',
    'setting_note_component_ip' => '通过启用此选项来启动 IP 管理员。',
    'setting_heading_component_tb' => '信任的机器人',
    'setting_note_component_tb_1' => '允许受欢迎的搜寻引擎检索您的网站',
    'setting_note_component_tb_2' => '注意：把这个选项关闭将会冲击到您的 SEO，因为机器人们将会进入检查的程序。',
    'setting_label_strict_mode' => '严格模式',
    'setting_note_component_tb_3' => 'IP 反解的主机名和 IP 位址必须互相吻合。',
    'setting_heading_component_header' => '头部长度',
    'setting_note_component_header_1' => '分析来自访客的标题栏资讯。',
    'setting_note_component_header_2' => '拒绝所有没有常见的头部资讯的访客。',
    'setting_heading_component_useragent' => '用户代理',
    'setting_note_component_useragent_1' => '分析来自访客的用户代理资讯。',
    'setting_note_component_useragent_2' => '拒绝所有用户代理资讯为空值的访客。',
    'setting_heading_component_rdns' => '反向 DNS',
    'setting_note_component_rdns_1' => '一般而言，从电信业者配发的 IP 都会被设置 RDNS 记录。此选项仅在严格模式时运作。',
    'setting_note_component_rdns_2' => '所有 RDNS 记录为空值的访客将会被封锁。',

    // 设置-守护程序
    'setting_heading_enable' => '启用',
    'setting_label_data_driver' => '资料驱动器',
    'setting_note_data_driver' => '通过由执行 Shieldon 开始保护您的网站。Shieldon 防火墙只有在这个选项启用时才会运作。',
    'setting_label_driver_file' => '档案系统',
    'setting_label_driver_mysql' => 'MySQL',
    'setting_label_driver_redis' => 'Redis',
    'setting_label_driver_sqlite' => 'SQLite',
    'setting_label_mysql_host' => '主机',
    'setting_label_mysql_dbname' => '资料库名称',
    'setting_label_mysql_user' => '用户',
    'setting_label_mysql_password' => '密码',
    'setting_label_mysql_charset' => '字符集',
    'setting_label_redis_host' => '主机',
    'setting_label_redis_port' => '端口',
    'setting_label_redis_auth' => '授权密码',
    'setting_note_redis_host' => '只有需要密码时才必填。',
    'setting_label_directory' => '目录',
    'setting_note_directory' => '请填写你要存储资料的目录的绝对路径。',
    'setting_label_reset_data_cycle' => '重设资料周期',
    'setting_note_reset_data_cycle' => '自动地在每天 0:00 清除所有记录。启用这个选项能提升效能。',
    'setting_label_ip_source' => 'IP来源',
    'setting_note_ip_source' => '您的网站在CDN服务背后？如果您使用 CDN，则将这个设定值填写正确，而不是全部来自 CDN 的 IP 位址可能会被封锁。',
    'setting_label_session_limit' => '工作阶段限制',
    'setting_note_session_limit_1' => '当在线的用户数量达到限制范围，其他未在此处中的用户必须排队！',
    'setting_note_session_limit_2' => '以下图片是范例。',
    'setting_label_online_limit' => '在线限制',
    'setting_note_online_limit' => '在线人数限制的出价。',
    'setting_label_alive_period' => '存续期间',
    'setting_note_alive_period' => '单位：分钟。',
    'setting_label_logs' => '日志',
    'setting_label_action_logger' => '动作记录器',
    'setting_heading_dailogui' => '外观界面',
    'setting_label_language' => '语系',
    'setting_label_background_image' => '背景图片',
    'setting_note_background_image' => '请加入该图片的完整网址或相对路径。',
    'setting_label_background_color' => '背景颜色',
    'setting_note_background_color' => '如果您不想用背景图片的话，您可以指定一个背景颜色。',
    'setting_label_dialog_header' => '其他头部',
    'setting_text_for_example' => '列举',
    'setting_label_font_color' => '字型颜色',
    'setting_label_shadow_opacity' => '阴影不正确',
    'setting_note_shadow_opacity' => '范围从 0 到 1，表示来说，0.2 代表20% 不透明度。',
    'setting_heading_filter_frequency' => '频率',
    'setting_note_filter_frequency_1' => '不要在意人类用户，如果他们达到限制范围而被封锁，他们可以通过解决 CAPTCHA 验证解除封锁继续浏览您的网站。',
    'setting_note_filter_frequency_2' => '以下图片是范例。',
    'setting_label_secondly_limit' => '每秒限制',
    'setting_label_minutely_limit' => '每分钟限制',
    'setting_label_hourly_limit' => '每小时限制',
    'setting_label_daily_limit' => '每日限制',
    'setting_note_secondly_limit' => '每秒钟每位用户可查看的网页数量。',
    'setting_note_minutely_limit' => '每分钟每位用户可查看的网页数量。',
    'setting_note_hourly_limit' => '每小时每位用户可查看的网页数量。',
    'setting_note_daily_limit' => '每天每位用户可查看的网页数量。',
    'setting_label_filter_cookie' => 'Cookie',
    'setting_label_filter_session' => '工作阶段',
    'setting_note_filter_session' => '检测是否多个工作阶段由同一位访客产生。',
    'setting_label_filter_referer' => '来源网址',
    'setting_note_filter_referer' => '检查 HTTP 来源网址资讯。',
    'setting_label_quota' => '额度',
    'setting_note_quota' => '访问可在达到限制时将会被暂时地封锁。',
    'setting_label_buffered_time' => '缓冲的时间',
    'setting_note_buffered_time' => '在第一次造访您的网站的 n 秒后开始使用这个过滤器。',
    'setting_label_cookie_name' => 'Cookie名',
    'setting_note_cookie_name' => '只限英文字元。',
    'setting_label_cookie_value' => 'Cookie 值',
    'setting_label_cookie_domain' => 'Cookie 域名',
    'setting_text_leave_blank' => '只要留空即套用预设值。',

    // 2019/11/11
    'menu_iptables_manager' => '管理器',
    'menu_iptables_status' => '状态',
    'menu_messenger' => '通讯器',
    'messenger_heading_events' => '事件',
    'messenger_desc_events' => '什么事件是在发生的当下，你想从即时通讯模组接收通知。',
    'messenger_label_event_1' => '在当前的资料周期中永久地封锁一位使用者。',
    'messenger_desc_event_1' => '这个事件通常在当一位使用者连续错误验证太多次时发生。',
    'messenger_label_event_2' => '在系统防火墙中永久地封锁一位使用者。',
    'messenger_desc_event_2' => '这个事件通常在当一位使用者已经在资料周期中被永久性的封锁，还连续地不断尝试连接，可清楚地辨识出他们是恶意的机器人。',
    'messenger_desc_event_3' => '这个选项只有在系统层防火墙正确地设定才会生效。',
    'messenger_heading_telegram' => 'Telegram',
    'messenger_label_api_key' => 'API 金钥',
    'messenger_label_channel' => '频道',
    'messenger_heading_line_notify' => 'Line Notify',
    'messenger_label_access_token' => '连接权杖',
    'messenger_heading_sendgrid' => 'SendGrid',
    'messenger_label_sender' => '发信人',
    'messenger_label_recipients' => '收件人',
    'tab_heading_events' => '事件',
    'tab_heading_modules' => '模组',
    'setting_heading_deny_attempts' => '拒决多次尝试',
    'setting_desc_deny_attempts' => '和意图不轨的访客说再见。',
    'error_ip6tables_directory_not_writable' => 'iptables 监视资料夹需要储存目录是可写入状态。',
    'setting_label_system_firewall' => '系统防火墙',
    'setting_note_install_iptables' => '确定您已经安装<strong>iptables</strong> 以及<strong>ip6tables</strong> 在您的伺服器中，而且在<strong>crontab</strong> 中正确地采用<strong >fiewall.sh</strong>。',
    'setting_label_watching_folder' => '监视资料夹',
    'setting_label_cronjob' => '系统排程',
    'setting_note_cronjob' => '请使用这段程式码到您的伺服器中的 crontab 档案。',
    'setting_note_iptables' => '<strong>firewall.sh</strong> 将会监视在此资料夹中的变化来套用指令到 iptables 里。',
    'setting_label_deny_attempt_buffer' => '缓冲',
    'setting_desc_deny_attempt_buffer' => '连续多少次错误会触发此事件。',
    'iptable_heading' => 'Iptables 管理器',
    'iptable_description_1' => '这是 <strong>iptables</strong> 的网站介面，请小心地使用此功能。',
    'iptable_description_2' => '您只可以管理连线进来的请求。',
    'iptable_description_3' => '在您重开机您的伺服器后，在这里的规则将会被清除。使用 <strong>iptables-save</strong> 指令保存规则',
    'ip6table_description_1' => '这是 <strong>ip6tables</strong> 的网站介面，请小心地使用此功能。',
    'ip6table_description_2' => '您只可以管理连线进来的请求。',
    'ip6table_description_3' => '在您重开机您的伺服器后，在这里的规则将会被清除。使用 <strong>ip6tables-save</strong> 指令保存规则',
    'iptables_label_subnet' => '子网路',
    'iptables_label_port' => '端口',
    'iptables_label_port_custom' => '自订',
    'iptables_label_protocol' => '通讯协议',
    'iptables_label_protocol_all' => '全部',
    'iptables_label_protocol_tcp' => 'TCP',
    'iptables_label_protocol_udp' => 'UDP',
    'iptables_label_action_allow' => '允许',
    'iptables_label_action_deny' => '拒绝',
    'tab_heading_iptables_status' => '状态',
    'iptable_status_description' => '以下文字是指令 <code>iptables -L</code> 产生的结果。',
    'ip6table_status_description' => '以下文字是指令 <code>ip6tables -L</code> 产生的结果。',

    // 2019/11/12
    'overview_reset_data_circle' => '重置资料周期',
    'overview_reset_action_logs' => '重置访客动作日志',
    'overview_thread_rows' => '列',
    'overview_thread_table' => '表',
    'overview_text_reset_data_circle_1' => '您想要重置当前的资料周期吗？',
    'overview_text_reset_data_circle_2' => '进行这个动作将会移除所有目前资料周期的记录，以及重建资料表。',
    'overview_text_reset_action_logs' => '您想要重置当前的访客动作日志吗？',
    'reset_data_circle' => '资料周期表已经被重新设置。',
    'reset_action_logs' => '访客动作日志已经被清除。',

    // 2019/11/13
    'overview_heading_messenger' => '通讯器模组',
    'overview_label_telegram' => 'Telegram',
    'overview_note_telegram' => '传送通知到您的 Telegram 频道。',
    'overview_label_line_notofy' => 'Line Notify',
    'overview_note_line_notify' => '传送通知到您的 Line 群组。',
    'overview_label_sendgrid' => 'SendGrid',
    'overview_note_sendgrid' => '透过 SendGrid API 传送通知到您的 Email。',

    // 2019/12/13
    'log_label_cache_time' => '报告产生时间',

    
    'reason_invalid_ip' =>' 无效的 IP 位址。',
    'reason_deny_ip' => '被 IP 元件拒绝。',
    'reason_allow_ip' => '由 IP 元件允许。',
    'reason_component_ip' => '被 IP 元件拒绝。',
    'reason_component_rdns' => '被 RDNS 元件拒绝。',
    'reason_component_header' => '被标头元件拒绝。',
    'reason_component_useragent' => '被用户代理元件拒绝。',
    'reason_component_trusted_robot' => '被识别为假造搜寻引擎资讯。',

    'setting_label_record_attempt_detection_period' => '检测期间',
    'setting_desc_record_attempt_detection_period' => '检查现在和上次尝试失败的时间差。在时间差以内的失败尝试会被记录。数值的数值表示越加严格。（单位：秒数）',
    'setting_label_record_attempt_reset' => '重设',
    'setting_desc_record_attempt_reset' => '在 n 秒后重设计数器。',
];