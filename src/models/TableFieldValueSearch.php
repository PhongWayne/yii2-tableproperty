<?php

namespace wayne\tableproperty\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use wayne\tableproperty\TableFieldValue;

/**
 * TableFieldValueSearch represents the model behind the search form about `app\models\TableFieldValue`.
 */
class TableFieldValueSearch extends TableFieldValue
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'table_property_id', 'order'], 'integer'],
            [['desc', 'created', 'modified'], 'safe'],
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
        $query = TableFieldValue::find();

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
            'table_property_id' => $this->table_property_id,
            'order' => $this->order,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
}
