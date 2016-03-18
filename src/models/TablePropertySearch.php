<?php

namespace wayne\tableproperty\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use wayne\tableproperty\models\TableProperty;

/**
 * TablePropertySearch represents the model behind the search form about `app\models\TableProperty`.
 */
class TablePropertySearch extends TableProperty
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['table_name', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TableProperty::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'table_name', $this->table_name]);

        $query->groupBy('table_name');
        return $dataProvider;
    }
}
