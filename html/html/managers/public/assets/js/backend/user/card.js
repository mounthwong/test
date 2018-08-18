define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function ($, undefined, Backend, Table, Form,Template) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/user/index',
                    add_url: 'user/user/add',
                    edit_url: 'user/user/edit',
                    del_url: 'user/user/del',
                    multi_url: 'user/user/multi',
                    table: 'user',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'user.id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true},
                        {field: 'group.name', title: __('Group')},
                        {field: 'username', title: __('Username'), operate: 'LIKE'},
                        {field: 'nickname', title: __('Nickname'), operate: 'LIKE'},
                        {field: 'email', title: __('Email'), operate: 'LIKE'},
                        {field: 'mobile', title: __('Mobile'), operate: 'LIKE'},
                        {field: 'idcard', title: __('IDcard'), operate: 'LIKE'},
                        {field: 'avatar', title: __('Avatar'), formatter: Table.api.formatter.image, operate: false},
                        {field: 'gender', title: __('Gender'), visible: false, searchList: {1: __('Male'), 0: __('Female')}},
                        {
                            field: 'id',
                            title: __('Issurance'),
                            events: Table.api.events.operate,
                            formatter: Controller.api.formatter.subnode
                        },
                        {field: 'successions', title: __('Successions'), visible: false, operate: 'BETWEEN', sortable: true},
                        {field: 'maxsuccessions', title: __('Maxsuccessions'), visible: false, operate: 'BETWEEN', sortable: true},
                        {field: 'logintime', title: __('Logintime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        {field: 'loginip', title: __('Loginip'), formatter: Table.api.formatter.search},
                        {field: 'jointime', title: __('Jointime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        {field: 'joinip', title: __('Joinip'), formatter: Table.api.formatter.search},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status, searchList: {normal: __('Normal'), hidden: __('Hidden')}},
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
            formatter: {
                subnode: function (value, row, index) {
                    //<a href="/managers/public/index.php/admin/user/user/edit/ids/5" class="btn btn-xs btn-success btn-editone" data-toggle="tooltip" title="" data-table-id="table" data-field-index="18" data-row-index="0" data-button-index="1" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                    return '<a href="../admin/card/card/index/inds/'+row.id+'" data-toggle="tooltip" title="' + __('Card') + '" data-table-id="table"  class="btn btn-xs btn-detail btn-dialog '
                        + (row.group_id == 2 ? 'btn-success' : 'btn-default disabled') + ' btn-node-sub"><i class="fa fa-plus-square"></i></a>';
                }
            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
                // $(document).on('click', "input[name='row[ismenu]']", function () {
                //     var name = $("input[name='row[name]']");
                //     name.prop("placeholder", $(this).val() == 1 ? name.data("placeholder-menu") : name.data("placeholder-node"));
                // });
                // $("input[name='row[ismenu]']:checked").trigger("click");

                // var iconlist = [];
                // var iconfunc = function () {
                //     Layer.open({
                //         type: 1,
                //         area: ['99%', '98%'], //宽高
                //         content: Template('chooseicontpl', {iconlist: iconlist})
                //     });
                // };
            }
        }
    };
    return Controller;
});