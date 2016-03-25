<?php

namespace app\components\data;

use app\components\auth\clients\Hh;
use Exception;
use Yii;
use yii\authclient\Collection;
use yii\data\BaseDataProvider;

class VacanciesDataProvider extends ApiResponseDataProvider
{
    public $url = 'vacancies';
}