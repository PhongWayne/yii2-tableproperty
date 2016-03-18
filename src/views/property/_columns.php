<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    ['class' => '\kartik\grid\SerialColumn'],
    [
        'attribute' => 'table_name'
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'vAlign' => 'middle',
        'template' => '{update}',
        'buttons' => [
            'update' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                Url::to(['update','tableName' => $model->table_name]),
                                ['title' => Yii::t('app', 'History')]
                    );
            },
        ]
    ]
];