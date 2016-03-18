<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TableFieldValue */

$this->title = 'Create Table Field Value';
$this->params['breadcrumbs'][] = ['label' => 'Table Field Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-field-value-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
