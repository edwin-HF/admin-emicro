var permission = {
    data : [],
    is_root : false,
    hasInit : false,
    init : function () {

        let obj = this;

        $.ajax({
            url: '/system/user/permissions',
            type: 'get',
            async: false,
            success: function (data) {
                let result = data.result.data;

                obj.data = result.permissions;
                obj.is_root = (result.is_root == 1 ? true : false);

                obj.hasInit = true;
            },
        });

    },
    allow : function (code) {

        console.log(this.is_root)

        if (!this.hasInit)
            this.init();

        if (this.is_root)
            return true;

        return $.inArray(code, this.data) !== -1;
    }
}



