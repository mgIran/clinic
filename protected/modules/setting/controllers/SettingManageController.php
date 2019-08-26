<?php

class SettingManageController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $defaultAction = 'changeSetting';

    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'backend' => array(
                'changeSetting',
                'socialLinks'
            )
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'checkAccess',
        );
    }

    /**
     * Change site setting
     */
    public function actionChangeSetting()
    {
        if (isset($_POST['SiteSetting'])) {
            foreach ($_POST['SiteSetting'] as $name => $value) {
                if ($name == 'buy_credit_options') {
                    $value = explode(',', $value);
                    $field = SiteSetting::model()->findByAttributes(array('name' => $name));
                    SiteSetting::model()->updateByPk($field->id, array('value' => CJSON::encode($value)));
                } else {
                    $field = SiteSetting::model()->findByAttributes(array('name' => $name));
                    SiteSetting::model()->updateByPk($field->id, array('value' => $value));
                }
            }
            Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
            $this->refresh();
        }
        $cr = new CDbCriteria();
        $cr->order = 'id';
        $model = SiteSetting::model()->findAll($cr);
        $this->render('_general', array(
            'model' => $model
        ));
    }

    /**
     * Change site setting
     */
    public function actionSocialLinks()
    {
        $model = SiteSetting::model()->findByAttributes(array('name' => 'social_links'));
        if (isset($_POST['SiteSetting'])) {
            foreach ($_POST['SiteSetting']['social_links'] as $key => $link) {
                if ($link == '')
                    unset($_POST['SiteSetting']['social_links'][$key]);
                elseif (!preg_match("~^(?:f|ht)tps?://~i", $link))
                    $_POST['SiteSetting']['social_links'][$key] = 'http://' . $_POST['SiteSetting']['social_links'][$key];
            }
            if ($_POST['SiteSetting']['social_links'])
                $social_links = CJSON::encode($_POST['SiteSetting']['social_links']);
            else
                $social_links = null;
            SiteSetting::model()->updateByPk($model->id, array('value' => $social_links));
            Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
            $this->refresh();
        }
        $this->render('_social_links', array(
            'model' => $model
        ));
    }
}
