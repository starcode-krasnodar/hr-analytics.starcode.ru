<?php

namespace app\models;

use app\components\data\VacanciesDataProvider;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class VacanciesAnalyticsForm extends Model
{
    const MAX_PAGE = 19;
    const PAGE_SIZE = 100;

    const QUERY_OPERATOR_OR = 'OR';
    const QUERY_OPERATOR_AND = 'AND';

    public $queryName;
    public $queryDescription;
    public $queryOperator = 'AND';
    public $area = [];
    public $queryNameOperator = 'AND';
    public $queryDescriptionOperator = 'AND';
    public $industry;

    protected $_totalCount;
    protected $_totalCountWithSalary;
    protected $_salaryAverage;
    protected $_salaryMax;
    protected $_salaryMin;
    protected $_employmentCount = [];
    protected $_scheduleCount = [];

    public function rules()
    {
        return [
            [['queryName', 'queryDescription'], 'string'],
            [['area'], 'each', 'rule' => ['integer']],
            [['industry'], 'integer'],
            [['queryOperator', 'queryNameOperator', 'queryDescriptionOperator'], 'default', 'value' => self::QUERY_OPERATOR_AND, 'isEmpty' => true],
            [['queryOperator', 'queryNameOperator', 'queryDescriptionOperator'], 'in', 'range' => [self::QUERY_OPERATOR_AND, self::QUERY_OPERATOR_OR]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'queryName' => \Yii::t('app', 'Search by name of vacancy'),
            'queryDescription' => \Yii::t('app', 'Search by description of vacancy'),
            'area' => \Yii::t('app', 'Select search area'),
            'industry' => \Yii::t('app', 'Select industry of the company'),
        ];
    }

    public function queryOperatorLabels()
    {
        return [
            self::QUERY_OPERATOR_AND => \Yii::t('app', 'Search all words'),
            self::QUERY_OPERATOR_OR => \Yii::t('app', 'Search any word'),
        ];
    }

    public function employmentLabels()
    {
        return [
            'full' => \Yii::t('app.employment', 'Full employment'),
            'part' => \Yii::t('app.employment', 'Part-time employment'),
            'project' => \Yii::t('app.employment', 'Project work'),
            'volunteer' => \Yii::t('app.employment', 'Volunteer'),
            'probation' => \Yii::t('app.employment', 'Probation'),
        ];
    }

    public function scheduleLabels()
    {
        return [
            'fullDay' => \Yii::t('app.schedule', 'Full day'),
            'shift' => \Yii::t('app.schedule', 'Shift work'),
            'flexible' => \Yii::t('app.schedule', 'Flexible work'),
            'remote' => \Yii::t('app.schedule', 'Remote work'),
            'flyInFlyOut' => \Yii::t('app.schedule', 'Fly in fly out'),
        ];
    }

    public function getEmploymentLabel($employment)
    {
        $employmentLabels = $this->employmentLabels();
        return isset($employmentLabels[$employment]) ? $employmentLabels[$employment] : ucfirst($employment);
    }

    public function getScheduleLabel($schedule)
    {
        $scheduleLabels = $this->scheduleLabels();
        return isset($scheduleLabels[$schedule]) ? $scheduleLabels[$schedule] : ucfirst($schedule);
    }

    public function process()
    {
        $allModels = [];
        $page = 0;

        $params = [
            'area' => $this->area,
            'search_field' => ['name', 'description'],
            'currency' => 'RUR',
        ];
        if (!empty($this->industry)) {
            $params['industry'] = $this->industry;
        }

        // @see https://krasnodar.hh.ru/article/1175#simple-search
        $queryOperator = empty($this->queryOperator) ? self::QUERY_OPERATOR_AND : $this->queryOperator;
        $queryNameOperator = empty($this->queryNameOperator) ? self::QUERY_OPERATOR_AND : $this->queryNameOperator;
        $queryDescriptionOperator = empty($this->queryDescriptionOperator) ? self::QUERY_OPERATOR_AND : $this->queryDescriptionOperator;
        if (!empty($this->queryName) && !empty($this->queryDescription)) {
            $params['text'] = implode(' ' . $queryOperator . ' ', [
                'NAME:("' . str_replace(',', '" ' . $queryNameOperator . ' "', $this->queryName) . '")',
                'DESCRIPTION:("' . str_replace(',', '" ' . $queryDescriptionOperator . ' "', $this->queryDescription) . '")',
            ]);
        } elseif (!empty($this->queryName)) {
            $params['text'] = 'NAME:("' . str_replace(',', '" ' . $queryNameOperator . ' "', $this->queryName) . '")';
        } elseif (!empty($this->queryDescription)) {
            $params['text'] = 'DESCRIPTION:("' . str_replace(',', '" ' . $queryDescriptionOperator . ' "', $this->queryDescription) . '")';
        } else {
            $params['text'] = '';
        }

        $dataProvider = new VacanciesDataProvider([
            'params' => $params,
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

        $this->_salaryMax = array_reduce($allModels, [$this, 'reduceSalaryMax'], 0);
        $this->_salaryMin = array_reduce($allModels, [$this, 'reduceSalaryMin'], 0);

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
        }, 0);

        $this->_totalCount = count($allModels);
        $this->_salaryAverage = $this->_totalCountWithSalary != 0 ? round($salarySum / $this->_totalCountWithSalary) : 0;

        // employment
        $employments = [
            'full', 'part', 'project', 'volunteer', 'probation',
        ];

        foreach ($employments as $employment) {
            $page = 0;
            $params['employment'] = $employment;

            $dataProvider = new VacanciesDataProvider([
                'params' => $params,
                'pagination' => [
                    'page' => $page,
                    'pageSize' => self::PAGE_SIZE,
                ],
            ]);

            $dataProvider->prepare(true);
            $this->_employmentCount[$employment] = $dataProvider->getTotalCount();
        }

        arsort($this->_employmentCount);
        unset($params['employment']);

        // schedule
        $schedules = [
            'fullDay', 'shift', 'flexible', 'remote', 'flyInFlyOut',
        ];

        foreach ($schedules as $schedule) {
            $page = 0;
            $params['schedule'] = $schedule;

            $dataProvider = new VacanciesDataProvider([
                'params' => $params,
                'pagination' => [
                    'page' => $page,
                    'pageSize' => self::PAGE_SIZE,
                ],
            ]);

            $dataProvider->prepare(true);
            $this->_scheduleCount[$schedule] = $dataProvider->getTotalCount();
        }

        arsort($this->_scheduleCount);
    }

    protected function reduceSalaryMax($carry, $item)
    {
        if (!empty($item['salary']['to'])) {
            return max([$item['salary']['to'], $carry]);
        } elseif (!empty($item['salary']['from'])) {
            return max([$item['salary']['from'], $carry]);
        } else {
            return $carry;
        }
    }

    protected function reduceSalaryMin($carry, $item)
    {
        if (!empty($item['salary']['from'])) {
            return $carry == 0 ? $item['salary']['from'] : min([$item['salary']['from'], $carry]);
        } elseif (!empty($item['salary']['to'])) {
            return $carry == 0 ? $item['salary']['to'] : min([$item['salary']['to'], $carry]);
        } else {
            return $carry;
        }
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

    public function getEmploymentCount()
    {
        return $this->_employmentCount;
    }

    /**
     * @return array
     */
    public function getEmploymentCountPercent()
    {
        $totalCount = $this->getTotalCount();
        return array_map(function($count) use ($totalCount) {
            $percentage = $totalCount == 0 ? 0 : ($count / $totalCount);
            return $percentage * 100;
        }, $this->_employmentCount);
    }

    /**
     * @return array
     */
    public function getScheduleCount()
    {
        return $this->_scheduleCount;
    }

    /**
     * @return array
     */
    public function getScheduleCountPercent()
    {
        $totalCount = $this->getTotalCount();
        return array_map(function($count) use ($totalCount) {
            $percentage = $totalCount == 0 ? 0 : ($count / $totalCount);
            return $percentage * 100;
        }, $this->_scheduleCount);
    }
}