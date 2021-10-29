/**
 * @author Edwin Fan
 * @contact edwin.fan@foxmail.com
 */
layui.define(["element","form"], function (exports) {
    var element = layui.element,
        form  = layui.form;

    var treeCheckbox = {

        container : false,
        render: function (data) {

            this.container = data.container || false;

            if (this.container){
                this.container.html(treeCheckbox.renderTree(data.data));
                form.render();

                treeCheckbox.listen();
            }

            return this;

        },

        checkedId : function(){

            let returnData = [];

            $.each(this.container.find('.tree-entry input[type=checkbox]:checked'),function () {
                returnData.push($(this).data('id'));
            })

            return returnData;

        },

        checkedData : function(){

            let returnData = [];

            $.each(this.container.find('.tree-entry input[type=checkbox]:checked'),function () {
                returnData.push($(this).data('item'));
            })

            return returnData;

        },
        /**
         * 监听
         */
        listen: function () {

            this.container.on('click','.tree-entry .tree-expand-icon-box',function(){
                if ($(this).hasClass('add')){
                    $(this).removeClass('add').addClass('sub').find('i').removeClass('layui-icon-addition').addClass('layui-icon-subtraction');
                    $(this).parent().children('.tree-child').slideDown();
                }else{
                    $(this).removeClass('sub').addClass('add').find('i').addClass('layui-icon-addition').removeClass('layui-icon-subtraction')
                    $(this).parent().children('.tree-child').slideUp();
                }
            })

            form.on('checkbox(tree-checkbox)', function(data){
                let $this = data.othis;
                let $tree_entry = $this.parents('.tree-entry').first();

                if (data.elem.checked){
                    $tree_entry.parents('.tree-entry').children('input[type=checkbox]').prop('checked',true);
                    $tree_entry.find('input[type=checkbox]').prop('checked',true);
                }else{
                    $tree_entry.find('input[type=checkbox]').removeProp('checked');
                }

                form.render();

            });

        },

        renderTree : function(data, level) {
            let html = '';
            level = level || 1;
            $.each(data,function (index,item) {

                let class_block_entry = (level == 1 || item.child.length > 0 ? 'tree-entry-block' : '');
                let display = (level > 0 ? 'display: none;' : '');
                let checked = (item.checked ? 'checked' : '');
                let dataStr = JSON.stringify(item);
                let dataId  = item.id ?? '';

                html += '<div class="tree-entry '+class_block_entry+'" data-id="'+dataId+'" data-item='+dataStr+'>\n';

                if (item.child.length > 0){
                    html += '<span class="tree-expand-icon-box add">\n' +
                        '<i class="layui-icon layui-icon-addition"></i>\n' +
                        '</span>\n';
                }else{
                    html += '<span class="tree-node-icon-box"></span>\n';
                }

                html += '<input type="checkbox" name="" data-id="'+dataId+'" data-item='+dataStr+' title="'+item.title+'" lay-skin="primary" '+checked+' lay-filter="tree-checkbox">\n' +
                    '<div class="tree-child" style="'+display+'">\n';

                if (item.child.length > 0){
                    html += treeCheckbox.renderTree(item.child,level+1);
                }

                html += '</div>\n' +
                    '</div>';

            })

            return html;
        },

    };


    layui.link(layui.cache.base + 'tree-checkbox/tree.css');

    exports("treeCheckbox", treeCheckbox);

});
