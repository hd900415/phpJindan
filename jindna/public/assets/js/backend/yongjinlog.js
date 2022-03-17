define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'yongjinlog/index' + location.search,
                    add_url: 'yongjinlog/add',
                    edit_url: 'yongjinlog/edit',
                    del_url: 'yongjinlog/del',
                    multi_url: 'yongjinlog/multi',
                    table: 'yongjinlog',
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
                        {field: 'uid', title: __('Uid')},
                        {field: 'user.nickname', title: __('User.nickname')},
                        {field: 'user.headimage', title: __('User.headimage'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'level', title: __('Level')},
                        {field: 'xj_id', title: __('Xj_id')},
                        {field: 'xj_nickname', title: __('Xj_nickname')},
                        {field: 'xj_headimage', title: __('Xj_headimage'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'yj_bili', title: __('Yj_bili'), operate:'BETWEEN'},
                        {field: 'yongjin', title: __('Yongjin'), operate:'BETWEEN'},
                        {field: 'order_num', title: __('Order_num')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'user.id', title: __('User.id')},
                        // {field: 'user.openid', title: __('User.openid')},
                        // {field: 'user.jine', title: __('User.jine'), operate:'BETWEEN'},
                        // {field: 'user.yongjin', title: __('User.yongjin'), operate:'BETWEEN'},
                        // {field: 'user.tuid1', title: __('User.tuid1')},
                        // {field: 'user.tuid2', title: __('User.tuid2')},
                        // {field: 'user.tuid3', title: __('User.tuid3')},
                        // {field: 'user.createtime', title: __('User.createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'user.updatetime', title: __('User.updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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