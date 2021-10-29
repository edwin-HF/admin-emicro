$(function () {

    $(document).on('click','.inline-edit-btn',function () {

        let $parent = $(this).parents('td');

        let url  = $parent.find('input').data('url');
        let id   = $parent.find('input').data('id');
        let sort = $parent.find('input').val();

        $.post(url, {id: id, sort:sort}, function (data) {
            if (data.code == 1000) {
                layer.msg('操作成功');
                window.location.reload();
            } else {
                layer.msg(data.info);
            }
        });

    });

})