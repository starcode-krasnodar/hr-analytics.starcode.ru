<?php

namespace app\controllers;

use app\models\VacanciesSearchForm;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

class VacanciesController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Search vacancies.
     *
     * @return string
     */
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