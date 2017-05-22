/**
 * IE版本提示 2014-09-24 17:47
 * @param string s_helpUrl 双核浏览器切换说明网址
 * @param object o_download 浏览器下载地址
 * Copyright 2011-2014 www.dookay.com, Inc.
 */

window.onload = function()
{
    //判断是否为ie浏览器
    if (window.ActiveXObject)
    {
        var s_ua = navigator.userAgent.toLowerCase();
        n_version = s_ua.match(/msie ([\d.]+)/)[1];
        if(n_version < 9)
        {
            var s_helpUrl = 'http://www.dbsoo.com/html/11-2/2479.htm',
                o_download = [
                {
                    'name' : 'IE',
                    'title': 'IE浏览器',
                    'url': 'http://windows.microsoft.com/zh-cn/internet-explorer/download-ie'
                },
                {
                    'name' : 'firefox',
                    'title': '火狐浏览器',
                    'url': 'http://www.firefox.com.cn/download/'
                },
                {
                    'name' : 'chrome',
                    'title': '谷歌浏览器',
                    'url': 'http://baoku.360.cn/soft/list/cid/157'
                }];

            //插入提示
            var d_html = document.getElementsByTagName('html')[0];
            d_html.style.overflow = 'hidden';

            var d_body = document.getElementsByTagName('body')[0];
            var d_overlay = document.createElement('div');

            d_overlay.innerHTML = '<div id="versiontips" class="versiontips"><div class="versiontips-middle"><div class="versiontips-inner"><div class="versiontips-panne"><div class="versiontips-desc"><h4>很抱歉</h4><p>您的浏览器内核版本过低，将无法正常浏览本站。<br>请升级您的浏览器到最新版本，或<a href="'+s_helpUrl+'" title="如何切换到浏览器高速内核？" target="_blank">切换到浏览器高速内核！</a></p></div><div class="versiontips-opt"><a id="j_versiontips-view" class="mr-2" href="javascript:;">继续访问</a><a id="j_versiontips-close" href="javascript:;">关闭本页面</a><div style="clear:both;"></div></div></div></div></div><div class="versiontips-exposeMask"></div></div>';
            d_body.appendChild(d_overlay);

            //绑定关闭事件
            var d_versiontips = document.getElementById('versiontips');
            document.getElementById('j_versiontips-view').onclick = function()
            {
                d_versiontips.style.display = 'none';
                d_html.style.overflow ="auto";
            }
            document.getElementById('j_versiontips-close').onclick = function()
            {
                window.opener=null;
                window.open('','_self','');
                window.close();
            }
        }
    }
}
