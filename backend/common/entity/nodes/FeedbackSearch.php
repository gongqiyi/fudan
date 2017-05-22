<?php

namespace common\entity\nodes;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\entity\nodes\FeedbackModel;

/**
 * FeedbackSearch represents the model behind the search form about `common\entity\nodes\FeedbackModel`.
 */
class FeedbackSearch extends FeedbackModel
{
    public $searchStartTime;
    public $searchEndTime;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'create_time','pid','searchStartTime','searchEndTime'], 'integer'],
            [['content'], 'safe'],
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
        $query = FeedbackModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => array_key_exists('page_size',Yii::$app->params)?Yii::$app->params['page_size']:15,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
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
            'pid'=>0,
            'status' => $this->status,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content])

            ->andFilterCompare('create_time',$this->searchStartTime,'>')
            ->andFilterCompare('create_time',$this->searchEndTime,'<');

        return $dataProvider;
    }
}
