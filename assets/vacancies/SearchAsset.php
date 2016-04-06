<?php

namespace app\assets\vacancies;

use yii\web\AssetBundle;

class SearchAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/vacancies/search.js',
    ];
    public $css = [
        'css/vacancies/search.css',
    ];
    public $depends = [
        'app\assets\SelectizeJsAsset',
    ];
}