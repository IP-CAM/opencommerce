<?php
// Site
$_['site_url']             = '';
$_['site_ssl']             = false;

// Url
$_['url_autostart']        = true;

// Language
$_['language_directory']   = 'en-gb';
$_['language_autoload']    = ['en-gb'];

// Date
$_['date_timezone']        = 'UTC';

// Database
$_['db_engine']            = 'mpdo';
$_['db_hostname']          = 'localhost';
$_['db_username']          = 'root';
$_['db_password']          = '';
$_['db_database']          = '';
$_['db_port']              = 3306;
$_['db_autostart']         = false;

// Cache
$_['cache_engine']         = 'file'; // apc, file, mem or memcached
$_['cache_expire']         = 3600;

// Session
$_['session_engine']       = 'db';
$_['session_autostart']    = true;
$_['session_name']         = 'OCSESSID';

// Template
$_['template_engine']      = 'twig';
$_['template_directory']   = '';
$_['template_cache']       = false;

// Error
$_['error_display']        = true;
$_['error_log']            = true;
$_['error_filename']       = 'error.log';

// Reponse
$_['response_header']      = ['Content-Type: text/html; charset=utf-8'];

// Autoload Configs
$_['config_autoload']      = [];

// Autoload Libraries
$_['library_autoload']     = [];

// Autoload Libraries
$_['model_autoload']       = [];

// Actions
$_['action_default']       = 'common/home';
$_['action_router']        = 'startup/router';
$_['action_error']         = 'error/not_found';
$_['action_pre_action']    = [];
$_['action_event']         = [];
$_['action_cron']          = [];
