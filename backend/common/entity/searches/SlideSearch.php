<?php

namespace common\entity\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\entity\models\SlideModel;

/**
 * SlideSearch represents the model behind the search form about `common\entity\models\SlideModel`.
 */
class SlideSearch extends SlideModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'related_data_model', 'related_data_id', 'sort', 'status', 'create_time','site_id'], 'integer'],
            [['title', 'thumb', 'thumb_mobile', 'link', 'description'], 'safe'],
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
        $query = SlideModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => array_key_exists('page_size',Yii::$app->params)?Yii::$app->params['page_size']:15,
            ],
            'sort' => [
                'defaultOrder' => [
                    'sort'=>SORT_DESC,
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
            'site_id'=>$this->site_id,
            'category_id' => $this->category_id,
            'related_data_model' => $this->related_data_model,
            'related_data_id' => $this->related_data_id,
            'sort' => $this->sort,
            'status' => $this->status,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'thumb', $this->thumb])
            ->andFilterWhere(['like', 'thumb_mobile', $this->thumb_mobile])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
