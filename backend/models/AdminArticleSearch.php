<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AdminArticle;

/**
 * AdminArticleSearch represents the model behind the search form about `backend\models\AdminArticle`.
 */
class AdminArticleSearch extends AdminArticle
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['art_id', 'permission', 'is_recom', 'create_time'], 'integer'],
            [['title', 'img', 'datail'], 'safe'],
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
        $query = AdminArticle::find();

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
            'art_id' => $this->art_id,
            'permission' => $this->permission,
            'is_recom' => $this->is_recom,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'img', $this->img])
            ->andFilterWhere(['like', 'datail', $this->datail]);

        return $dataProvider;
    }
}
