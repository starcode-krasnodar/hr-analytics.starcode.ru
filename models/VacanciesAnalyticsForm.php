<?php

namespace app\models;

use app\components\data\VacanciesDataProvider;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class VacanciesAnalyticsForm extends Model
{
    const MAX_PAGE = 19;
    const PAGE_SIZE = 100;

    public $query;
    public $area;

    protected $_totalCount;
    protected $_totalCountWithSalary;
    protected $_salaryAverage;
    protected $_salaryMax;
    protected $_salaryMin;

    public function rules()
    {
        return [
            ['query', 'string'],
            ['area', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'query' => \Yii::t('app', 'Enter vacancy name and press Enter'),
            'area' => \Yii::t('app', 'Select search area'),
        ];
    }

    public function process()
    {
        $allModels = [];
        $page = 0;

        $dataProvider = new VacanciesDataProvider([
            'params' => [
                'text' => $this->query,
                'area' => $this->area,
                'currency' => 'RUR',
            ],
            'pagination' => [
                'page' => $page,
                'pageSize' => self::PAGE_SIZE,
            ],
        ]);

        while ($page++ < self::MAX_PAGE && ($models = $dataProvider->getModels())) {
            $allModels = ArrayHelper::merge($allModels, $models);
            $dataProvider->pagination->setPage($page);
            $dataProvider->prepare(true);
        }

        $this->_salaryMax = array_reduce($allModels, function($carry, $item) {
            if (!empty($item['salary']['to'])) {
                return max([$item['salary']['to'], $carry]);
            } elseif (!empty($item['salary']['from'])) {
                return max([$item['salary']['from'], $carry]);
            } else {
                return $carry;
            }
        }, 0);

        $this->_salaryMin = array_reduce($allModels, function($carry, $item) {
            if (!empty($item['salary']['from'])) {
                return min([$item['salary']['from'], $carry]);
            } elseif (!empty($item['salary']['to'])) {
                return min([$item['salary']['to'], $carry]);
            } else {
                return $carry;
            }
        }, 0);

        $salarySum = array_reduce($allModels, function($carry, $item) {
            if (!empty($item['salary']['to']) && !empty($item['salary']['from'])) {
                $salary = round(($item['salary']['to'] + $item['salary']['from']) / 2);
            } else if (!empty($item['salary']['to'])) {
                $salary = $item['salary']['to'];
            } else if (!empty($item['salary']['from'])) {
                $salary = $item['salary']['from'];
            } else {
                $salary = 0;
            }
            return $carry + $salary;
        }, 0);

        $this->_totalCountWithSalary = array_reduce($allModels, function($carry, $item) {
            if (isset($item['salary']) && !empty($item['salary'])) {
                return $carry + 1;
            } else {
                return $carry;
            }
        });

        $this->_totalCount = count($allModels);
        $this->_salaryAverage = round($salarySum / $this->_totalCount);
    }

    /**
     * @return mixed
     */
    public function getSalaryAverage()
    {
        return $this->_salaryAverage;
    }

    /**
     * @return mixed
     */
    public function getTotalCount()
    {
        return $this->_totalCount;
    }

    /**
     * @return mixed
     */
    public function getSalaryMax()
    {
        return $this->_salaryMax;
    }

    /**
     * @return mixed
     */
    public function getSalaryMin()
    {
        return $this->_salaryMin;
    }

    /**
     * @return mixed
     */
    public function getTotalCountWithSalary()
    {
        return $this->_totalCountWithSalary;
    }

    /**
     * @return float
     */
    public function getTotalCountWithSalaryPercent()
    {
        $percentage = $this->_totalCount == 0 ? 0 : ($this->_totalCountWithSalary / $this->_totalCount);
        return $percentage * 100;
    }
}