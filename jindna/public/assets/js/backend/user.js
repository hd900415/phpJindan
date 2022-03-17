define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/index' + location.search,
                    add_url: 'user/add',
                    edit_url: 'user/edit',
                    del_url: 'user/del',
                    multi_url: 'user/multi',
                    table: 'user',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'headimage', title: __('Headimage'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'openid', title: __('Openid')},
                        {field: 'jine', title: __('Jine'), operate:'BETWEEN'},
                        {field: 'yongjin', title: __('Yongjin'), operate:'BETWEEN'},
                        {field: 'jifen', title: __('Jifen')},
                        {field: 'fudai', title: __('Fudai')},
                        {field: 'game', title: __('Game')},
                        {field: 'tuid1', title: __('Tuid1')},
                        {field: 'tuid2', title: __('Tuid2')},
                        {field: 'tuid3', title: __('Tuid3')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'yiji_bili', title: __('Yiji_bili'), operate:'BETWEEN'},
                        {field: 'erji_bili', title: __('Erji_bili'), operate:'BETWEEN'},
                        {field: 'sanji_bili', title: __('Sanji_bili'), operate:'BETWEEN'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});