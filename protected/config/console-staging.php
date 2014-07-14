<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
  'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
  'name'=>'Site Console',
  'preload'=>array('log'),
  // autoloading model and component classes
  'import'=>array(
    'application.models.*',
    'application.components.*',
    'ext.WideImage.WideImage',
    'ext.CountrySelectorWidget.CountrySelectorWidget',
    'ext.mail.YiiMailMessage',
    'application.extensions.CAdvancedArBehavior',
  ),
  'modules'=>array(
    'gii'=>array(
      'class'=>'system.gii.GiiModule',
      'password'=>'sunpa',
    ),
  ),
  // application components
  'components'=>array(
    'db'=>array(
      'connectionString'         => 'mysql:host=localhost;dbname=cms_test',
      'emulatePrepare'           => true,
      'username'                 => 'codepleb',
      'password'                 => 'x35r57f',
      'charset'                  => 'utf8',
      'schemaCachingDuration'    => 3600,
      'tablePrefix'              => '',
    ),
    'log'=>array(
      'class'=>'CLogRouter',
      'routes'=>array(
        array(
          'class'    =>  'CFileLogRoute',
          'logFile'  =>  'console.log',
          'levels'   =>  'error, warning, info',
        ),
      ),
    ),
  ),
  // application-level parameters that can be accessed
  // using Yii::app()->params['paramName']
  'params'=>array(
  ),
);
