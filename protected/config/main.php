<?php
return array(
	//'onBeginRequest'=>create_function('$event', 'return ob_start("ob_gzhandler");'),
	//'onEndRequest'=>create_function('$event', 'return ob_end_flush();'),
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'پزشک یار',
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
		'publishers',
		'manageBooks',
		'tickets',
		'advertises',
		'news',
		'rows',
		'places',
		'discountCodes',
		'festivals',
        'shop',
		'comments'=>array(
			//you may override default config for all connecting models
			'defaultModelConfig' => array(
				//only registered users can post comments
				'registeredOnly' => true,
				'useCaptcha' => true,
				//allow comment tree
				'allowSubcommenting' => true,
				//display comments after moderation
				'premoderate' => true,
				//action for postig comment
				'postCommentAction' => '/comments/comment/postComment',
				//super user condition(display comment list in admin view and automoderate comments)
				'isSuperuser'=>'Yii::app()->user->checkAccess("moderate")',
				//order direction for comments
				'orderComments'=>'DESC',
				'translationCategory'=>'comments',
				'showEmail' => false
			),
			//the models for commenting
			'commentableModels'=>array(
				//model with individual settings
				'Books'=>array(
					'registeredOnly'=>true,
					'useCaptcha'=> false,
					'premoderate' => true,
					'allowSubcommenting'=>true,
					'isSuperuser'=>'!Yii::app()->user->isGuest && (Yii::app()->user->type == \'admin\' || Yii::app()->user->roles == "publisher")',
					'orderComments'=>'DESC',
					//config for create link to view model page(page with comments)
					'pageUrl'=>array(
						'route'=>'book/',
						'data'=>array('id'=>'id','title'=>'title')
					),
				),
			),
			'userConfig'=>array(
				'class'=>'Users',
				'nameProperty'=>'userDetails.fa_name',
				'avatarProperty'=>'userDetails.avatar',
				'avatarFolderPath'=>'uploads/users/avatar/',
				'emailProperty'=>'email',
				'rateProperty'=>'bookRate.rate',
			),
		)
	),

	// application components
	'components'=>array(
		'yexcel' => array(
			'class' => 'ext.yexcel.Yexcel'
		),
		'userCounter' => array(
			'class' => 'application.components.UserCounter',
			'tableUsers' => 'ym_counter_users',
			'tableSave' => 'ym_counter_save',
			'autoInstallTables' => true,
			'onlineTime' => 5, // min
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'class' => 'WebUser',
			'loginUrl'=>array('/login'),
			'allowActiveSessions'=>2,
		),
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
		),
		'chartjs' => array('class' => 'chartjs.components.ChartJs'),
		// uncomment the following to enable URLs in path-format
		// @todo change rules in projects
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'appendParams'=>true,
			'rules'=>array(
				'<action:(about|contactus|help|publishers)>' => 'site/<action>',
				'<action:(login)>' => 'users/public/login',
				'<action:(logout|dashboard|googleLogin|library|transactions|downloaded)>' => 'users/public/<action>',
				'/help'=>'site/help',
				'books/<id:\d+>'=>'books/view',
				'documents/<id:\d+>/<title>'=>'pages/manage/view',
				'category/<id:\d+>/<title:(.*)>'=>'category/index',
				'news/<id:\d+>/<title:(.*)>'=>'news/manage/view',
				'news/category/<id:\d+>/<title:(.*)>'=>'news/categoriesManage/view',
				'news/tag/<id:\d+>/<title:(.*)>'=>'news/manage/tag',
				'news/index'=>'news/manage/index',
				'category/<action:\w+>'=>'bookCategories/<action>',
				'news/category/<action:\w+>'=>'news/categoriesManage/<action>',
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
		'noReplyEmail' => 'noreply@ketabic.ir',
		'SMTP' => array(
			'Host' => 'mail.ketabic.ir',
			'Port' => 587,
			'Secure' => 'tls',
			'Username' => 'noreply@ketabic.ir',
			'Password' => '!@ketabic1395',
		),
		'mailTheme'=>
			'<div style="border: 1px solid #dadada; border-radius: 4px;display: block;overflow: hidden;" ><h2 style="margin-bottom:0;box-sizing:border-box;display: block;width: 100%;background-color: #364760;line-height:60px;color:#fff;font-size: 24px;text-align: right;padding-right: 50px">کتابیک<span style="font-size: 14px;color:#f0f0f0"> - مرجع خرید و فروش و کتابخوانی آنلاین</span></h2>
             <div style="display: inline-block;width: 100%;font-family:tahoma;line-height: 28px;">
                <div style="direction:rtl;display:block;overflow:hidden;border:1px solid #efefef;text-align: center;padding:15px;">{MessageBody}</div>
             </div>
             <div style="font-size: 8pt;color: #bbb;text-align: right;font-family: tahoma;padding: 15px;">
                <a href="'.((strpos($_SERVER['SERVER_PROTOCOL'], 'https'))?'https://':'http://').$_SERVER['HTTP_HOST'].'/about">درباره</a> | <a href="'.((strpos($_SERVER['SERVER_PROTOCOL'], 'https'))?'https://':'http://').$_SERVER['HTTP_HOST'].'/help">راهنما</a>
                <span style="float: left;"> همهٔ حقوق برای کتابیک محفوظ است. ©‏ {CurrentYear} </span>
             </div></div>',
	),
);
