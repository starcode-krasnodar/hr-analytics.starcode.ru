<?php

namespace app\assets\controllers;

use yii\web\AssetBundle;

class VacanciesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/vacancies.js',
    ];
    public $css = [
        'css/vacancies.css',
    ];
    public $depends = [
        'app\assets\HighchartsAsset',
        'app\assets\SelectizeJsAsset',
    ];
}