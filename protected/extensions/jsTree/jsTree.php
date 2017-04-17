<?php
class jsTree extends CInputWidget
{
    protected $publishedAssetsPath;
    public $data = NULL;
    public $name = NULL;
    public $selected = array();

    public function init()
    {
        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl() . '/css/jsTree.min.css');
        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl() . '/css/bootstrap-theme.min.css');
        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl() . '/css/jsTree.style.css');
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl() . '/js/jsTree.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl() . '/js/jsTree.script.js', CClientScript::POS_END);
        self::renderView();
    }

    public function getAssetsUrl()
    {
        if (!isset($this->publishedAssetsPath)) {
            $assetsSourcePath = Yii::getPathOfAlias('application.extensions.jsTree.assets');

            $publishedAssetsPath = Yii::app()->assetManager->publish($assetsSourcePath, false, -1);

            return $this->publishedAssetsPath = $publishedAssetsPath;
        } else return $this->publishedAssetsPath;
    }

    private function renderView()
    {
        $liTags = '';
        foreach ($this->data as $module => $controller)
            $liTags .= $this->getListItem($module, $controller);

        $this->render('view', array('liTags' => $liTags));
    }

    protected function getListItem($module, $controller)
    {
        $temp = '';

        $temp .=
            '<li id="' . $module . '">
                <span class="js-tree-title">' . Yii::t('actions', $module) . '</span>
                <ul>';

        foreach ($controller as $controllerID => $actions) {
            $temp .=
                '<li id="' . $module . '-' . $controllerID . '">
                    <span class="js-tree-title">' . Yii::t('actions', $module . ucfirst($controllerID)) . '</span>
                    <ul>';

            foreach ($actions as $action)
                $temp .=
                    '<li id="' . $module . '-' . $controllerID . '-' . $action . '" ' . (in_array($module.'-'.$controllerID.'-'.$action, $this->selected) ? 'data-jstree=\'{"selected":true}\'' : '') . '>
                        <span class="js-tree-title">' . Yii::t('actions', $module . ucfirst($controllerID) . ucfirst($action)) . '</span>
                    </li>';

            $temp .= '</ul>
                </li>';
        }
        $temp .= '</ul>
            </li>';

        return $temp;
    }
}