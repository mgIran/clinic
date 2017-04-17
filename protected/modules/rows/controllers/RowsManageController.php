<?php

class RowsManageController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'backend' => array('admin', 'const', 'index', 'view', 'delete', 'create', 'update', 'updateConst', 'add', 'remove', 'order')
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'checkAccess',
            'postOnly + delete',
        );
    }

    public function actions()
    {
        return array(
            'order' => array(
                'class' => 'ext.yiiSortableModel.actions.AjaxSortingAction',
            )
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new RowsHomepage;

        if (isset($_POST['RowsHomepage'])) {
            $model->attributes = $_POST['RowsHomepage'];
            $model->const_query = 0;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '<span class="icon-check"></span>&nbsp;&nbsp;اطلاعات با موفقیت ذخیره شد.');
                $this->redirect(array('update?step=2&id='.$model->id));
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['RowsHomepage'])) {
            $model->attributes = $_POST['RowsHomepage'];
            $model->const_query = 0;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '<span class="icon-check"></span>&nbsp;&nbsp;اطلاعات با موفقیت ویرایش شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('RowsHomepage');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new RowsHomepage('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['RowsHomepage']))
            $model->attributes = $_GET['RowsHomepage'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return RowsHomepage the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = RowsHomepage::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param RowsHomepage $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'rows-homepage-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAdd()
    {
        if (isset($_POST) && isset($_POST['row_id']) && isset($_POST['book_id'])) {
            $book_id = (int)$_POST['book_id'];
            $row_id = (int)$_POST['row_id'];
            $model = new RowBookRel();
            $model->book_id = $book_id;
            $model->row_id = $row_id;
            if ($model->save())
                echo CJSON::encode(array('status' => true));
            else
                echo CJSON::encode(array('status' => false));
        }
    }

    public function actionRemove()
    {
        if (isset($_POST) && isset($_POST['row_id']) && isset($_POST['book_id'])) {
            $book_id = (int)$_POST['book_id'];
            $row_id = (int)$_POST['row_id'];
            $model = RowBookRel::model()->findByAttributes(array('book_id' => $book_id, 'row_id' => $row_id));
            if ($model && $model->delete())
                echo CJSON::encode(array('status' => true));
            else
                echo CJSON::encode(array('status' => false));
        }
    }

    /**
     * Manages all models.
     */
    public function actionConst()
    {
        $model = new RowsHomepage('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['RowsHomepage']))
            $model->attributes = $_GET['RowsHomepage'];

        $this->render('const_admin', array(
            'model' => $model,
        ));
    }

    /**
     * update const rows.
     */
    public function actionUpdateConst($id)
    {
        $model = $this->loadModel($id);
        if (isset($_POST['RowsHomepage'])) {
            $model->attributes = $_POST['RowsHomepage'];
            $model->const_query = 1;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '<span class="icon-check"></span>&nbsp;&nbsp;اطلاعات با موفقیت ذخیره شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        $this->render('update_const', array(
            'model' => $model,
        ));
    }
}