<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TableProperty */

$this->title = 'Create Table Property';
$this->params['breadcrumbs'][] = ['label' => 'Table Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-property-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
