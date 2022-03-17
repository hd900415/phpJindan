define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/index' + location.search,
                    add_url: 'order/add',
                    edit_url: 'order/edit',
                    del_url: 'order/del',
                    multi_url: 'order/multi',
                    table: 'order',
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
                        {field: 'order_num', title: __('Order_num')},
                        {field: 'goods_id', title: __('Goods_id')},
                        {field: 'goods.goods_name', title: __('Goods.goods_name')},
                        {field: 'goods.goods_image', title: __('Goods.goods_image'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3'),"4":__('Status 4')}, formatter: Table.api.formatter.status},
                        // {field: 'transaction_id', title: __('Transaction_id')},
                        {field: 'express_num', title: __('Express_num')},
                        {field: 'rece_name', title: __('Rece_name')},
                        {field: 'rece_address', title: __('Rece_address')},
                        {field: 'rece_phone', title: __('Rece_phone')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'goods.id', title: __('Goods.id')},
                        // {field: 'goods.goods_images', title: __('Goods.goods_images'), events: Table.api.events.image, formatter: Table.api.formatter.images},
                        // {field: 'goods.goods_desc', title: __('Goods.goods_desc')},
                        // {field: 'goods.category_id', title: __('Goods.category_id')},
                        // {field: 'goods.price', title: __('Goods.price'), operate:'BETWEEN'},
                        // {field: 'goods.jifen', title: __('Goods.jifen')},
                        // {field: 'goods.status', title: __('Goods.status'), formatter: Table.api.formatter.status},
                        // {field: 'goods.is_show', title: __('Goods.is_show')},
                        // {field: 'goods.createtime', title: __('Goods.createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'goods.updatetime', title: __('Goods.updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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