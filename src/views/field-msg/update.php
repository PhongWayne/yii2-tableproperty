<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TableFieldMsg */

$this->title = 'Update Table Field Msg: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Table Field Msgs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="table-field-msg-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
