<?php

namespace common\entity\searches;

use common\entity\models\SystemFileslogModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SystemFileslogSearch represents the model behind the search form about `common\entity\domains\SystemFileslogModel`.
 */
class SystemFileslogSearch extends SystemFileslogModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'width', 'height', 'size'], 'integer'],
            [['savename', 'name', 'folder', 'savepath', 'ext', 'type', 'thumb'], 'safe'],
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
        $query = SystemFileslogModel::find();

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

        $query->andFilterWhere([
            'id' => $this->id,
            'width' => $this->width,
            'height' => $this->height,
            'size' => $this->size,
        ]);

        $query->andFilterWhere(['like', 'savename', $this->savename])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'folder', $this->folder])
            ->andFilterWhere(['like', 'savepath', $this->savepath])
            ->andFilterWhere(['like', 'ext', $this->ext])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'thumb', $this->thumb]);

        return $dataProvider;
    }
}
