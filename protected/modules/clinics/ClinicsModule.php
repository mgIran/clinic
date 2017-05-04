<?php

class ClinicsModule extends CWebModule
{
	public $controllerMap = array(
		'manage' => 'clinics.controllers.ClinicsManageController',
		'doctor' => 'clinics.controllers.ClinicsDoctorController',
		'panel' => 'clinics.controllers.ClinicsPanelController',
	);

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'clinics.models.*',
			'clinics.components.*',
		));
	}

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
