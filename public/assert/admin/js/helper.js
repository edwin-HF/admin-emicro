function numberConvertToUppercase(num) {
    num = Number(num);
    let upperCaseNumber = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '百', '千', '万', '亿'];
    let length = String(num).length;
    if (length == 1) {
        return upperCaseNumber[num];
    } else if (length == 2) {
        if (num == 10) {
            return upperCaseNumber[num];
        } else if (num > 10 && num < 20) {
            return '十' + upperCaseNumber[String(num).charAt(1)];
        } else {
            return upperCaseNumber[String(num).charAt(0)] + '十' + upperCaseNumber[String(num).charAt(1)].replace('零', '');
        }
    }
}

function parseTree(callback, data, sort, level){
    level = level || 1;
    sort  = sort || 'asc';
    $.each(data,function (index,item) {

        if (sort == 'asc'){
            callback(item,level);
        }

        if (item && item.child && item.child.length>0){
            parseTree(callback,item.child,sort,level+1);
        }

        if (sort == 'desc'){
            callback(item,level);
        }

    })
}

function renderMenu(callback, data) {

    let tree_menu = '';

    $.each(data(),function (index,item) {

        let has_child = (item.child.length > 0);

        let menu = '<li class="layui-nav-item menu-li '+menuSelector(item.id)+'">\n';

            if (item.path.length > 0){
                menu += '<a target="_self" href="'+item.path+'">\n';
            }else{
                menu += '<a target="_self" href="javascript:;">\n';
            }

            menu += '<i class="'+item.icon+'"></i>\n' +
            '<span class="layui-left-nav">'+item.title+'</span>\n';
        if (has_child){
            menu += '<span class="layui-nav-more"></span>\n';
        }
        menu += '</a>';

        if (has_child){
            menu += renderSubMenu(item.child);
        }

        menu += '</li>';

        tree_menu += menu;

    });

    callback(tree_menu);

}

function renderSubMenu(data) {

    let sub_menu = '';

    $.each(data,function (index,item) {

        let has_child = (item.child.length > 0);

        let sm = '<dl class="layui-nav-child '+menuSelector(item.id)+'">\n' +
            '<dd class="menu-dd" >\n';

            if (item.path.length > 0){
                sm += '<a href="'+item.path+'" target="_self">\n';
            }else{
                sm += '<a href="javascript:;" target="_self">\n';
            }

            sm += '<i class="'+item.icon+'"></i>\n' +
            '<span class="layui-left-nav"> '+item.title+'</span>\n' +
            '</a>\n';

        if (has_child){
            sm += renderSubMenu(item.child);
        }

        sm += '</dd>\n' +
            '</dl>';

        sub_menu += sm;

    })

    return sub_menu;

}

function menuSelector(unique) {
    return 'unique-'+unique;
}

function choiceMenu(selector) {
    let $itemMenu = $('.' + selector);
    if ($itemMenu.hasClass('layui-nav-child')){
        $itemMenu.find('dd').addClass('layui-this');
    }else{
        $itemMenu.addClass('layui-nav-itemed');
    }
}


