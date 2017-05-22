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
 * uploadimg
 * */

var uploadimgApp = function () {

  var _config = parent.uploadApp.getConfig(self.name),

  // 缩略图大小
    percentages = {},
    ratio = window.devicePixelRatio || 1,
    thumbnailWidth = 105 * ratio,
    thumbnailHeight = 105 * ratio,

    //notify 信息提示配置
    notifyConf = {
      layout:'center',
      animation: {
        open  : 'animated bounceInDown',
        close : 'animated bounceOutUp'
      }
    },

    // 判断浏览器是否支持图片的base64
    isSupportBase64 = ( function() {
      var data = new Image();
      var support = true;
      data.onload = data.onerror = function() {
        if( this.width != 1 || this.height != 1 ) {
          support = false;
        }
      };
      data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
      return support;
    })(),

    tpl_list = template.compile('<li>' +
      '<p class="imgWrap" title="<%=name%>"><img draggable="false" src="<%=(thumb.length > 0?thumb[0].file:savepath+savename)%>"></p>' +
      '<p class="success"><span class="iconfont">&#xe621;</span></p>' +
      '</li>');

  /**
   * 在webuploader中生成选中的本地图片
   * @param uploader
   * @param file
   * @returns {*|jQuery|HTMLElement}
   */
  var addFileFun = function(uploader, file){

    var $li = $( '<li id="' + file.id + '">' +
        '<p class="imgWrap" title="'+ file.name +'"></p>'+
        '<p class="progress"><span></span></p>' +
        '</li>' ),

      $btns = $('<div class="file-panel"><span class="cancel">删除</span></div>').appendTo( $li ),
      $prgress = $li.find('p.progress span'),
      $wrap = $li.find( 'p.imgWrap' ),
      $info = $('<p class="error"></p>'),

      showError = function( code ) {
        switch( code ) {
          case 'exceed_size':
            text = '文件大小超出';
            break;
          case 'interrupt':
            text = '上传暂停';
            break;
          default:
            text = '上传失败，请重试';
            break;
        }
        $info.text( text ).appendTo( $li );
      };

    if ( file.getStatus() === 'invalid' ) {
      showError( file.statusText );
    } else {
      $wrap.text( '预览中' );
      uploader.makeThumb( file, function( error, src ) {
        var img;

        if (error || !isSupportBase64) { // ie67下不能预览缩略图
          $wrap.text( '不能预览' );
          return;
        }

        img = $('<img src="'+src+'">');
        $wrap.empty().append( img );

      }, thumbnailWidth, thumbnailHeight );

      percentages[ file.id ] = [ file.size, 0 ];
      file.rotation = 0;
    }

    file.on('statuschange', function( cur, prev ) {
      if ( prev === 'progress' ) {
        $prgress.hide().width(0);
      } else if ( prev === 'queued' ) {
        $li.off( 'mouseenter mouseleave' );
        $btns.remove();
      }

      // 成功
      if ( cur === 'error' || cur === 'invalid' ) {
        showError( file.statusText );
        percentages[ file.id ][ 1 ] = 1;
      } else if ( cur === 'interrupt' ) {
        showError( 'interrupt' );
      } else if ( cur === 'queued' ) {
        $info.remove();
        $prgress.css('display', 'block');
        percentages[ file.id ][ 1 ] = 0;
      } else if ( cur === 'progress' ) {
        $info.remove();
        $prgress.css('display', 'block');
      } else if ( cur === 'complete' ) {
        $prgress.hide().width(0);
      }

      $li.removeClass( 'state-' + prev ).addClass( 'state-' + cur );
    });

    $btns.on( 'click', 'span', function() {
      uploader.removeFile( file );
    });

    return $li;
  };

  /**
   * 添加远程图片
   */
  var remoteImageFun = function(){

    var $show = $('#remote_image_show'),

      $info = $('#remote_image_info'),

      img_info = {width:'- ',height:'- '},

      tpl_remoteimg = template.compile('图片宽度：<%=width%>px &nbsp; 图片高度：<%=height%>px' +
        '<input type="hidden" name="width" value="<%=width%>">' +
        '<input type="hidden" name="height" value="<%=height%>">'),

      // 加载和获取图片信息
      _loadImg = function($this){
        var img_src = $this.val();
        if(!img_src){
          $show.attr('style','').html('');
          $info.html(tpl_remoteimg(img_info));

          return;
        }
        $show.html('').css({backgroundImage:'url(../images/loading.gif)',backgroundSize:'auto'});

        if(img_src && !/^\/{1}/.test(img_src) && !/^http{1}s?:\/\/{1}/.test(img_src)) img_src = '/' + img_src;
        var _img = new Image();
        _img.onload = function(){
          img_info.width = _img.width;
          img_info.height = _img.height;
          $info.html(tpl_remoteimg(img_info));
          $show.css({backgroundImage:'url('+ img_src +')',backgroundSize:'contain'});
        };
        _img.onerror = function(){
          $info.html(tpl_remoteimg(img_info));
          $show.attr('style','').html('<p>图片加载失败！</p>');
        };
        _img.src = img_src;
      };

    $info.html(tpl_remoteimg(img_info));

    $('#j_remote_image').bind({
      keyup:function(){
        _loadImg($(this));
      }
    });

    //屏蔽右键
    window.document.oncontextmenu = function(e){
      if(e.srcElement.name == "remoteImage") return false;
    };
  };

  /**
   * 在线管理
   * */
  var manageImageFun = function(){

    var $pagination = $('#j_pagination'),
      $tabmanage = $('a[href="#tab-manage"]'),
      $manageCnt = $('#manage_cnt'),
      getManageList = function(page){
        $.ajax({
          type: 'get',
          url: _config.serverManage,
          data: {page:page,folder:_config.formData.folder},
          dataType: 'json',
          beforeSend: function (XMLHttpRequest) {
            commonApp.loading('数据加载中，请稍后...');
          },
          complete: function () {
            commonApp.loading(false);
          },
          error: function (XMLHttpRequest, textStatus, errorThrown) {
            commonApp.notify.error(errorThrown+' '+ textStatus + ' ' + XMLHttpRequest.status,notifyConf);
          },
          success: function (result) {
            var _html = [],
              selected = $manageCnt.data('selected') || {};
            for (var i in result.datalist){
              var _id = result.datalist[i].savename.replace('.',''),
                _class = '';
              if(selected[_id]) _class = 'active';
              _html.push($(tpl_list(result.datalist[i])).attr('id',_id).addClass(_class).data('file',result.datalist[i]));
            }
            $manageCnt.html(_html);

            //显示分页
            laypage({
              cont: $pagination,
              skin:false,
              pages: parseInt(result.pages),
              curr: page || 1,
              first: 1,
              last: parseInt(result.pages),
              prev: '<span class="iconfont">&#xe61d;</span>',
              next: '<span class="iconfont">&#xe622;</span>',
              jump: function(obj, first){
                if(!first) getManageList(obj.curr);
              }
            });
          }
        });
      };



    //刷新和加载在线列表
    $tabmanage.on('show.bs.tab', function (e) {
      var $this = $(this),
        _refresh = $this.data('refresh');

      if(typeof _refresh == 'undefined' || _refresh){
        getManageList();
        $this.data('refresh',false);
      }
    });
  };

  return {
    init:function(){
      remoteImageFun();
      manageImageFun();
    },
    addFile:addFileFun,
    percentages:percentages,
    lang:{
      classifier:'张'
    }
  }
}();