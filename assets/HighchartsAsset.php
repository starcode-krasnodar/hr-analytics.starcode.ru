<?php

namespace app\assets;

use yii\web\AssetBundle;

class HighchartsAsset extends AssetBundle
{
    public $sourcePath = '@bower/highcharts';
    public $baseUrl = '@web';
    public $js = [
        'highcharts.js',
        'modules/data.js',
    ];
}