/**
 * @author Edwin Fan
 * @contact edwin.fan@foxmail.com
 */
layui.define(["element","form"], function (exports) {
    var element = layui.element,
        form  = layui.form;

    var treeTable = {
        container : null,
        data    : [],
        primary : {
            header : {title:'title',style:''},
            field  : 'title'
        },
        extra : {
            headers : [],
            columns : false
        },
        unique  : 'id',
        render: function (data) {

            this.container = data.container || '';
            this.data    = data.data || [];
            this.primary = data.primary;
            this.unique  = data.unique || 'id';
            this.extra   = data.extra;

            if (this.container){
                this.container.html(treeTable.renderTable(data.data));
                form.render();

                treeTable.listen();
            }

            return this;

        },

        /**
         * 监听
         */
        listen: function () {

            this.container.on('click','.slide-action-td',function () {
                let $this = $(this);
                let $context = $this.parents('tr');

                if ($this.parents('tr').hasClass('open')){
                    $this.parents('tr').removeClass('open');
                    $.each(treeTable.nextAll($context),function () {
                        $(this).hide().removeClass('open');
                    })
                }else{
                    $this.parents('tr').addClass('open');
                    $.each(treeTable.next($context),function () {
                        $(this).slideDown();
                    })
                }

            });

        },

        renderTable : function(){

            let html =  '<table id="menu-list" lay-skin="line" class="layui-table" style="min-width: 1000px">\n' +
                        '<thead>\n' +
                        '<tr>\n';

                let primary_title = this.primary.header.title || '';
                let primary_style = this.primary.header.style || '';

                html += '<td style="'+primary_style+'" >'+primary_title+'</td>\n';

                $.each(this.extra.headers,function (index,item) {
                    let title = item.title || '';
                    let style = item.style || '';
                    html += '<td style="'+style+'" >'+title+'</td>\n';
                })

                html += '</tr>\n' +
                        '</thead>\n' +
                        '<tbody>\n'+ treeTable.renderList(this.data) +'</tbody>\n' +
                        '</table>';

                return html;

        },

        renderList : function(data,path='') {

            let obj = this;

            let html = '';

            $.each(data,function (index,item) {

                let unique_id = eval('item.'+obj.unique);
                let primary   = eval('item.'+obj.primary.field);

                let node_path = (path ? (path + '/n_' + unique_id) : 'n_' + unique_id);

                let has_child = item.child && item.child.length > 0;
                let show = (path == '') ? '' : 'display:none;'
                let node = 'n_' + unique_id;

                html += '<tr class="table-toggle ' + node + ' "  style="'+show+'" data-node="'+node+'" data-path="'+node_path+'" data-item=\''+JSON.stringify(item)+'\'>' +
                    '<td class="slide-action-td">';

                for(let i=1;i<treeTable.deep(node_path);i++){
                    html += '<span class="empty"></span>';
                }

                if (has_child){
                    html += '<i class="layui-icon layui-icon-triangle-d slide-action"></i>';
                }else{
                    html += '<i class="layui-icon layui-icon-layer" style="margin-right: 2px"></i>';
                }

                html += primary+'</td>';

                if (obj.extra.columns){
                    html += obj.extra.columns(item);
                }

                html += '</tr>';

                if (has_child){
                    html += treeTable.renderList(item.child,(node_path));
                }
            })

            return html;

        },

        deep : function(path) {

            let deep = 1;

            for (let i=0;i<path.length;i++){
                if (path[i] == '/'){
                    deep++;
                }
            }

            return deep;
        },

        next : function($context) {
            let node = $context.data('node');
            let ret  = [];
            $.each($context.siblings('tr'),function () {
                let path = $(this).data('path');
                let path_arr = treeTable.deep(path) == 1 ? [path] : path.split('/');
                if ($.inArray(node,path_arr) != -1 && path_arr.length == treeTable.indexValue(path_arr,node)+2){
                    ret.push($(this));
                }
            })

            return ret;
        },

        nextAll : function($context) {
            let node = $context.data('node');
            let ret  = [];
            $.each($context.siblings('tr'),function () {
                let path = $(this).data('path');
                let path_arr = treeTable.deep(path) == 1 ? [path] : path.split('/');
                if ($.inArray(node,path_arr) != -1){
                    let find_parent = false;
                    for (let i=0;i<path_arr.length;i++){
                        if (path_arr[i] == node){
                            find_parent = true;
                            continue;
                        }
                        if (find_parent){
                            ret.push($('.' + path_arr[i]));
                        }
                    }
                }
            })

            return ret;
        },

        indexValue : function(arr,str) {
            for (let i=0;i<arr.length;i++){
                if (arr[i] == str){
                    return i;
                }
            }
        }

    };


    layui.link(layui.cache.base + 'tree-table/tree.css');

    exports("treeTable", treeTable);

});