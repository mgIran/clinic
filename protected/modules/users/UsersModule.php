<?php

class UsersModule extends CWebModule
{
	public function init()
	{
        $this->defaultController = 'manage';
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'users.models.*',
			'users.components.*',
		));
	}

    public $controllerMap = array(
        'manage' => 'users.controllers.UsersManageController',
        'roles' => 'users.controllers.UsersRolesController',
        'credit' => 'users.controllers.UsersCreditController',
        'public' => 'users.controllers.UsersPublicController',
        'bon' => 'users.controllers.UsersBonController',
    );

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
