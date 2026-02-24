<?php
require_once(dirname(__FILE__) . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__));
$dotenv->load();

$debug = $_ENV['APP_DEBUG'] === 'true' ?? false;
defined('YII_DEBUG') or define('YII_DEBUG', $debug);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', $debug ? 3 : 0);

$yii=dirname(__FILE__).'/vendor/yiisoft/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
