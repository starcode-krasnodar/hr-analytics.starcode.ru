<?php

namespace app\models;

use app\components\data\VacanciesDataProvider;
use yii\base\Model;
use yii\data\DataProviderInterface;

class VacanciesSearchForm extends Model
{
    const QUERY_OPERATOR_OR = 'OR';
    const QUERY_OPERATOR_AND = 'AND';

    public $queryName;
    public $queryDescription;
    public $queryOperator = 'AND';
    public $area = [];
    public $queryNameOperator = 'AND';
    public $queryDescriptionOperator = 'AND';
    public $industry;

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

    /**
     * @return DataProviderInterface
     */
    public function search()
    {
        $params = [
            'area' => $this->area,
            'industry' => empty($this->industry) ? null : $this->industry,
            'search_field' => ['name', 'description'],
        ];

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

        return new VacanciesDataProvider([
            'params' => $params,
        ]);
    }
}