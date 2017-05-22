<?php
namespace common\widgets;


use common\components\BaseNodeModel;
use common\components\home\NodeController;
use TypeError;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Created by PhpStorm.
 * User: Breeze
 * Date: 16/7/18
 * Time: 21:07
 */

class RelatedData extends Widget{

	/**
	 * 栏目列表
	 * @var $categoryList array
	 */
	public $categoryList;

	/**
	 * 当前栏目信息
	 * @var object
	 */
	public $categoryInfo;

	/**
	 * 默认5条相关内容
	 * @var int
	 */
	public $itemLimitCount = 5;

	/**
	 * 上下文
	 * @var NodeController
	 */
	public $context;

	/**
	 * 指定栏目
	 * @var null
	 */
	public $targetModelId = null;

	/**
	 * 入口
	 * @inheritdoc
	 * @return string
	 * @throws InvalidConfigException
	 * @throws TypeError
	 */
	public function run()
	{

		$this->init();
		$dataList = $this->getData();
		return $dataList;
	}

	/**
	 * 检测配置参数
	 * @throws InvalidConfigException
	 * @throws TypeError
	 */
	public function init()
	{
		if($this->context === null){
			throw new InvalidConfigException('Initialization parameter error: The "context" property must be set.');
		}

		if(!$this->context instanceof NodeController){
			throw new TypeError('Initialization parameter type error: The "context" property must be NodeController');
		}

		if($this->categoryList === null){
			throw new InvalidConfigException('Initialization parameter error: The "categoryList" property must be set.');
		}

		if($this->categoryInfo === null){
			throw new InvalidConfigException('Initialization parameter error: The "categoryInfo" property must be set.');
		}

		if(!is_array($this->categoryList)){
			throw new TypeError('Initialization parameter type error: The "categoryList" property must be array');
		}

		$this->initCorrelationCategory($this->targetModelId);
	}


	/**
	 * 实例化模型, 获取数据
	 * @param $category
	 * @param array $sort
	 * @return \yii\db\ActiveQuery
	 */
	public function model($category,$sort = []){
		$modelName = '\\common\\entity\\nodes\\'.ucwords($category['model']['name']).'Model';
		if(empty($sort)) $sort = ['sort'=>SORT_DESC,'id'=>SORT_DESC];

		/**
		 * @var $model BaseNodeModel
		 */
		$model = new $modelName();
		$result = $model->find();

		if(array_key_exists('status',$model->attributes)) $result->andWhere(['status'=>1]);
		if($sort && is_array($sort)) $result->orderBy($sort);

		return $result;
	}

	/**
	 * 多个栏目随机获取抽取栏目id
	 * @return array
	 */
	public function randChoiceCategory(){
		if(count($this->categoryList) > 1){
			return $this->categoryList[array_rand($this->categoryList)];
		}else{
			return $this->categoryList[0];
		}
	}

	/**
	 * 排除重复
	 * @param $category
	 * @return int
	 */
	public function exceptCategory($category){
		$count = count($this->categoryList);
		if($count > 1){
			foreach($this->categoryList as $key => &$value){
				if($value['id'] == $category['id']){
					unset($this->categoryList[$key]);
				}
			}
			return count($this->categoryList);
		}else{
			return $count;
		}
	}

	/**
	 * 获取栏目内容数据
	 * @return array|mixed
	 */
	public function getData(){
		$category = $this->randChoiceCategory();
		$dataList = $this->model($category)
			->select('*')
			->where(['category_id'=> $category['id']])
			->limit($this->itemLimitCount)
			->all();

		// 无数据重新查找
		if(empty($dataList)){
			// 循环生命周期中避免重复
			if($this->exceptCategory($category) > 1){
				$dataList = $this->getData();
			}
		}
		return $dataList;
	}

	/**
	 * 查找同胞栏目, 或指定栏目
	 * @param $targetModelId
	 */
	public function initCorrelationCategory($targetModelId){
		$correlationCategories = array();
		foreach($this->categoryList as &$item){
			// 获取同胞栏目
			if(empty($targetModelId)){
				if($item['model_id'] == $this->categoryInfo->model_id){
					$correlationCategories[] = $item;
				}
			}else{
				if($item['model_id'] == $targetModelId){
					$correlationCategories[] = $item;
				}
			}

		}
		$this->categoryList = $correlationCategories;
	}
}