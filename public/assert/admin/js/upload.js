function fileExtensionName(filename)
{

    let index = filename.lastIndexOf(".");
    return filename.substr(index + 1);
}

function fileName(filename)
{
    let index = filename.lastIndexOf(".");
    return filename.substr(0, index);
}

function randomUuid()
{

    let s = [];

    let hexDigits = "0123456789abcdef";

    for (let i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }

    s[14] = "4";  
    // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  
    // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = "-";

    let uuid = s.join("");

    return uuid;
}

function bindAsyncUpload(upload,$parent,ossClient,prefix)
{

    let $handle = $('.upload-box');
    let path = prefix ? prefix : 'materials';
    if ($parent) {
        $handle = $parent.find('.upload-box');
    }

    $.each(
        $handle,
        function () {

            let $this = $(this);
            let field = $this.attr('field');
            let async = $this.attr('async') ? true : false;

            let data = {
                elem: $this
                // 绑定元素
                ,accept:'file'
                ,auto:false
                ,data:{
                    path   : path,
                    _token : "{{ csrf_token() }}",
                }
                ,choose: function (obj) {
                    obj.preview(
                        function (index, file, result) {
                            if (file.type.indexOf('image') !== -1) {
                                $this.html('<img src="' + result + '">')
                            } else {
                                $this.html('<i class="fa fa-file-archive-o fa-4 file-icon" aria-hidden="true"></i>')
                            }

                            if (async) {
                                const randomFileName = path + '/' + randomUuid() + '.' + fileExtensionName(file.name);

                                ossClient.multipartUpload(randomFileName, file)
                                .then(
                                    function (data) {

                                        if (data.res.status == 200) {
                                            $this.attr('upload-src',randomFileName);
                                        } else {
                                            layer.msg('上传失败');
                                        }
                                    }
                                ).catch(
                                    function (e) {
                                        layer.msg('上传失败');
                                        console.log(e);
                                    }
                                );

                                $('input[name=file]').val('');
                            }
                        }
                    );
                }
            };

            if (field) {
                data.field = field;
            }

            upload.render(data);
        }
    );

}
