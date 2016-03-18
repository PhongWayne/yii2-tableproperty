<?php

namespace wayne\tableproperty\controllers;

use Yii;
use wayne\tableproperty\models\TableFieldValue;
use wayne\tableproperty\models\TableFieldValueSearch;
use app\controllers\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use wayne\tableproperty\models\TableFieldMsg;
use app\models\Language;
use yii\web\Response;
/**
 * FieldValueController implements the CRUD actions for TableFieldValue model.
 */
class FieldValueController extends BackendController
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
     * Lists all TableFieldValue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TableFieldValueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TableFieldValue model.
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
     * Creates new FieldValue, if success,
     * continue to create FieldMsg which is belong to Language
     *
     * @author Wayne
     * @return \yii\web\Response $_SERVER['HTTP_REFERER']
     */
    public function actionCreate()
    {
        if(Yii::$app->request->isAjax) {
            \Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            $_post = Yii::$app->request->post();
            $propertyId = $_post['propertyId'];
            $language_codes = Language::getAllLanguageCode();
            $maxOrder = TableFieldValue::find()->select('order')->where(['table_property_id' => $propertyId])->max('`order`');
            $model = new TableFieldValue();
            $model->table_property_id = $propertyId;
            $model->order = $maxOrder++;
            $return = false;
            try{
                if($model->save(false)) {
                    foreach($language_codes as $language_code => $language) {
                        $fieldMsgModel = new TableFieldMsg();
                        $fieldMsgModel->field_value_id = $model->id;
                        $fieldMsgModel->language = $language_code;
                        $fieldMsgModel->save(false);
                        $return = true;
                    }
                }
            } catch (\Exception $e) {
                switch ($e->getCode()) {
                    case 23000:
                        $return = false;
                        break;
                    default:
                        $return = false;
                }
            }
            $jsonData['success'] = ($return) ? 1 : 0;
            return $jsonData;
        }
    }

    /**
     * Updates an existing TableFieldValue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TableFieldValue model.
     * If deletion is successful,continue to delete existing TableFieldMsg models
     * then the browser will be redirected to the page request before.
     *
     *@author Wayne
     * @return \yii\web\Response $_SERVER['HTTP_REFERER']
     */
    public function actionDelete()
    {
        if(Yii::$app->request->isAjax) {
            $_post = Yii::$app->request->post();
            $table_field_value_id = $_post['fieldValueId'];
            $fieldValueModel = $this->findModel($table_field_value_id);
            $return = ($fieldValueModel->delete()) ? true : false;
            $jsonData['success'] = ($return) ? 1 : 0;
            \Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return $jsonData;
        }
    }

    /**
     * Finds the TableFieldValue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TableFieldValue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TableFieldValue::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Changes the order for TableFieldValue models
     *
     * @author Wayne
     * @return string $jsonData
     */
    public function actionChangeOrder()
    {
        if(Yii::$app->request->isAjax) {
            $_post = Yii::$app->request->post();
            $_post = $_post['val_order'];
            $orderArr = explode(',', $_post);
            $result = false;
            foreach($orderArr as $order => $fieldValueId) {
                $model = TableFieldValue::findOne($fieldValueId);
                $model->order = $order;
                $model->save();
                $return = true;
            }
            $jsonData['success'] = ($return) ? 1 : 0;
            Yii::$app->session->setFlash('change-field-value-order-ok', 'You successfully change the order.');
            \Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return $jsonData;
        }
    }

    public function actionSetDefault()
    {
        if(Yii::$app->request->isAjax) {
            $_post = Yii::$app->request->post();
            $fieldValueId = $_post['set_default'];
            $model = $this->findModel($fieldValueId);
            $propertyId = $model->table_property_id;
            TableFieldValue::updateAll(['is_default' => 0], '`table_property_id` = ' . $propertyId);
            $model->is_default = 1;
            $return = ($model->save()) ? true : false;

            $jsonData['success'] = ($return) ? 1 : 0;
            \Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return $jsonData;
        }
    }
}
