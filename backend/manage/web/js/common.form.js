// +----------------------------------------------------------------------
// | SimplePig
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/3/13.
// +----------------------------------------------------------------------

/**
 * 表单页公用js
 * */

var formApp = function () {

  /**
   * 初始化
   * */

  var _initFun = function () {
    /* select美化 */
    $('select[prety]').each(function(){
      var $this = $(this),
        _placeholder = $this.data('placeholder');
      if(!_placeholder) _placeholder = '';
      $this.select2({
        placeholder: _placeholder
      });
    });

    // 编辑器
    $('.j_editor').each(function(i,n){
      $(this).attr({'id':'editor_cnt_'+i});
      UE.getEditor('editor_cnt_'+i);
    });

    // 附件上传
    var $uploadSingleFile = $('.j_upload_single_file');
    if($uploadSingleFile.size() > 0) uploadUeditor.singleAttachment($uploadSingleFile);

    var $uploadMultipleFile = $('.j_upload_multiple_file');
    if($uploadMultipleFile.size() > 0) uploadUeditor.singleAttachment($uploadMultipleFile);

    // 数据关联
    $('.j_related_selector').each(function(i,n) {
      var $this = $(this),
        $input = $this.find('.related_input'),
        $count = $this.find('.related_count'),
        relatedData = function(){
          var _val = $input.val();
          return _val?_val.split(','):[];
        };

      $this.find('.related_btn').dataSelector({selectedIds: relatedData()},function(){
        $count.text(relatedData().length);
      }, function (selectedIds) {
        $count.text(selectedIds.length);
        $input.val(selectedIds.join(","));
      });
    });
  };

  /**
   * 百度编辑器扩展
   */
  var _editorPlugin = function () {
    // 第三方视频通用代码解析
    UE.registerUI('button',function(editor,uiName){
      editor.registerCommand(uiName,{
        execCommand:function(){
          var code = prompt("请输入第三方视频通用代码","");
          if (code!=null && code!=""){
            var uri = '',_w = 100,_h = 100;
            if(/^\<(iframe|embed){1}\s{1}/.test(code)){
              var _temp = $(code);
              uri = _temp.attr('src');
              _w = _temp.attr('width')||100;
              _h = _temp.attr('height')||100;
            }else{
              uri = code;
            }
            editor.execCommand("inserthtml",'<iframe src="'+uri+'" frameborder="0" width="'+_w+'" height="'+_h+'" allowfullscreen=""></iframe>');
          }
        }
      });

      return new UE.ui.Button({
        name:uiName,
        title:'插入第三方视频通用代码',
        cssRules :'background-position: -680px -40px;',
        onclick:function () {
          editor.execCommand(uiName);
        }
      });
    });
  };

  return {
    init: function () {
      _initFun();
      if(typeof UE != 'undefined') {
        _editorPlugin();
      }
    }
  }
}();
