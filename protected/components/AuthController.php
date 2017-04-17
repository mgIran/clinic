<?php
/**
 * AuthController is the customized base controller class with authentication filters.
 */
class AuthController extends CController
{

    public function getAllActions($type = 'all')
    {
        $controllers = array();
        $temp = $this->getActions($type);

        if (is_array($temp))
            $controllers = $temp;

        $modules = new Metadata;
        $modules = $modules->getModules();
        foreach ($modules as $module) {
            $temp = $this->getActions($type, $module);
            if (is_array($temp))
                $controllers = array_merge($controllers, $temp);
        }
        return $controllers;
    }

    public function getActions($type, $module = null)
    {

        $controllersAddress = 'application.controllers';
        if (!is_null($module))
            $controllersAddress = 'application.modules.' . $module . '.controllers';
        else
            $module = 'base';

        $classes = array();
        foreach (glob(Yii::getPathOfAlias($controllersAddress) . "/*Controller.php") as $controller) {
            $class = basename($controller, ".php");

            if (!@class_exists($class))
                Yii::import($controllersAddress . '.' . $class, true);

            if (method_exists($class, 'actionsType')) {
                if ($type == 'all')
                    $classes[$module][$class] = $class::actionsType();
                else {
                    $temp = $class::actionsType();
                    $array = array();
                    foreach ($temp as $key => $value)
                        if ($key == $type)
                            $array = $value;
                    if (!empty($array))
                        $classes[$module][$class] = $array;
                }
            }
        }
        return $classes;
    }

    /**
     * Check user permissions
     *
     * @param CFilterChain $filterChain
     * @throws CHttpException if the current user is guest or current user does not have access
     */
    public function filterCheckAccess($filterChain)
    {
        if (Yii::app()->user->isGuest) {
            if (isset($filterChain->controller->actionsType()['frontend']) && in_array($filterChain->action->id, $filterChain->controller->actionsType()['frontend'])) {
                Yii::app()->user->returnUrl = Yii::app()->request->pathInfo;
                $this->redirect(array('/login'));
            }
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        if (!Yii::app()->user->isGuest && Yii::app()->user->type == 'admin' && isset($filterChain->controller->actionsType()['frontend']) && in_array($filterChain->action->id, $filterChain->controller->actionsType()['frontend'])) {
            Yii::app()->user->returnUrl = Yii::app()->request->pathInfo;
            $this->redirect(array('/login'));
        }

        $moduleID = is_null($filterChain->controller->module) ? 'base' : $filterChain->controller->module->name;
        $controllerID = (($moduleID == 'base') ? '' : ucfirst($moduleID)) . ucfirst($filterChain->controller->id) . 'Controller';
        $actionID = $filterChain->action->id;
        $role = Yii::app()->user->roles;
        $userType = Yii::app()->user->type;
        if ($role == 'superAdmin')
            $filterChain->run();
        else {
            if ($userType == 'admin') {
                Yii::app()->getModule('admins');
                $roleID = AdminRoles::model()->findByAttributes(array('role' => $role))->id;
                $permissions = AdminRolePermissions::model()->find('module_id = :modID AND controller_id = :conID AND role_id = :rolID', array(
                    ':modID' => $moduleID,
                    ':conID' => $controllerID,
                    ':rolID' => $roleID
                ));
            } else {
                Yii::app()->getModule('users');
                $roleID = UserRoles::model()->findByAttributes(array('role' => $role))->id;
                $permissions = UserRolePermissions::model()->find('module_id = :modID AND controller_id = :conID AND role_id = :rolID', array(
                    ':modID' => $moduleID,
                    ':conID' => $controllerID,
                    ':rolID' => $roleID
                ));
            }

            if ($permissions) {
                $actions = explode(',', $permissions->actions);
                if (in_array($actionID, $actions))
                    $filterChain->run();
                else
                    throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
            } else
                throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
    }
}