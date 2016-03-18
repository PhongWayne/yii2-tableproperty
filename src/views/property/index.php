<?php

use yii\helpers\Html;
use kartik\grid\GridView as KartikGridView;
use wayne\tableproperty\assets\TablePropertyAsset;

TablePropertyAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\models\TablePropertySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Table Properties';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-property-index">

     <p></p>
     <?php if (Yii::$app->session->hasFlash('sync-error')):?>
     <div id="fail-ball" class="alert alert-danger"><?php echo Yii::$app->session->getFlash('sync-error')?></div>
    <?php elseif (Yii::$app->session->hasFlash('sync-ok')):?>
     <div id="fail-ball" class="alert alert-success"><?php echo Yii::$app->session->getFlash('sync-ok')?></div>
    <?php endif;?>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Table Property', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(yii::t('app','Sync Table Property'), ['sync'], ['class' => 'btn btn-warning pull-right']) ?>
    </p>
        <?= KartikGridView::widget([
            'dataProvider' => $dataProvider,
            'id' => 'component-grid',
            'pjax' => true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toggleDataOptions' => [
                'all' => [
                    'icon' => 'resize-full',
                    'class' => 'btn btn-default',
                    'label' => Yii::t('app', 'All'),
                    'title' => Yii::t('app', 'Show all data')
                ],
                'page' => [
                    'icon' => 'resize-small',
                    'class' => 'btn btn-default',
                    'label' => Yii::t('app', 'Page'),
                    'title' => Yii::t('app', 'Show first page data')
                ],
            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['role' => 'modal-remote', 'title' => Yii::t('app', 'Create new Component'), 'class' => 'btn btn-default']) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reload Grid')]) .
                    '{toggleData}'
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => KartikGridView::TYPE_PRIMARY,
                'heading' => '<i class="glyphicon glyphicon-list"></i> ' . $this->title,
            ],
            'hover' => true
        ]); ?>

</div>
