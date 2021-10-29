/**
 * date:2020/02/27
 * author:Mr.Chung
 * version:2.0
 * description:layuimini 菜单框架扩展
 */
layui.define(["element","laytpl" ,"jquery"], function (exports) {
    var element = layui.element,
        $ = layui.$,
        laytpl = layui.laytpl,
        layer = layui.layer,
        form  = layui.form;

    var miniMenu = {

        /**
         * 菜单初始化
         * @param options.menuList   菜单数据信息
         * @param options.multiModule 是否开启多模块
         * @param options.menuChildOpen 是否展开子菜单
         */
        render: function () {

            miniMenu.listen();
            let $current = $('[data-menu]').parents('ul').find('.layui-this');
            if ($current){
                let index = $current.parents('ul').find('li').index($current);
                if (index < 0 ){
                    index = 0;
                }
                // $('[data-menu]').eq(index).addClass('layui-this').click();
            }else{
                // $('[data-menu]').eq(0).click().addClass('layui-this');
            }
        },


        /**
         * 监听
         */
        listen: function () {

            /**
             * 菜单缩放
             */
            $('body').on('click', '.slide-expand-tool', function () {
                let $slideExpandTool = $('.slide-expand-tool');
                let $body = $('.layui-layout-body');
                let status = $slideExpandTool.attr('data-expanded');

                if (status == 1) { // 缩放
                    $slideExpandTool.attr('data-expanded',0).find('i').attr('class', 'fa fa-indent');
                    $body.removeClass('layuimini-all').addClass('layuimini-mini');

                } else { // 正常
                    $slideExpandTool.attr('data-expanded',1).find('i').attr('class', 'fa fa-outdent');
                    $body.removeClass('layuimini-mini').addClass('layuimini-all');
                }
                element.init();
            });

        },

    };


    exports("miniMenu", miniMenu);
});
