<?php

namespace app\models;

use app\components\data\VacanciesDataProvider;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\helpers\ArrayHelper;

/**
 * @property $allModels array
 * @property $totalCount int
 * @property $totalCountWithSalary int
 * @property $totalCountWithSalaryPercent float
 * @property $salaryAverage int
 * @property $salaryMax int
 * @property $salaryMin int
 * @property $employmentCount array
 * @property $employmentCountPercent array
 * @property $scheduleCount array
 * @property $scheduleCountPercent array
 */
class VacanciesSearchForm extends Model
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

    protected $_allModels;
    protected $_totalCount;
    protected $_totalCountWithSalary;
    protected $_salaryAverage;
    protected $_salaryMax;
    protected $_salaryMin;
    protected $_employmentCount;
    protected $_scheduleCount;

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

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'queryName' => \Yii::t('app', 'Search by name of vacancy'),
            'queryDescription' => \Yii::t('app', 'Search by description of vacancy'),
            'area' => \Yii::t('app', 'Select search area'),
            'industry' => \Yii::t('app', 'Select industry of the company'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'queryName' => \Yii::t('app', 'Name'),
            'queryDescription' => \Yii::t('app', 'Description'),
            'area' => \Yii::t('app', 'Area'),
            'industry' => \Yii::t('app', 'Industry'),
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

    /**
     * @return DataProviderInterface
     */
    public function search()
    {
        return new VacanciesDataProvider([
            'params' => $this->buildParams(),
        ]);
    }

    /**
     * @return array|null
     */
    public function getAllModels()
    {
        if ($this->_allModels === null) {
            $this->_allModels = [];
            $page = 0;

            $dataProvider = new VacanciesDataProvider([
                'params' => $this->buildParams(),
                'pagination' => [
                    'page' => $page,
                    'pageSize' => self::PAGE_SIZE,
                ],
            ]);

            while ($page++ < self::MAX_PAGE && ($models = $dataProvider->getModels())) {
                $this->_allModels = ArrayHelper::merge($this->_allModels, $models);
                $dataProvider->pagination->setPage($page);
                $dataProvider->prepare(true);
            }
        }
        return $this->_allModels;
    }

    /**
     * @return mixed
     */
    public function getSalaryAverage()
    {
        if ($this->_salaryAverage === null) {
            $salarySum = 0.0;
            $allModels = $this->getAllModels();
            foreach ($allModels as $model) {
                if (!empty($model['salary'])) {
                    $to = $model['salary']['to'];
                    $from = $model['salary']['from'];
                    if (!empty($to) && !empty($from)) {
                        $salarySum += round($to + $from) / 2;
                    } else if (empty($from)) {
                        $salarySum += $to;
                    } else if (empty($to)) {
                        $salarySum += $from;
                    }
                }
            }

            $this->_salaryAverage = $this->getTotalCountWithSalary() != 0 ? round($salarySum / $this->getTotalCountWithSalary()) : 0;
        }
        return $this->_salaryAverage;
    }

    /**
     * @return mixed
     */
    public function getTotalCount()
    {
        if ($this->_totalCount === null) {
            $this->_totalCount = count($this->getAllModels());
        }
        return $this->_totalCount;
    }

    /**
     * @return mixed
     */
    public function getSalaryMax()
    {
        if ($this->_salaryMax === null) {
            $this->_salaryMax = 0;
            $allModels = $this->getAllModels();
            foreach ($allModels as $model) {
                if (!empty($model['salary'])) {
                    $to = $model['salary']['to'];
                    $from = $model['salary']['from'];
                    $this->_salaryMax = max($this->_salaryMax, $to, $from);
                }
            }
        }
        return $this->_salaryMax;
    }

    /**
     * @return mixed
     */
    public function getSalaryMin()
    {
        if ($this->_salaryMin === null) {
            $this->_salaryMin = $this->getSalaryMax();
            $allModels = $this->getAllModels();
            foreach ($allModels as $model) {
                if (!empty($model['salary'])) {
                    $to = $model['salary']['to'];
                    $from = $model['salary']['from'];
                    if ($from !== null) {
                        $this->_salaryMin = min($this->_salaryMin, $from);
                    } else if ($to !== null) {
                        $this->_salaryMin = min($this->_salaryMin, $to);
                    }
                }
            }
        }
        return $this->_salaryMin;
    }

    /**
     * @return mixed
     */
    public function getTotalCountWithSalary()
    {
        if ($this->_totalCountWithSalary === null) {
            $this->_totalCountWithSalary = 0;
            $allModels = $this->getAllModels();
            foreach ($allModels as $model) {
                if (isset($model['salary']) && !empty($model['salary'])) {
                    $this->_totalCountWithSalary++;
                }
            }
        }
        return $this->_totalCountWithSalary;
    }

    /**
     * @return float
     */
    public function getTotalCountWithSalaryPercent()
    {
        $totalCount = $this->getTotalCount();
        $totalCountWithSalary = $this->getTotalCountWithSalary();
        $percentage = $totalCount == 0 ? 0 : ($totalCountWithSalary / $totalCount);
        return $percentage * 100;
    }

    /**
     * @return array|null
     */
    public function getEmploymentCount()
    {
        if ($this->_employmentCount === null) {
            $params = $this->buildParams();
            $this->_employmentCount = [];
            $employments = array_keys($this->employmentLabels());
            foreach ($employments as $employment) {
                $page = 0;
                $params['employment'] = $employment;

                $dataProvider = new VacanciesDataProvider([
                    'params' => $params,
                    'pagination' => [
                        'page' => $page,
                        'pageSize' => 1,
                    ],
                ]);

                $dataProvider->prepare(true);
                $this->_employmentCount[$employment] = $dataProvider->getTotalCount();
            }

            arsort($this->_employmentCount);
        }
        return $this->_employmentCount;
    }

    /**
     * @return array
     */
    public function getEmploymentCountPercent()
    {
        $totalCount = $this->getTotalCount();
        $employmentCount = $this->getEmploymentCount();
        $employmentCountPercent = [];
        foreach ($employmentCount as $employment => $count) {
            $percentage = $totalCount == 0 ? 0 : ($count / $totalCount);
            $employmentCountPercent[$employment] = $percentage * 100;
        }
        return $employmentCountPercent;
    }

    /**
     * @return array
     */
    public function getScheduleCount()
    {
        if ($this->_scheduleCount === null) {
            $params = $this->buildParams();
            $this->_scheduleCount = [];
            $schedules = array_keys($this->scheduleLabels());

            foreach ($schedules as $schedule) {
                $page = 0;
                $params['schedule'] = $schedule;

                $dataProvider = new VacanciesDataProvider([
                    'params' => $params,
                    'pagination' => [
                        'page' => $page,
                        'pageSize' => 1,
                    ],
                ]);

                $dataProvider->prepare(true);
                $this->_scheduleCount[$schedule] = $dataProvider->getTotalCount();
            }

            arsort($this->_scheduleCount);
        }
        return $this->_scheduleCount;
    }

    /**
     * @return array
     */
    public function getScheduleCountPercent()
    {
        $totalCount = $this->getTotalCount();
        $scheduleCount = $this->getScheduleCount();
        $scheduleCountPercent = [];
        foreach ($scheduleCount as $schedule => $count) {
            $percentage = $totalCount == 0 ? 0 : ($count / $totalCount);
            $scheduleCountPercent[$schedule] = $percentage * 100;
        }
        return $scheduleCountPercent;
    }

    /**
     * @return array
     */
    protected function buildParams()
    {
        $params = [
            'text' => $this->buildTextParam(),
            'area' => $this->area,
            'search_field' => ['name', 'description'],
            'currency' => 'RUR',
        ];
        if (!empty($this->industry)) {
            $params['industry'] = $this->industry;
        }

        return $params;
    }

    /**
     * @see https://krasnodar.hh.ru/article/1175#simple-search
     * @return string
     */
    protected function buildTextParam()
    {
        $queryOperator = empty($this->queryOperator) ? self::QUERY_OPERATOR_AND : $this->queryOperator;
        $queryNameOperator = empty($this->queryNameOperator) ? self::QUERY_OPERATOR_AND : $this->queryNameOperator;
        $queryDescriptionOperator = empty($this->queryDescriptionOperator) ? self::QUERY_OPERATOR_AND : $this->queryDescriptionOperator;
        if (!empty($this->queryName) && !empty($this->queryDescription)) {
            return implode(' ' . $queryOperator . ' ', [
                'NAME:("' . str_replace(',', '" ' . $queryNameOperator . ' "', $this->queryName) . '")',
                'DESCRIPTION:("' . str_replace(',', '" ' . $queryDescriptionOperator . ' "', $this->queryDescription) . '")',
            ]);
        } elseif (!empty($this->queryName)) {
            return 'NAME:("' . str_replace(',', '" ' . $queryNameOperator . ' "', $this->queryName) . '")';
        } elseif (!empty($this->queryDescription)) {
            return'DESCRIPTION:("' . str_replace(',', '" ' . $queryDescriptionOperator . ' "', $this->queryDescription) . '")';
        } else {
            return '';
        }
    }
}