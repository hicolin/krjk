<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AdminBankCard;

/**
 * AdminBankCardSearch represents the model behind the search form about `backend\models\AdminBankCard`.
 */
class AdminBankCardSearch extends AdminBankCard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_time'], 'integer'],
            [['title', 'beizhu', 'hk_way', 'range', 'time_limit', 'flow', 'condition', 'attention'], 'safe'],
            [['price', 'rate', 'interest'], 'number'],
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
        $query = AdminBankCard::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'rate' => $this->rate,
            'interest' => $this->interest,
            'time_limit' => $this->time_limit,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'beizhu', $this->beizhu])
            ->andFilterWhere(['like', 'hk_way', $this->hk_way])
            ->andFilterWhere(['like', 'range', $this->range])
            ->andFilterWhere(['like', 'flow', $this->flow])
            ->andFilterWhere(['like', 'condition', $this->condition])
            ->andFilterWhere(['like', 'attention', $this->attention]);

        return $dataProvider;
    }
}
