<?php
return array(
	//'onBeginRequest'=>create_function('$event', 'return ob_start("ob_gzhandler");'),
	//'onEndRequest'=>create_function('$event', 'return ob_end_flush();'),
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'ویزیت 365',
	'timeZone' => 'Asia/Tehran',
	'theme' => 'abound',
	'sourceLanguage' => '00',
	'language' => 'fa_ir',
	// preloading 'log' component
	'preload'=>array('log','userCounter'),

	// autoloading model and component classes
	'import'=>array(
		'application.vendor.*',
		'application.models.*',
		'application.components.*',
		'ext.yiiSortableModel.models.*',
		'application.modules.places.models.*',
		'application.modules.users.models.*',
        'ext.dropZoneUploader.*',
		'application.modules.users.components.*',
		'application.modules.setting.models.*'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'admins',
		'clinics',
		'users',
		'setting',
		'pages',
		'tickets',
		'places',
		'holidays',
        'slideshow',
	),

	// application components
	'components'=>array(
//		'yexcel' => array(
//			'class' => 'ext.yexcel.Yexcel'
//		),
        'JWT' => array(
            'class' => 'ext.jwt.JWT',
            'key' => base64_encode(md5('Rahbod-Visit365-1396')),
        ),
        'JWS' => array(
            'class' => 'ext.jwt.JWT',
            'key' => base64_encode(sha1('Rahbod-Visit365-1396')),
        ),
        'session' => array(
            'class' => 'YmDbHttpSession',
            'autoStart' => false,
            'connectionID' => 'db',
            'sessionTableName' => 'ym_sessions',
            'timeout' => 1800
        ),
		'userCounter' => array(
			'class' => 'application.components.UserCounter',
			'tableUsers' => 'ym_counter_users',
			'tableSave' => 'ym_counter_save',
			'autoInstallTables' => true,
			'onlineTime' => 10, // min
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'class' => 'WebUser',
			'loginUrl'=>array('/login'),
//			'allowActiveSessions'=>2,
		),
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
		),
		// uncomment the following to enable URLs in path-format
		// @todo change rules in projects
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'appendParams'=>true,
			'rules'=>array(
				'api/<action:\w+>'=>'api/<action>',
				'<action:(about|contactus|help|publishers|search)>' => 'site/<action>',
				'<action:(logout|dashboard|googleLogin|library|transactions|downloaded|login|register)>' => 'users/public/<action>',
				'clinics/manage/<action:(updatePersonnel|removePersonnel|resetPass)>/<clinic:\d+>/<person:\d+>' => 'clinics/manage/<action>',
				'clinics/manage/<action:(addPersonnel|addNewPersonnel|adminPersonnel)>/<clinic:\d+>' => 'clinics/manage/<action>',
				'/help'=>'site/help',
				'search/<id:\d+>'=>'reservation/search',
				'users/<id:\d+>/clinic/<clinic:\d+>'=>'users/public/viewProfile',
				'users/<id:\d+>'=>'users/public/viewProfile',
				'<module:\w+>/<id:\d+>'=>'<module>/manage/view',
				'<module:\w+>/<controller:\w+>'=>'<module>/<controller>/index',
				'<controller:\w+>/<action:\w+>/<id:\d+>/<title:(.*)>'=>'<controller>/<action>',
				'<controller:\w+>/<id:\d+>/<title:(.*)>'=>'<controller>/view',
				'<module:\w+>/<controller:\w+>/<id:\d+>/<title:\w+>'=>'<module>/<controller>/view',
				'<module:\w+>/<action:\w+>/<id:\d+>/<title:(.*)>'=>'<module>/manage/<action>',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<module>/<controller>/<action>/view',
				'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
				'<module:\w+>/<controller:\w+>/<id:\d+>'=>'<module>/<controller>/view',
			),
		),

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class' => 'CFileLogRoute',
					'levels'=>'error, warning, trace, info',
					'categories'=>'application.*',
				),
				// uncomment the following to show log messages on web pages
				array(
					'class' => 'CWebLogRoute',
					'enabled' => YII_DEBUG,
					'levels'=>'error, warning, trace, info',
					'categories'=>'application.*',
					'showInFireBug' => true,
				),
			),
		),
		'clientScript'=>array(
			//'class'=>'ext.minScript.components.ExtMinScript',
			'coreScriptPosition' => CClientScript::POS_HEAD,
			'defaultScriptFilePosition' => CClientScript::POS_END,
		),
	),
	'controllerMap' => array(
		'min' => array(
			'class' =>'ext.minScript.controllers.ExtMinScriptController',
		),
		'category' => array(
			'class' =>'application.controllers.BookCategoriesController',
		),
//		'news.category' => array(
//            'class' =>'application.modules.news.controllers.NewsCategoriesManageController',
//        ),
	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// @todo change webmail of emails
		'adminEmail'=>'webmaster@ketabic.ir',
		'noReplyEmail' => 'noreply@visit365.ir',
		'SMTP' => array(
			'Host' => 'mail.visit365.ir',
			'Secure' => 'ssl',
			'Port' => '465',
			'Username' => 'noreply@visit365.ir',
			'Password' => '@#visit1396',
		),
		'mailTheme'=>
			'<div style="border: 1px solid #dadada; border-radius: 4px;display: block;overflow: hidden;" ><h2 style="margin-bottom:0;box-sizing:border-box;display: block;width: 100%;background-color: #18b29e;line-height:60px;color:#fff;font-size: 24px;text-align: right;padding-right: 50px">ویزیت 365<span style="font-size: 14px;color:#f0f0f0"> - رزرو اینترنتی پزشک</span></h2>
             <div style="display: inline-block;width: 100%;font-family:tahoma;line-height: 28px;">
                <div style="direction:rtl;display:block;overflow:hidden;border:1px solid #efefef;text-align: center;padding:15px;">{MessageBody}</div>
             </div>
             <div style="font-size: 8pt;color: #bbb;text-align: right;font-family: tahoma;padding: 15px;">
                <a href="' .((strpos($_SERVER['SERVER_PROTOCOL'], 'https'))?'https://':'http://').$_SERVER['HTTP_HOST'].'/about">درباره</a> | <a href="'.((strpos($_SERVER['SERVER_PROTOCOL'], 'https'))?'https://':'http://').$_SERVER['HTTP_HOST'].'/help">راهنما</a>
                <span style="float: left;"> همهٔ حقوق برای ویزیت 365 محفوظ است. ©‏ {CurrentYear} </span>
             </div></div>',
	),
);
