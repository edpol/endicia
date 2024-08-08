<?php
include("errors.php");

if(!defined('DS'))             define('DS', DIRECTORY_SEPARATOR);
if(!defined('LIB_PATH'))       define('LIB_PATH', __DIR__);
if(!defined('SITE_ROOT'))      define('SITE_ROOT',      substr(LIB_PATH,  0, strrpos(LIB_PATH,  DS)));
if(!defined('PUBLIC_ROOT'))    define('PUBLIC_ROOT',    SITE_ROOT . DS . 'public');
if(!defined('UPLOADS_FOLDER')) define('UPLOADS_FOLDER', PUBLIC_ROOT . DS . 'uploads');
if(!defined('CACHE_LIFETIME')) define('CACHE_LIFETIME', 60 * 60 * 24 * 31); // 31 days

require_once(LIB_PATH . DS . 'functions.php');
require_once(LIB_PATH . DS . 'Database.php');
