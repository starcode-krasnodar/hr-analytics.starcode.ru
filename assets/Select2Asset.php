<?php

namespace app\assets;

use yii\web\AssetBundle;

class Select2Asset extends AssetBundle
{
    public $sourcePath = '@bower/select2/dist';
    public $baseUrl = '@web';
    public $css = [
        'css/select2.min.css',
    ];
    public $js = [
        'js/select2.full.min.js',
    ];
}