<?php

function get_host() {
  if ((!$host = $_SERVER['HTTP_HOST']) && (!$host = $_SERVER['SERVER_NAME'])) {
    $host = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
  } else if (in_array('HTTP_X_FORWARDED_HOST', $_SERVER) && 
                                    $host = $_SERVER['HTTP_X_FORWARDED_HOST']) {
    $elements = explode(',', $host);
    $host = trim(end($elements));
  }
  // Remove port number from host
  $host = preg_replace('/:\d+$/', '', $host);
  return trim($host);
}

$debug = false;
switch (get_host()) {
  # staging urls
  case 'application.local':
    $yii='/var/www/yii-1.1.14.f0fee9/framework/yii.php';
    $config=dirname(__FILE__).'/../protected/config/main-staging.php';
    $debug = true;
    break;

  # production urls
  case '{{ production url }}':
    $yii='/home/yii-1.1.14.f0fee9/framework/yii.php';
    $config=dirname(__FILE__).'/../protected/config/main-production.php'; 
    break;

  default:
    header('Location: 404.html');
}

if ($debug) {

  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
  // remove the following lines when in production mode
  defined('YII_DEBUG') or define('YII_DEBUG',true);
  // specify how many levels of call stack should be shown in each log message
  defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
}

require_once($yii);
Yii::createWebApplication($config)->run();

?>