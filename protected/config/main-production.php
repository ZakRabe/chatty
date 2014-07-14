<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'chatty',
	'theme'=>'',
	
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
	),

	// application components
	'components'=>array(
		'mail' => array(
			'class' => 'ext.mail.YiiMail',
			'transportType' => 'php',
			'viewPath' => 'application.views.mail',
			'logging' => true,
			'dryRun' => false,
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
				'<controller:\w+>/detail/<categoryId:\d+>/<productId:\d+>'=>'<controller>/detail',
                // cms frontend
                'p/<id:\d+>/<title:.*?>'=>'p/view',
				// default rules
				'<controller:\w+>/<id:\d+>/<title:\d+>'=>'<controller>/view',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=chatty',
			'emulatePrepare' => true,
			'username' => 'chatty_user',
			'password' => 'r3act0r',
			'charset' => 'utf8',
            'schemaCachingDuration'=>3600,
			'tablePrefix' => '',
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
					'levels'=>'error, warning',
				),
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
			'autoCreateSessionTable'=>false, // run ONCE as 'true' to create table
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'emailSubjectPrefix'=>'',
		'supportEmail'=>'support@reactordigital.com.au',
		'fromEmail'=>'noreply@'.$_SERVER['HTTP_HOST'],
	),
);
