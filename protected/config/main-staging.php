<?php

return array(
  'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
  'name'=>'chatty',
  //'theme'=>'botany',
  
  // preloading 'log' component
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
    'admin',
    // uncomment the following to enable the Gii tool
    'gii'=>array(
      'class'=>'system.gii.GiiModule',
      'password'=>'snafu',
       // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters'=>array(
        '127.0.0.1',
        '::1',
        '192.168.2.*',
      ),
    ),
  ),

  // application components
  'components'=>array(
    'mail' => array(
      'class' => 'ext.mail.YiiMail',
      'transportType' => 'smtp',
      'viewPath' => 'application.views.mail',
      'logging' => true,
      'dryRun' => false,
      'transportOptions' => array(
        'host'=>'mail.internode.on.net',
      ),
    ),
    'user'=>array(
      'allowAutoLogin'=>true,
      'loginUrl' => array('/user/login'),
    ),
    'widgetFactory'=>array(
            'class'=>'CWidgetFactory',
      'widgets'=>array(
        'CGridView'=>array(
          'cssFile'=>false,
        ),
        'CDetailView'=>array(
          'cssFile'=>false,
        ),
      )
    ),
    // uncomment the following to enable URLs in path-format
    'urlManager'=>array(
      'urlFormat'=>'path',
      'showScriptName'=>false,
      'rules'=>array(
        // passes categoryId and productId
        '<controller:\w+>/detail/<categoryId:\d+>/<productId:\d+>'
            =>'<controller>/detail',

        // cms frontend
        'p/<id:\d+>/<title:.*?>'
            =>'p/view',

        // default rules
        '<controller:\w+>/<id:\d+>/<title:\d+>'
            =>'<controller>/view',
        '<controller:\w+>/<id:\d+>'
            =>'<controller>/view',
        '<controller:\w+>/<action:\w+>/<id:\d+>'
            =>'<controller>/<action>',
        '<controller:\w+>/<action:\w+>'
            =>'<controller>/<action>',
      ),
    ),
    // uncomment the following to use a MySQL database
    'db'=>array(
      'connectionString'       => 'mysql:host=localhost;dbname=chatty',
      'emulatePrepare'         => true,
      'username'               => 'chatty_user',
      'password'               => 'r3act0r',
      'charset'               => 'utf8',
      'schemaCachingDuration'  => 3600,
      'tablePrefix'           => '',
      //'enableParamLogging' => true,
      //'enableProfiling' => true
    ),
    'errorHandler'=>array(
      // use 'site/error' action to display errors
      'errorAction'=>'site/error',
    ),
    'log'=>array(
      'class'=>'CLogRouter',
      'routes'=>array(
        array(
          'class'=>'CFileLogRoute',
          'levels'=>'error, warning, info',
          'enabled' => true,
        ),
        // uncomment the following to show log messages on web pages
        //array('class'=>'CWebLogRoute',),
      ),
    ),
    'authManager'=>array(
      'class'=>'CDbAuthManager',
      'connectionID'=>'db',
    ),
    'session'=>array(
      'class'=>'CDbHttpSession',
      'connectionID'=>'db',
      'sessionName'=>strtoupper(str_replace('.','',$_SERVER['HTTP_HOST'])),
      'autoCreateSessionTable'=>false, // run once as 'true' then set to 'false'
    ),
  ),

  // application-level parameters that can be accessed
  // using Yii::app()->params['paramName']
  'params'=>array(
    'emailSubjectPrefix'=>'',
    'supportEmail'=>'support@reactordigital.com.au',
    'fromEmail'=>'noreply@'.$_SERVER['HTTP_HOST'],
    'imageUploadPath'=>DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR,
    
    //**********************************
    //Settings for Product Categories
    //**********************************
    //Defines the maximum number of levels for the Product Categories ie 1 = main category then sub categories
    'maxCategoryLevels' => 1, 
    
    //defines at what levels products can be attached to ie array(1) = only sub categories, array(0,1) = level 0 and 1 etc
    'productAtCategoryLevels' => array(1), 
    
    
    //**********************************
    //Settings for Products
    //**********************************
    //Defines the style of the Product Categories within the Product Create/Edit form - Valid options are: 'dropList','dropListMulti','checkBoxes'
    'categoryDisplayMode' => 'checkBoxes',
    
    //Defines weather the Specifications field is displayed/used
    'displaySpecifications' => false,
  ),
);
