<?php

namespace app\assets;

use yii\web\AssetBundle;

class SelectizeJsAsset extends AssetBundle
{
    public $sourcePath = '@bower/selectize/dist';
    public $baseUrl = '@web';
    public $css = [
        'css/selectize.bootstrap3.css',
    ];
    public $js = [
        'js/standalone/selectize.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}