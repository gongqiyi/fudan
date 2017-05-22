// +----------------------------------------------------------------------
// | Dookay
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2015/11/6.
// +----------------------------------------------------------------------

/**
 * upload
 * */

var uploadApp = function () {

  var _path = commonApp.getFilePath('upload.js'), // 当前js文件路径

    // 图片列表模板
    tpl_img = template.compile('<%for(var i in listfile){%><li>' +
      '<div class="pic"><div class="inner">' +
      '<img src="<%if(listfile[i].thumb.length>0){%><%=listfile[i].thumb[0].file%><%}else{%><%=listfile[i].file%><%}%>">' +
      '</div></div><div class="opt fade">' +
      '<a class="j_upload_change" href="javascript:;">更换图片</a>' +
      '<a class="j_upload_edit" href="javascript:;">编辑信息</a>' +
      '<a class="j_upload_del" href="javascript:;">删除图片</a>' +
      '</div></li><% } %>'),
    tpl_moreimg = template.compile('<%for(var i in listfile){%><li><div class="left">' +
      '<div class="pic-wraper"><div class="pic"><div class="inner"><img src="<%if(listfile[i].thumb.length>0){%><%=listfile[i].thumb[0].file%><%}else{%><%=listfile[i].file%><%}%>"></div></div></div>' +
      '<div class="opt fade"><a class="j_upload_change" href="javascript:;">更换图片</a><a class="j_upload_del" href="javascript:;">删除图片</a></div>' +
      '</div><div class="info-form">' +
      '<input class="form-control j_upload_info" name="upload_title" type="text" placeholder="图片标题" value="<%=listfile[i].title%>">' +
      '<textarea class="form-control j_upload_info" name="upload_description" placeholder="图片描述" rows="5"><%=listfile[i].description%></textarea>' +
      '<input class="form-control j_upload_info" name="upload_link" type="hidden" placeholder="图片链接" value="<%=listfile[i].link%>">'+
     '</div><div class="upload-sort j_upload_sort" title="拖动排序"><span class="iconfont">&#xe615;</span></div></li><% } %>'),

    // 已上传文件信息编辑框
    tpl_edit = template.compile('<form id="uploadfile_info_form" action="javascript:;" method="post">' +
      '<div class="form-group"><label><%=(filetype == "uploadimg"?"图片":文件)%>标题</label>' +
      '<input type="text" class="form-control" name="title" value="<%=title%>">' +
      '</div><div class="form-group"><label><%=(filetype == "uploadimg"?"图片":文件)%>描述</label>' +
      '<textarea class="form-control" name="description" rows="5"><%=description%></textarea>' +
      '</div><% if(filetype == "uploadimg"){%><div class="form-group"><label>图片链接</label>' +
      '<input type="text" class="form-control" name="link" value="<%=link%>">' +
      '</div><% } %></form>'),

  // 文件列表模板
    tpl_file = template.compile('');

  /**
   * 上传弹出框
   * @param config
   * @param callback
   * @private
   */
  var _uploadFun = function($element,config,callback){

    commonApp.dialog.iframe(null,_path + 'page/' +  config.filetype +'.html',{
      dialogIframeName:'dialog-iframe',
      dialogIframeHeight:350,
      className:'dialog-upload',
      confirm:function(){
        if(callback && typeof callback == 'function') callback(window.frames['dialog-iframe'].selectImg());
        return false;
      },
      cancel:function(){
        $element.removeData('change');
      }
    });
    $('#dialog-iframe').data('config',config);
  };


  /**
   * 绑定上传事件
   * @param $element
   * @param callback
   * @param config
   * @private
   */
  var _uploadBindFun = function($element,callback,config){
    if(!config) config = {};
    if(!callback) callback = $.noop;

    $element.each(function(){
      var $e = $(this),
        _setting = {
          multiple:false, //是否多上传
          filetype:'uploadfile',
          serverManage:null,
          formData:{
            folder:'default'
          }
        };

      // 检查是否已经绑定上传控件
      if($e.data('upload')) return;
      $e.data('upload',true);

      // 添加触发事件
      var _name = $e.data('name'),
        _econfig = $e.data('config');
      if(!_econfig) _econfig = {};
      $.extend(true,_setting,config,_econfig);

      $e.find('a.btn_upload').click(function(){
        _uploadFun($e,_setting,function(result){
          callback($e,result,_setting);
          $('#dialog-iframe').parents('.bootbox').find('button.close').trigger('click');
        });
      });

      /* 初始化 */
      if(_setting.multiple) $e.addClass('list-img-multiple');

      if(!_name) _name = 'file';
      var _$input = $e.children('input:hidden');
      if(_$input.size() < 1){
        $e.prepend('<input type="hidden" class="form-control" name="'+ _name +'">');
      }else{
        var _val = _$input.val();
        if(_val && _val != ''){
           _val = JSON.parse(_val);
          var _tpl;
          _setting.filetype == 'uploadimg'?_tpl = (_setting.multiple?tpl_moreimg:tpl_img):_tpl = tpl_file;
          $e.find('ul').html(_tpl({listfile:_val}));
          if(!_setting.multiple){
            $e.find('.btn_upload').hide();
          }else{
            // 更新索引
            $e.find('ul>li').each(function(i,e){
              $(this).data('sort',i);
            });
          }
        }
      }

      // 绑定已上传文件操作
      _bindOperate($e,_setting);
    });
  };

  /**
   * 对上传后的文件进行操作
   * @param $e
   * @private
   */
  function _bindOperate($e, config){
    var $list = $e.children('ul'),
      $btn = $e.children('.btn_upload'),
      $input = $e.children('input:hidden'),
      _type = (config.filetype == "uploadimg"?'图片':'文件');

    /* 删除文件 */
    $e.on('click','.j_upload_del',function(){
      var $this = $(this);
      commonApp.dialog.warning('您确定要删除此'+_type+'吗？',{
        confirm:function(){
          var $li = $this.parents('li'),
            index = $li.index();

          $li.fadeOut(function(){
            $li.remove();
            if(!config.multiple){
              $btn.show();
            }else{
              $e.find('ul>li').each(function(i,e){
                $(this).data('sort',i);
              });
            }
          });

          var _data = JSON.parse($input.val());
          _data.splice(index,1);
          _data.length>0?$input.val(JSON.stringify(_data)):$input.val('');
        }
      });
    });

    /* 编辑信息 */
    $e.on('click','.j_upload_edit',function(){
      var $li = $(this).parents('li'),
        index = $li.index(),
        _data = JSON.parse($input.val());

      var  _tmpdata = {filetype:config.filetype};
      $.extend(_tmpdata, _data[index]);

      commonApp.dialog.default(['修改'+ _type +'信息',tpl_edit(_tmpdata)],{
        confirm:function(){
          $.extend(_data[index],commonApp.getFormData($('#uploadfile_info_form')));
          $input.val(JSON.stringify(_data));
          commonApp.notify.success('修改'+ _type +'信息成功！');
        }
      });
    });

    // 多图片编辑信息
    $e.on('change','.j_upload_info',function(){
      var $this = $(this),
        $li = $this.parents('li'),
        index = $li.index(),
        _data = JSON.parse($input.val()),
        _temp = {};
      _temp[$this.attr('name').replace('upload_','')] = $this.val();
      $.extend(_data[index],_temp);
      $input.val(JSON.stringify(_data));
    });

    /* 更换图片 */
    $e.on('click','.j_upload_change',function(){
      var index = $(this).parents('li').index();
      $e.data('change',index);
      $btn.trigger('click');
    });

    /* 多图片排序
     * 依赖jqueryui的sortable插件
     * */
    if(typeof $.fn.sortable == 'function'){
      $list.sortable({
        handle:'.j_upload_sort',
        update:function(event, ui){
          var _data = JSON.parse($input.val()),
            _newdata = [];
          $list.children().each(function(i,e){
           var $this = $(this);
            _newdata.push(_data[$this.data('sort')]);
            $this.data('sort',i);
          });
          $input.val(JSON.stringify(_newdata));
        }
      });
      $list.disableSelection();
    }else{
      $e.on('mousedown','.j_upload_sort',function(){
        commonApp.dialog.error("图片排序功能依赖<b>“jquery-ui.min.js”</b>的<b>sortable</b>组件！");
      });
    }
  }

  /**
   * 获取配置信息
   * @returns {*}
   */
  var getConfigFun = function(id){
    var $e = $('#'+id),
      _config = $e.data('config');
    if(typeof $e.data('change') != 'undefined') _config.multiple = false;
    return _config;
  };

  /**
   * 上传图片
   * @param $element
   */

  var uploadImgFun = function($element,config){

    /* 上传图片 */
    var _config = {
      filetype:'uploadimg',
      accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/*'
      },
      thumb:{
        quality: 90,
        allowMagnify: false
      },
      formData:{
        ext:'jpg,gif,png,jpeg,bmp'
      },
      // 图片在上传前压缩
      fileSingleSizeLimit:1*1024*1024 //1M
    };
    $.extend(true,_config,config);
    _uploadBindFun($element,function($e,result,conf){
      if(result.length < 1) return;

      // 插入图片
      var _newresult = [];
      for(var i in result){
        var _file = result[i].remoteImage?result[i].remoteImage:result[i].savepath + result[i].savename;
        _newresult.push({
          title:result[i].name,
          description:'',
          link:'',
          file:_file,
          width:result[i].width,
          height:result[i].height,
          thumb:result[i].thumb
        });
      }

      //判断是否为更换图片
      var change_file_index = $e.data('change'),
        _$input = $e.children(':hidden'),
        _olddata = _$input.val();

      if(typeof change_file_index == 'undefined'){
        //选择插入模板
        if(!conf.multiple){
          $e.find('ul').append(tpl_img({listfile:_newresult}));
          $e.children('.btn_upload').hide()
        }else{
          $e.find('ul').append(tpl_moreimg({listfile:_newresult}));
          $e.find('ul>li').each(function(i,e){
            //更新索引
            $(this).data('sort',i);
          });
        }

        // 插入数据
        !_olddata?_olddata = []:_olddata = JSON.parse(_olddata);
        _$input.val(JSON.stringify(_olddata.concat(_newresult)));
      }else{

        // 更改数据
        _olddata = JSON.parse(_olddata);
        _newresult[0].title = _olddata[change_file_index].title;
        _newresult[0].description = _olddata[change_file_index].description;
        _newresult[0].link = _olddata[change_file_index].link;
        _olddata[change_file_index] = _newresult[0];
        _$input.val(JSON.stringify(_olddata));

        //更改图片
        $e.children('ul').find('li:eq('+ change_file_index +') img').attr('src',(_newresult[0].thumb.length>0?_newresult[0].thumb[0].file:_newresult[0].file));
      }


    },_config);
  };

  return {
    getConfig:getConfigFun,
    uploadImg:uploadImgFun
  }
}();