<?php

namespace app\controllers;

use app\models\VacanciesSearchForm;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class VacanciesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['analytics', 'search'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionAnalytics()
    {
        $model = new VacanciesSearchForm();
        $isLoad = $model->load(\Yii::$app->request->getQueryParams());
        return $this->render('analytics', [
            'model' => $model,
            'isLoad' => $isLoad,
        ]);
    }

    public function actionSearch()
    {
        $model = new VacanciesSearchForm();
        if ($model->load(\Yii::$app->request->getQueryParams())) {
            $dataProvider = $model->search();
        } else {
            $dataProvider = new ArrayDataProvider();
        }

        return $this->render('search', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
}