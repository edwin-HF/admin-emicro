$(function () {
    layui.use(
        ['form','layer'],
        function () {
            let form  = layui.form;
            let layer = layui.layer;

            $(document).on('click','.search',function () {
                $('.table-search-fieldset').slideToggle();
            });

            $(document).on('click','.btn-batch',function () {
                $('.batch-operate').slideToggle();
            });

            $(document).on('click','.inline-edit-btn',function () {
                let $parent = $(this).parents('td');

                let url = $parent.find('input').data('url');
                let id  = $parent.find('input').data('id');
                let sort = $parent.find('input').val();

                $.post(
                    url,
                    {id: id, sort:sort},
                    function (data) {
                        if (data.code == 1000) {
                            layer.msg('操作成功');
                            window.location.reload();
                        } else {
                            layer.msg(data.info);
                        }
                    }
                );
            })

            form.on(
                'checkbox(check-all)',
                function (data) {

                    if (data.elem.checked) {
                        $('.check-item').prop('checked','checked');
                    } else {
                        $('.check-item').removeAttr('checked');
                    }

                    form.render('checkbox');
                }
            );
        }
    );

    $.fn.extend(
        {
            batch : function (callback) {

                $(this).click(
                    function () {
                        let result = [];
                        $.each(
                            $('.check-item'),
                            function () {
                                if ($(this).is(':checked')) {
                                    result.push($(this).data('id'));
                                }
                            }
                        );

                        if (result.length <= 0) {
                            layer.msg('请选择数据');
                            return;
                        }

                        callback(result);
                    }
                )
            }
        }
    )
})

