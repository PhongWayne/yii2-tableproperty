<?php

namespace targetmedia\tableproperty\controllers;

use Yii;
use targetmedia\tableproperty\models\TableProperty;
use targetmedia\tableproperty\models\TablePropertySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\controllers\BackendController;
use targetmedia\tableproperty\models\TableFieldMsg;

/**
 * PropertyController implements the CRUD actions for TableProperty model.
 */
class PropertyController extends BackendController
{
    public function behaviors() {
        $parent_access = parent::behaviors();
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
                ],
            ],
            'access' => $parent_access['access'],
        ];
    }

    /**
     * Lists all TableProperty models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TablePropertySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TableProperty model.
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
     * Creates a new TableProperty model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TableProperty();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TableFieldMsg model via Ajax request using kartik/widget/Editable
     *
     * @author Wayne
     * @param unknown $tableName
     * @return string[]|unknown[]|string
     */
    public function actionUpdate($tableName, $is_new = false)
    {
            $model = TableProperty::find()->where(['table_name' => $tableName])->all();

            if (isset(\Yii::$app->request->post()['hasEditable'])) {
                return TableFieldMsg::updateViaAjax(\Yii::$app->request->post());
            }

            return $this->render('update', [
                'model' => $model,
                'tableName' => $tableName
            ]);
    }

    /**
     * Deletes an existing TableProperty model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSync(){
        $cmd = 'DESCRIBE ';
        $conn = Yii::$app->db;
        $table = 'table_property';
        $rows = [];

        $allTableNames = isset(Yii::$app->params['whitelist_tables']) ? Yii::$app->params['whitelist_tables'] : [];
        if(empty($allTableNames)) {
            Yii::$app->session->setFlash( 'sync-error',yii::t('doctool','Please configure table list in params section.') );
            return $this->redirect(Yii::$app->request->referrer);
        }
        foreach($allTableNames as $tablename) {
            $query = $conn->createCommand($cmd . '`'.$tablename.'`');
            $columns = $query->queryAll();
            foreach($columns as $fieldName) {
               $rows[] =  array($tablename,$fieldName['Field']);
            }
        }
//         dd1($rows);
        $re = $conn->createCommand()->batchInsert($table, ['table_name','field_name'], $rows)->execute();
        if($re > 0) {
            Yii::$app->session->setFlash( 'sync-ok',yii::t('doctool','Insert '.$re.' records') );
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Finds the TableProperty model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TableProperty the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TableProperty::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
