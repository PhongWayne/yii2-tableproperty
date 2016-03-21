<?php

namespace targetmedia\tableproperty\controllers;

use Yii;
use targetmedia\tableproperty\models\TableFieldMsg;
use targetmedia\tableproperty\models\TableFieldMsgSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\controllers\BackendController;

/**
 * FieldMsgController implements the CRUD actions for TableFieldMsg model.
 */
class FieldMsgController extends BackendController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * Lists all TableFieldMsg models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TableFieldMsgSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TableFieldMsg model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TableFieldMsg model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TableFieldMsg();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Render partial for modal translation
     *
     * @author Wayne
     * @param integer $table_field_value_id
     * @return renderPartial modal _update_msg_modal
     */
    public function actionUpdateMsgModal($table_field_value_id)
    {
        $models = TableFieldMsg::find()->where(['field_value_id' => $table_field_value_id])->all();
        return $this->renderPartial('_update_msg_modal', [
            'models' => $models,
            'table_field_value_id' => $table_field_value_id
        ]);
    }
    /**
     * Updates an existing TableFieldMsg model.
     * If updation is successful, the browser will be redirected to the page request before.
     *
     * @author Wayne
     * @param integer $fieldValueId
     * @return \yii\web\Response $_SERVER['HTTP_REFERER']
     */
    public function actionUpdate($fieldValueId)
    {
        if (Yii::$app->request->post()) {
            $translationList = Yii::$app->request->post('language');
            foreach($translationList as $lang_code => $translation) {
                $model = TableFieldMsg::find()->where(['field_value_id' => $fieldValueId, 'language' => $lang_code])->one();
                $model->translation = $translation;
                $model->save();
            }
            return $this->redirect(Yii::$app->request->referrer . '#'. $fieldValueId .'-cont');
        }
    }

    /**
     * Deletes an existing TableFieldMsg model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TableFieldMsg model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TableFieldMsg the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TableFieldMsg::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
