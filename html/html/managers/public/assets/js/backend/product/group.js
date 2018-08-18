define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function ($, undefined, Backend, Table, Form, Template) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    "index_url": "product/group/index",
                    "add_url": "product/group/add",
                    "edit_url": "product/group/edit",
                    "del_url": "product/group/del",
                    "multi_url": "product/group/multi",
                    "table": "auth_rule"
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'weigh',
                escape: false,
                pagination: false,
                search: false,
                commonSearch: false,
                export:false,
                columns: [
                    [
                        {field: 'state', checkbox: true,},
                        {field: 'name', title: __('Name'), align: 'center'},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ],
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            //当内容渲染完成后
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                //默认隐藏所有子节点
                $(".btn-change[data-id],.btn-delone,.btn-dragsort").data("success", function (data, ret) {
                    Fast.api.refreshmenu();
                });

            });
            //批量删除后的回调
            $(".toolbar > .btn-del,.toolbar .btn-more~ul>li>a").data("success", function (e) {
                Fast.api.refreshmenu();
            });
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
