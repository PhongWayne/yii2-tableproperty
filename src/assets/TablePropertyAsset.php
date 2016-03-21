<?php

namespace targetmedia\tableproperty\assets;

use yii\web\AssetBundle;
use Yii;

class TablePropertyAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $css = [
        'css' => 'css/tableProperty.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $publishOptions = [
        'forceCopy' => true,
    ];
}
