<?php

namespace app\assets\vacancies;

use yii\web\AssetBundle;

class AnalyticsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/vacancies/analytics.js',
    ];
    public $css = [
        'css/vacancies/analytics.css',
    ];
    public $depends = [
        'app\assets\HighchartsAsset',
        'app\assets\SelectizeJsAsset',
    ];
}