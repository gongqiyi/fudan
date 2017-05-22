// +----------------------------------------------------------------------
// | nantong
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/4/24.
// +----------------------------------------------------------------------

/**
 * order
 * */

var orderApp = function () {

  /**
   * 初始化
   * */

  var _initFun = function () {

    // 订单受理
    $('#j_order_accept').click(function(){
      var $this = $(this);
      commonApp.dialog.warning('您确定要受理此订单吗？',{
        confirm:function(){
          _ajaxOrder($this,3);
        }
      });
      return false;
    });

    // 取消订单
    $('#j_order_close').click(function(){
      var $this = $(this);
      commonApp.dialog.warning('您确定要关闭此订单吗？',{
        confirm:function(){
          _ajaxOrder($this,0);
        }
      });
      return false;
    });

    // 发货
    $('#j_order_post').click(function(){
      commonApp.dialog.default(['发货',$('#tpl_order_post').html()],{
        confirm:function(){
          var $dialog = $(this),
            $form = $dialog.find('form');

          if(!$form.find('input[name="data[post_company]"]').val() || !$form.find('input[name="data[post_number]"]').val()){
            commonApp.notify.error('快递公司和运单号必须填写');
            return false;
          }

          $.ajax({
            type: 'post',
            url: $form.attr('action'),
            data:$form.serialize(),
            dataType: 'json',
            beforeSend: function (XMLHttpRequest) {
              commonApp.loading('系统操作中，请稍后...');
            },
            complete: function () {
              commonApp.loading(false);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
              commonApp.notify.error('系统操作失败');
            },
            success: function (result) {
              if (result.status == 1) {
                commonApp.notify.success('操作成功');
                $dialog.modal('hide');
                setTimeout(function(){
                  history.go(0);
                },2000);
              } else {
                commonApp.notify.error('系统操作失败');
              }
            }
          });
          return false;
        }
      });
    });

  };

  /**
   * 订单异步提交
   * @param $btn
   * @param value
   */
  function _ajaxOrder($btn,value){
    $.ajax({
      type: 'get',
      url: $btn.attr('href'),
      data:{
        value:value
      },
      dataType: 'json',
      beforeSend: function (XMLHttpRequest) {
        commonApp.loading('系统操作中，请稍后...');
      },
      complete: function () {
        commonApp.loading(false);
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        commonApp.notify.error('系统操作失败');
      },
      success: function (result) {
        if (result.status == 1) {
          commonApp.notify.success('操作成功');
          setTimeout(function(){
            history.go(0);
          },2000);
        } else {
          commonApp.notify.error('系统操作失败');
        }
      }
    });
  }

  return {
    init: function () {
      _initFun();
    }
  }
}();
