define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'information/information/index',
                    add_url: 'information/information/add',
                    edit_url: 'information/information/edit',
                    del_url: 'information/information/del',
                    multi_url: 'information/information/multi',
                    table: 'information',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'createtime',
                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'type', title: __('Type'), operate: false,formatter: Controller.api.formatter.subnode},
                        {field: 'cataglory', title: "分类", operate: false,formatter: function(value,row,index){
                            if(row.cataglory==0){
                               return "默认";
                            }else if(row.cataglory==1){
                                return "名医专访";
                            }else if(row.cataglory==2){
                                return "疾病百科";
                            }else if(row.cataglory==3){
                                return "医学金字塔";
                            }
                        }},
                        {field: 'title', title: __('Title'),operate: 'LIKE'},
                        {field: 'pic', title: __('Pic'), formatter: Table.api.formatter.image, operate: false},
                        {field: 'desp', title: __('Desp'), operate: 'LIKE'},
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
                    return (row.type == 0 ? '资讯' : '自救');
                },

            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
