<?php

namespace app\widgets\AreaSelect2\assets;

use yii\web\AssetBundle;

class WidgetAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/AreaSelect2/web';
    public $baseUrl = '@web';
    public $css = [
        'css/widget.css',
    ];
    public $js = [
        'js/widget.js',
    ];
    public $depends = [
        'app\assets\Select2Asset',
    ];
}