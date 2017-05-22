<?php
/**
 * Created by PhpStorm.
 * User: Breeze
 * Date: 16/6/27
 * Time: 09:09
 */
namespace common\widgets;

use common\components\BaseNodeModel;
use TypeError;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\bootstrap\Html;
use home\controllers\NodeController;

/**
 * 要使用此挂件, 需提供context, categoryInfo 及categoryList
 * Class RelatedContent
 * @package common\widgets
 */
class RelatedContent extends Widget{

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
	 * 部件标题
	 * @var array
	 */
	public $elementTitle = ['element' => 'h4', 'text' => '相关内容', 'style' => ['font-size:18px']];

	/**
	 * 默认容器包裹元素
	 * css class widget_correlation_container
	 * @var array
	 */
	public $elementContainer = ['element' => 'div', 'class' => 'widget_related_container', 'style' => ['border:solid 1px #DDD', 'padding:10px;']];

	/**
	 * 项目父容器
	 * css class itemContainer
	 * @var array
	 */
	public $elementItemContainer = ['element' => 'ul', 'class' => 'itemContainer', 'style' => ['overflow:hidden']];

	/**
	 * 项目容器
	 * css class item
	 * @var array
	 */
	public $elementItem = ['element' => 'li', 'class' => 'item', 'style' => ['font-size:16px;', 'margin-left:10px;', 'margin-right:10px;']];

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
	 * 行内样式
	 * 使用此类型时必需声明element内的 style 样式
	 */
	const STYLE_INLINE = 0;

	/**
	 * 内联样式
	 * 使用此类型时必需声明element内的 class 及使用css定义样式表
	 */
	const STYLE_INNER = 1;

	/**
	 * 行内 + 内联
	 */
	const STYLE_BOTH = 2;

	/**
	 * 启用css样式, 默认内联样式
	 * @var int
	 */
	public $style = self::STYLE_INNER;

	/**
	 * 默认后台打开链接
	 * @var string
	 */
	public $target = '_black';

	/**
	 * 指定栏目
	 * @var null
	 */
	public $targetModelId = null;

	/**
	 * 默认css渲染
	 * @var string
	 */
	public $css = <<<STRING

STRING;

	/**
	 * @inheritdoc
	 * @param array $config
	 * @return string
	 * @throws \Exception
	 */
	public static function widget($config = [])
	{
		ob_start();
		ob_implicit_flush(false);
		try {
			/* @var $widget Widget */
			$config['class'] = get_called_class();
			$widget = Yii::createObject($config);
			$out = $widget->run();
		} catch (\Exception $e) {
			// close the output buffer opened above if it has not been closed already
			if (ob_get_level() > 0) {
				ob_end_clean();
			}
			throw $e;
		}
		return ob_get_clean() . $out;
	}


	/**
	 * create CSS
	 * @param $style array
	 * @return string
	 */
	public function buildCSS($style){
		
		if(is_array($style)){
			$cssStr = '';
			foreach($style as $key => &$item){
				// 检测及补全css分号
				if(!empty($item)){
					$lastStr = substr($item, strlen($item)-1, 1);
					if($lastStr != ';'){
						$item.=';';
					}

					// 输出css
					$cssStr .=$item;
				}
			}
			return $cssStr;
		}else{
			return $style;
		}
	}

	
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
		return $this->renderView($dataList);
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
	 * 视图数据输出
	 * @param $data
	 * @return string
	 */
	public function renderView($data){
		return $this->style != self::STYLE_INLINE && !empty($this->css) ? $this->css.$this->createContainer($data) : $this->createContainer($data);
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

	/**
	 * 创建项目
	 * @param $dataList array
	 * @return string
	 */
	public function createItem($dataList){
		if(count($dataList)){
			$content = '';
			foreach($dataList as $key => &$item){
				$content .= Html::tag($this->elementItem['element'], Html::tag('a', $item['title'], [
					'href' => $this->context->generateDetailUrl($item),
					'target' => $this->target
				]), [
					'style' => ($this->style == self::STYLE_INLINE || $this->style == self::STYLE_BOTH) ? $this->buildCSS($this->elementItem['style']) : '',
					'class' => ($this->style == self::STYLE_INNER || $this->style == self::STYLE_BOTH) && isset($this->elementItem['class']) ? $this->elementItem['class'] : ''
				]);
			}
			return $content;
		}else{
			return Html::tag($this->elementItem['element'], '无数据', [
				'style' => ($this->style == self::STYLE_INLINE || $this->style == self::STYLE_BOTH) ? $this->buildCSS($this->elementItem['style']) : '',
				'class' => ($this->style == self::STYLE_INNER || $this->style == self::STYLE_BOTH) && isset($this->elementItem['class']) ? $this->elementItem['class'] : ''
			]);
		}
	}

	/**
	 * 项目容器
	 * @param $dataList
	 * @return string
	 */
	public function createItemContainer($dataList){
		return Html::tag($this->elementItemContainer['element'], $dataList, [
			'style' => ($this->style == self::STYLE_INLINE || $this->style == self::STYLE_BOTH) ? $this->buildCSS($this->elementItemContainer['style']) : '',
			'class' => ($this->style == self::STYLE_INNER || $this->style == self::STYLE_BOTH) && isset($this->elementItemContainer['class']) ? $this->elementItemContainer['class'] : ''
		]);
	}

	/**
	 * 创建标题
	 * @return string
	 */
	public function createTitle(){
		return Html::tag($this->elementTitle['element'], $this->elementTitle['text'], [
			'style' => ($this->style == self::STYLE_INLINE || $this->style == self::STYLE_BOTH) ? $this->buildCSS($this->elementTitle['style']) : '',
			'class' => ($this->style == self::STYLE_INNER || $this->style == self::STYLE_BOTH) && isset($this->elementTitle['class']) ? $this->elementTitle['class'] : ''
		]);
	}

	/**
	 * 创建widget容器
	 * @param $dataList array
	 * @return string
	 */
	public function createContainer($dataList){
		$content = $this->createTitle().$this->createItemContainer($this->createItem($dataList));
		return Html::tag($this->elementContainer['element'], $content, [
			'style' => ($this->style == self::STYLE_INLINE || $this->style == self::STYLE_BOTH) ? $this->buildCSS($this->elementContainer['style']) : '',
			'class' => ($this->style == self::STYLE_INNER || $this->style == self::STYLE_BOTH) && isset($this->elementContainer['class']) ? $this->elementContainer['class'] : ''
		]);
	}



}