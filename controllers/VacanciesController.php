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
        $analyticsModel = new VacanciesAnalyticsForm();
        if ($analyticsModel->load(\Yii::$app->request->getQueryParams())) {
            $analyticsModel->process();
        }
        return $this->render('analytics', [
            'model' => $analyticsModel,
            'totalCount' => $analyticsModel->getTotalCount(),
            'totalCountWithSalary' => $analyticsModel->getTotalCountWithSalary(),
            'totalCountWithSalaryPercent' => $analyticsModel->getTotalCountWithSalaryPercent(),
            'salaryMax' => $analyticsModel->getSalaryMax(),
            'salaryMin' => $analyticsModel->getSalaryMin(),
            'salaryAverage' => $analyticsModel->getSalaryAverage(),
            'employmentCount' => $analyticsModel->getEmploymentCount(),
            'employmentCountPercent' => $analyticsModel->getEmploymentCountPercent(),
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