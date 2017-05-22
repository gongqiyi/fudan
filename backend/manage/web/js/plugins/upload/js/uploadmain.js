// +----------------------------------------------------------------------
// | Dookay
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2015/11/9.
// +----------------------------------------------------------------------

/**
 * uploadmain
 * */

var uploadmainApp = function () {

  var _config = parent.uploadApp.getConfig(self.name), // 上传配置
    notifyConf = { //notify 信息提示配置
      layout:'center',
      animation: {
        open  : 'animated bounceInDown',
        close : 'animated bounceOutUp'
      }
    },
    $tabpane = $('.tab-pane'),
    $manage = $('#manage_cnt'),
    $uploaderWrap = $tabpane.filter('#tab-upload'), // 上传区域
    uploadType, // 上传类型
    uploader; // uploader实例

  /**
   * 初始化
   * @private
   */
  var _initFun = function(uploadtype){

    uploadType = uploadtype;

    // 检查ie浏览器是否支持上传控件
    if ( !WebUploader.Uploader.support('flash') && WebUploader.browser.ie ) {
      var flashVersion = ( function() {
        var version;

        try {
          version = navigator.plugins[ 'Shockwave Flash' ];
          version = version.description;
        } catch ( ex ) {
          try {
            version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash').GetVariable('$version');
          } catch ( ex2 ) {
            version = '0.0';
          }
        }
        version = version.match( /\d+/g );
        return parseFloat( version[ 0 ] + '.' + version[ 1 ], 10 );
      })();

      // flash 安装了但是版本过低。
      if (flashVersion) {
        (function(container) {
          window['expressinstallcallback'] = function( state ) {
            switch(state) {
              case 'Download.Cancelled':
                commonApp.notify.error('更新失败，您取消了更新！',notifyConf);
                break;
              case 'Download.Failed':
                commonApp.notify.error('更新失败！',notifyConf);
                break;
              default:
                commonApp.notify.success('安装已成功，请重新打开上传控件！',notifyConf);
                break;
            }
            delete window['expressinstallcallback'];
          };

          var swf = '../js/webuploader/expressInstall.swf';
          // insert flash object
          var html = '<object type="application/' +
            'x-shockwave-flash" data="' +  swf + '" ';

          if (WebUploader.browser.ie) {
            html += 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ';
          }

          html += 'width="100%" height="100%" style="outline:0">'  +
            '<param name="movie" value="' + swf + '" />' +
            '<param name="wmode" value="transparent" />' +
            '<param name="allowscriptaccess" value="always" />' +
            '</object>';

          container.html(html);

        })($uploaderWrap);
      } else {
        // 压根就没有安转。
        $uploaderWrap.html('<a href="http://www.adobe.com/go/getflashplayer" target="_blank" border="0"><img alt="get flash player" src="http://www.adobe.com/macromedia/style_guide/images/160x41_Get_Flash_Player.jpg" /></a>');
      }
      return false;
    } else if (!WebUploader.Uploader.support()) {
      commonApp.notify.error('检测发现上传控件不支持您的浏览器！',notifyConf);
      return false;
    }
  };

  /**
   * 实例化webupload
   * @private
   */
  var _initWebuploaderFun = function(){
    var _setting = {
      pick:'#picker', // 选择文件的按钮
      dnd: '#dndArea', // 拖拽的容器，如果不指定，则不启动。
      paste: '#tab-upload', // 此功能为通过粘贴来添加截屏的图片，如果不指定，不启用此功能。
      swf: '../js/webuploader/Uploader.swf', //
      //chunked: true, // 是否要分片处理大文件上传
      //chunkSize: 512 * 1024, // 分多大一片？ 默认大小为5M.
      fileVal:'file', //设置文件上传域的name。
      server: '',
      compress:false,// 图片上传前不进行压缩
      disableGlobalDnd: true, //如果不禁用，拖拽上传时会被打开
      fileNumLimit: 50,// 验证文件总数量, 超出则不允许加入队列
      fileSizeLimit: 200 * 1024 * 1024,    // 200 M 验证文件总大小是否超出限制, 超出则不允许加入队列
      fileSingleSizeLimit: 20 * 1024 * 1024,    // 20 M 验证单个文件大小是否超出限制, 超出则不允许加入队列。
      duplicate: true //允许重复上传
    };

    $.extend(_setting,_config);
    uploader = WebUploader.create(_setting);

    /* 拖拽时文件过滤文件 */
    uploader.on( 'dndAccept', function( items ) {
      var denied = false,
        len = items.length,
        i = 0,
      // 修改js类型
      unAllowed = 'application/javascript ';
      for ( ; i < len; i++ ) {
        // 如果在列表里面
        if ( ~unAllowed.indexOf( items[ i ].type ) ) {
          denied = true;
          break;
        }
      }
      return !denied;
    });

    uploader.on('ready', function() {
      window.uploader = uploader;
    });

    uploader.addButton({
      id: '#filePicker',
      label: '继续添加'
    });

    var $queuelist = $uploaderWrap.find('.queue-list'),
      $filelist = $uploaderWrap.find('.filelist'),
      $statusBar = $uploaderWrap.find( '.status-bar' ), // 状态栏，包括进度和控制按钮
      $upload = $statusBar.find('.btn-upload'), // 上传按钮
      $info = $statusBar.find( '.info'), // 文件总体选择信息。
      $progress = $statusBar.find( '.progress'), // 进度条
      fileCount = 0, // 添加的文件数量
      fileSize = 0, // 添加的文件总大小
      state = 'pedding',// 可能有pedding, ready, uploading, confirm, done.
      $currli, // 当前正在上传的文件生成的li  jquey对象
      percentages = {},// 所有文件的进度信息，key为file id
      $tabmanage = $('a[href="#tab-manage"]');

    // 当有文件添加进来时执行，负责view的创建
    function addFile( file ) {
      $currli = uploadType.addFile(uploader,file);
      $currli.appendTo( $filelist );
      percentages = uploadType.percentages;
    }

    // 负责view的销毁
    function removeFile( file ) {
      var $li = $('#'+file.id);

      delete percentages[ file.id ];
      updateTotalProgress();
      $li.off().find('.file-panel').off().end().remove();
    }

    function updateTotalProgress() {
      var loaded = 0,
        total = 0,
        spans = $progress.children(),
        percent;

      $.each( percentages, function( k, v ) {
        total += v[ 0 ];
        loaded += v[ 0 ] * v[ 1 ];
      } );

      percent = total ? loaded / total : 0;

      spans.eq( 0 ).text( Math.round( percent * 100 ) + '%' );
      spans.eq( 1 ).css( 'width', Math.round( percent * 100 ) + '%' );
      updateStatus();
    }

    function updateStatus() {
      var text = '', stats;

      if ( state === 'ready' ) {
        text = '共' + fileCount + uploadType.lang.classifier + '（' +
          WebUploader.formatSize( fileSize ) + '）';
      } else if ( state === 'confirm' ) {
        stats = uploader.getStats();
        if ( stats.uploadFailNum ) {
          text =  stats.successNum+ uploadType.lang.classifier + '上传成功，'+
            stats.uploadFailNum + uploadType.lang.classifier + '上传失败，<a class="retry text-primary" href="#">重新上传</a> 或 <a class="ignore text-primary" href="#">忽略</a>'
        }

      } else {
        stats = uploader.getStats();
        text = '共' + fileCount + uploadType.lang.classifier + '（' +
          WebUploader.formatSize( fileSize ) + '），已上传' + stats.successNum + uploadType.lang.classifier;
        if ( stats.uploadFailNum ) {
          text += '，失败' + stats.uploadFailNum + uploadType.lang.classifier;
        }
      }

      $info.html( text );
    }

    function setState( val ) {
      var file, stats;

      if ( val === state ) {
        return;
      }

      $upload.removeClass( 'state-' + state );
      $upload.addClass( 'state-' + val );
      state = val;

      switch ( state ) {
        case 'pedding':
          $queuelist.removeClass( 'element-invisible' );
          $filelist.hide();
          $statusBar.hide();
          uploader.refresh();
          break;

        case 'ready':
          $queuelist.addClass( 'element-invisible' );
          $( '#filePicker' ).removeClass( 'element-invisible');
          $filelist.show();
          //$statusBar.removeClass('element-invisible');
          uploader.refresh();
          break;

        case 'uploading':
          $( '#filePicker' ).addClass( 'element-invisible' );
          $progress.show();
          $upload.text( '暂停上传' );
          break;

        case 'paused':
          $progress.show();
          $upload.text( '继续上传' );
          break;

        case 'confirm':
          $progress.hide();
          $( '#filePicker' ).removeClass( 'element-invisible' );
          $upload.text( '开始上传' );

          stats = uploader.getStats();
          if ( stats.successNum && !stats.uploadFailNum ) {
            setState( 'finish' );
            return;
          }
          break;
        case 'finish':
          stats = uploader.getStats();
          if (!stats.successNum ) {
            // 没有成功的图片，重设
            state = 'done';
            location.reload();
          }
          break;
      }

      updateStatus();
    }

    uploader.onUploadProgress = function( file, percentage ) {
      var $li = $('#'+file.id),
        $percent = $li.find('.progress span');

      $percent.css( 'width', percentage * 100 + '%' );
      percentages[ file.id ][ 1 ] = percentage;
      updateTotalProgress();
    };

    uploader.onFileQueued = function( file ) {
      fileCount++;
      fileSize += file.size;

      if ( fileCount === 1 ) {
        $queuelist.addClass( 'element-invisible' );
        $statusBar.show();
      }

      addFile( file );
      setState( 'ready' );
      updateTotalProgress();
    };

    uploader.onFileDequeued = function( file ) {
      fileCount--;
      fileSize -= file.size;

      if ( !fileCount ) {
        setState( 'pedding' );
      }

      removeFile( file );
      updateTotalProgress();

    };

    uploader.on('all', function( type ) {
      var stats;
      switch( type ) {
        case 'uploadFinished':
          setState( 'confirm' );
          break;
        case 'startUpload':
          setState( 'uploading' );
          break;
        case 'stopUpload':
          setState( 'paused' );
          break;
      }
    });

    uploader.onError = function( code ) {
      var _str = code;
      switch (code){
        case 'Q_EXCEED_NUM_LIMIT':
          _str = '文件总数量超出'+ _setting.fileNumLimit + '个';
          break
        case 'Q_EXCEED_SIZE_LIMIT':
          _str = '文件总大小超出'+_setting.fileSizeLimit;
          break;
        case 'Q_TYPE_DENIED':
          _str = '文件类型必须为'+ _setting.extensions;
          break;
      }

      commonApp.notify.error('错误：'+ _str,notifyConf);
    };

    uploader.on('uploadSuccess', function (file,resporse) {
      var _$file = $('#'+file.id);
      if(resporse.status == 1){
        _$file.data('file',resporse.file).append( '<p class="success"><span class="iconfont">&#xe621;</span></p>');

        if(_config.multiple){
          _$file.addClass('active');
        }else{
          $filelist.find('li.state-complete').eq(0).addClass('active');
        }

        $tabmanage.data('refresh',true); //有新文件上传成功 图片管理刷新
      }else{
        _$file.attr('class','state-error').append('<p class="error">上传失败！'+ resporse.error +'</p>');
      }
    });

    $upload.on('click', function() {
      if ( $(this).hasClass( 'disabled' ) ) {
        return false;
      }

      if ( state === 'ready' ) {
        uploader.upload();
      } else if ( state === 'paused' ) {
        uploader.upload();
      } else if ( state === 'uploading' ) {
        uploader.stop();
      }
    });

    $info.on( 'click', '.retry', function() {
      uploader.retry();
    });

    $info.on( 'click', '.ignore', function() {
      alert( 'todo' );
    });

    $upload.addClass( 'state-' + state );

    updateTotalProgress();
  };

  /**
   * 选中已上传文件
   * @private
   */
  var _selectFile = function(){
    var _select = function($this){
      if(_config.multiple){
        $this.hasClass('active')?$this.removeClass('active'):$this.addClass('active');
      }else{
        $this.addClass('active').siblings().removeClass('active');
      }
    };
    $uploaderWrap.on('click','.state-complete',function(){
      _select($(this));
    });

    $manage.on('click','li',function(){
      var $this = $(this),
        _id = $this.attr('id');
      _select($this);

      if(_config.multiple){
        var selected = $manage.data('selected') || {};
        selected[_id]?delete selected[_id]:selected[_id] = $this.data('file');
      }else {
        selected = {};
        selected[_id] = $this.data('file');
      }
      $manage.data('selected',selected);
    });

  };

  var _getFilesFun = function(){
    var _data = [];
    switch ($tabpane.filter('.active').attr('id')){
      case 'tab-insert':

        var _remote_data = commonApp.getFormData($('#remote_image_form'));
        if(_remote_data.remoteImage && _remote_data.remoteImage != ''){
          _remote_data.title = '';
          _remote_data.thumb = [];
          _data.push(_remote_data);
        }

        break;
      case 'tab-upload':
        $uploaderWrap.find('.active').each(function(){
          _data.push($(this).data('file'));
        });

        break;
      case 'tab-manage':
        var select = $manage.data('selected') || {};
        for(var i in select){
          _data.push(select[i]);
        }
        break;
      case 'tab-search':

        break;
    }
    return _data;
  };

  return {
    init:function(uploadtype){
      _initFun(uploadtype);
      _initWebuploaderFun();
      _selectFile();
    },
    getFiles:_getFilesFun
  }
}();