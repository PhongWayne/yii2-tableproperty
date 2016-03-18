<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TableFieldMsg */

$this->title = 'Create Table Field Msg';
$this->params['breadcrumbs'][] = ['label' => 'Table Field Msgs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-field-msg-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
