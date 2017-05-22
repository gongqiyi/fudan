// +----------------------------------------------------------------------
// | nantong
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/4/19.
// +----------------------------------------------------------------------

/**
 * goods
 * */

var goodsApp = function () {

  /**
   * 初始化
   * */

 var $totalInventory = $('#goodsmodel-inventory');// 总库存

  var _initFun = function () {
    var $standardWrap = $('#standard_wrap'),
      $standardBtn = $standardWrap.find('.btn'),
      $skuWrap = $('#sku_wrap'),
      $skuHeader = $('#sku_header'),
      $skBody = $('#sku_body'),

      $standardInput = $('#goods_standard_input'),
      _standardVal = $standardInput.val(),
      $skuInput = $('#goods_sku_input'),
      _skuVal = $skuInput.val();

    // 初始化
    _skuVal = _skuVal != ''?JSON.parse(_skuVal):[];
    if(_skuVal.length > 0) $skuWrap.show();
    $skBody.html(template('tpl_sku_body',{sku:_skuDataHandle(_skuVal)}));

    _standardVal = _standardVal != ''?JSON.parse(_standardVal):[];
    $skuHeader.html(template('tpl_sku_header',{standard:_standardVal}));

    var parseSkuData = _parseSku(_skuVal,_standardVal);

    for (var i in _standardVal){
      var $panel = $(template('tpl_standard',_standardVal[i]));
      $standardBtn.before($panel);
      $panel.data('title',_standardVal[i].title).find('.tags_input').val(parseSkuData[_standardVal[i].title].join(','));

      _standardDelete($panel);
      _standardTagsFun($panel);
    }

    // 新增规格
    $standardBtn.click(function(){
      var $this = $(this);

      bootbox.prompt($this.text(), function(result) {
        result = $.trim(result);
        if (result !== null && result !== '') {
          var _val = $standardInput.val();
          _val = _val != ''?JSON.parse(_val):[];

          // 检查是否有重复值
          var isRepeat = false;
          for(var i in _val){
            if(_val[i].title == result){
              isRepeat = true;
              break;
            }
          }
          if(isRepeat){
            commonApp.notify.error('规格“'+result+'"已经存在');
            return false;
          }

          // 格式化值
          result = {id:null,goods_id:null,title:result,sort:null};

          // 更新value
          _val.push(result);
          $standardInput.val(JSON.stringify(_val));

          // 更新页面
          var $html = $(template('tpl_standard',result));
          $standardBtn.before($html);
          $html.data('title',result.title);
          // 绑定事件
          _standardTagsFun($html);
          _standardDelete($html);
        }
      });
    });

    // 删除规格
    function _standardDelete($panel){
      // 删除规格
      $panel.find('.close').click(function(){
        var _val = JSON.parse($standardInput.val());
        _val.splice($panel.index(),1);
        $standardInput.val(JSON.stringify(_val));
        $panel.remove();

        var _skuInfo = _getSku($standardWrap,_val);

        $skuInput.val(JSON.stringify(_skuInfo.sku));
        $skuHeader.html(template('tpl_sku_header',{standard:_skuInfo.standard}));
        $skBody.html(template('tpl_sku_body',{sku:_skuDataHandle(_skuInfo.sku)}));

        _updateInventory($skBody);

        if(_val.length < 1) $skuWrap.hide();
      });
    }

    // 商品库存
    function _standardTagsFun($panel){
      var $tag = $panel.find('.tags_input');
      $tag.tagsinput();

      /* 新增一个sku */
      $tag.on('itemAdded', function(event) {
        _standardVal = JSON.parse($standardInput.val());

        var _skuInfo = _getSku($standardWrap,_standardVal),
          _skuData = _skuInfo.sku;

        // 更新sku信息
        if($skuHeader.find('td').size() != _standardVal.length+3){
          if($skuWrap.is(':hidden')) $skuWrap.show();
          $skuHeader.html(template('tpl_sku_header',{standard:_skuInfo.standard}));

          if(!$totalInventory.attr('readonly')){
            $totalInventory.attr('readonly',true).val(0);
          }
        }else{
          _skuVal = JSON.parse($skuInput.val());
          for (var i in _skuData){
            for(var o in _skuVal){
              if(_skuData[i].sku == _skuVal[o].sku){
                _skuData[i] = _skuVal[o];
                break;
              }
            }
          }
        }
        $skuInput.val(JSON.stringify(_skuData));
        $skBody.html(template('tpl_sku_body',{sku:_skuDataHandle(_skuData)}));
      });

      /* 删除一个sku */
      $tag.on('itemRemoved', function(event) {
        var $this = $(this),
          _newSkuVal = [],
          _group = $this.parent('.panel').data('title');

        if($this.val() != ''){
         _skuVal = JSON.parse($skuInput.val());
          for (var i in _skuVal){
            var skuObj = JSON.parse(_skuVal[i].sku),
              temp = false;
            for(var s in skuObj){
              if(event.item == skuObj[s] && _group == s){
                temp = true;
                break;
              }
            }

            if(!temp) _newSkuVal.push(_skuVal[i]);
          }
        }else{
          // 更新库存信息
          var _skuInfo = _getSku($standardWrap,JSON.parse($standardInput.val()));
          $skuHeader.html(template('tpl_sku_header',{standard:_skuInfo.standard}));
          _newSkuVal = _skuInfo.sku;
        }

        $skuInput.val(JSON.stringify(_newSkuVal));
        $skBody.html(template('tpl_sku_body',{sku:_skuDataHandle(_newSkuVal)}));

        _updateInventory($skBody);
      });
    }

    // 更新sk价格、库存和编码信息
    $skBody.on('change','input',function(){
      var $this = $(this),
        _role = $this.data('role'),
        _sku = $this.parents('tr').attr('data-sku');
      _skuVal = JSON.parse($skuInput.val());
      for (var i in _skuVal){
        if(_skuVal[i].sku == _sku){
          _skuVal[i][_role] = $this.val();
          break;
        }
      }
      $skuInput.val(JSON.stringify(_skuVal));

      if(_role == 'inventory'){
        _updateInventory($skBody);
      }

    });
  };

  /**
   * 生成笛卡尔乘积算法
   * @param list 多维数组
   * @returns {*}
   */
  function _descartes(list) {
    var point = {};
    var result = [];
    var pIndex = null;
    var tempCount = 0;
    var temp = [];
    for (var i in list) {
      if (typeof list[i] == 'object') {
        point[i] = {'parent': pIndex, 'count': 0};
        pIndex = i;
      }
    }
    if (pIndex == null) {
      return list;
    }
    while (true) {
      for (var i in list) {
        tempCount = point[i]['count'];
        temp.push(list[i][tempCount]);
      }
      result.push(temp);
      temp = [];
      while (true) {
        if (point[i]['count'] + 1 >= list[i].length) {
          point[i]['count'] = 0;
          pIndex = point[i]['parent'];
          if (pIndex == null) {
            return result;
          }
          i = pIndex;
        }
        else {
          point[i]['count']++;
          break;
        }
      }
    }
  }

  /**
   * sku数据转换
   * @param $data
   * @returns {*}
   * @private
   */
  function _skuDataHandle($data){
    for (var i in $data){
      $data[i].skuString = $data[i].sku;
      $data[i].sku = JSON.parse($data[i].sku);
    }
    return $data;
  }

  /**
   * 获取sku信息
   * @param $standardWrap $ 规格最外层包裹元素
   * @param standard obj 已有规格数据
   * @private
   */
  function _getSku($standardWrap,standard){
    var _newStandardVal =[],
      _skuGroup = [],
      _skuData = [];

    // todo::处理删除整个规格情况

    $standardWrap.find('.tags_input').each(function(i){
      var $this = $(this),
        _temp = $this.val();
      if(_temp != ''){
        var _sku = _temp.split(','),
          _nsku =  [];
        for(var s = 0;s<_sku.length;s++){
          _nsku[s] = $this.parent('.panel').data('title')+'=>'+_sku[s];
        }
        _skuGroup.push(_nsku);
        // 排除空规格
        _newStandardVal.push(standard[i]);
      }
    });

    var _skuDescartes = _descartes(_skuGroup);

    for(var i in _skuDescartes){
      var _sku = {};
      for (var s=0;s<_skuDescartes[i].length;s++){
        var temp = _skuDescartes[i][s].split('=>');
        _sku[temp[0]] = temp[1];
      }
      _skuData.push({
        id:null,
        goods_id:null,
        standard_id:null,
        sku:JSON.stringify(_sku),
        price:null,
        inventory:null,
        sales:null,
        code:null
      });
    }

    return {
      standard:_newStandardVal,
      sku:_skuData
    }
  }

  /**
   * 更新总库存
   * @param $skBody
   * @private
   */
  function _updateInventory($skBody){
    var _inventory = 0,
    $inventory = $skBody.find('input[data-role="inventory"]');
    $inventory.each(function(){
      var _val = $(this).val();
      if(_val != '') _inventory += parseInt(_val);
    });

    $totalInventory.val(_inventory);

    if($skBody.children().size() < 1){
      $totalInventory.attr('readonly',false).val(0);
    }
  }

  /**
   * 解析sku
   * @param $skuData
   * @param $standardData
 * @private
   */
  function _parseSku($skuData,$standardData){
    var _result = [];

    for (var i in $standardData){
      _result[$standardData[i].title] = [];
    }

    for (var i in $skuData){
      for (var s in $skuData[i].sku){
        if(_result[s].indexOf($skuData[i].sku[s]) == -1) _result[s].push($skuData[i].sku[s]);
      }
    }

    return _result;
  }

  return {
    init: _initFun,
    // 活动地图
    map:function(){
      /*$('#goodsmodel-location').focus(function(){
        alert('弹出坐标选择');
      });*/
    }
  }
}();
