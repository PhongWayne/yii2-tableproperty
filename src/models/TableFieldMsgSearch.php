<?php

namespace app\modules\tableproperty\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tableproperty\TableFieldMsg;

/**
 * TableFieldMsgSearch represents the model behind the search form about `app\models\TableFieldMsg`.
 */
class TableFieldMsgSearch extends TableFieldMsg
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'field_value_id'], 'integer'],
            [['language', 'translation', 'created', 'modified'], 'safe'],
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
        $query = TableFieldMsg::find();

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
            'field_value_id' => $this->field_value_id,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'translation', $this->translation]);

        return $dataProvider;
    }
}
