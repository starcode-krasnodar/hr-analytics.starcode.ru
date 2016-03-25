<?php

namespace app\controllers;

use app\models\VacanciesAnalyticsForm;
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
                        'actions' => ['index', 'analytics', 'search'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAnalytics()
    {
        $analyticsModel = new VacanciesAnalyticsForm();
        if ($analyticsModel->load(\Yii::$app->request->getQueryParams())) {
            $analyticsModel->process();
        }
        return $this->render('analytics', [
            'model' => $analyticsModel,
            'totalCount' => $analyticsModel->getTotalCount(),
            'salaryMax' => $analyticsModel->getSalaryMax(),
            'salaryMin' => $analyticsModel->getSalaryMin(),
            'salaryAverage' => $analyticsModel->getSalaryAverage(),
        ]);
    }

    public function actionSearch()
    {
        $searchModel = new VacanciesSearchForm();
        if ($searchModel->load(\Yii::$app->request->getQueryParams())) {
            $dataProvider = $searchModel->search();
        } else {
            $dataProvider = new ArrayDataProvider();
        }

        return $this->render('search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}