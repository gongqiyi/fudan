var commonApp = function () {
    var slider = function(){
        $('#j_banner').bxSlider({
            pager: false
        });
    };

    //判断滚动条
    var dx_scroll = function () {
        var nav = $("#j_nav"); //得到导航对象
        var win = $(window); //得到窗口对象
        var sc=$(document);//得到document文档对象

        win.scroll(function () {
            if (sc.scrollTop()>=266){
                nav.addClass("fixednav");
                $(".second-nav").css("paddingTop","8%");
            }else {
                nav.removeClass("fixednav");
                $(".second-nav").css("paddingTop","21%");
            }
        });
    };

    //显示二级导航
    var secondNav = function () {
        var timer;

        $(".nav-list li").on("mouseenter",function () {
            $(".second-nav").fadeIn("slow");
        });
        $(".nav-list li").on("mouseleave",function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                $(".second-nav").fadeOut("slow");
            },2000);
        });
        $(".second-nav").on("mouseenter",function () {
            clearTimeout(timer);
            $(this).removeClass("none");
        });
        $(".second-nav").on("mouseleave",function () {
            $(this).fadeOut("slow");
        });
    };
    return {
        init: function(){
            slider();
            //dx_scroll();
            secondNav();
        }
    }
}();