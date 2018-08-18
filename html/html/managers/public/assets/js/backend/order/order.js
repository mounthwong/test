define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function ($, undefined, Backend, Table, Form,Template) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/order/index',
                    add_url: 'order/order/custom/ids/0',
                    edit_url: '',
                    del_url: '',
                    multi_url: 'order/order/multi',
                    table: 'product_order',
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
                        {field: 'truename', title: __('Nickname'), operate: 'LIKE'},
                        {field: 'idcard', title: __('IDcard'), operate: 'LIKE'},
                        {field: 'gender', title: "性别", searchList: {'1': __('女'),'0': __('男')},formatter:function(value, row, index){
                            var success="购物车";
                            if(value=='1'){
                                success="女";
                            }else if(value=='0'){
                                success="男";
                            }
                            return success;
                        }},
                        {field: 'phone', title: "手机号", operate: 'LIKE'},
                        {field: 'status', title: __('订单状态'),formatter:function(value, row, index){
                            var success="未受理";
                            if(row.customdetail==''||row.customdetail==null||row.customdetail==undefined){
                                success="未受理"
                            }else{
                                success="客服已受理";
                                if(row.overtime != ''&&row.overtime != null&&row.overtime!=undefined){
                                    success="订单结束";
                                }else{
                                    if(row.diagnosisdisease!=""&&row.diagnosisdisease != null&&row.diagnosisdisease!=undefined){
                                        success="就诊中";
                                    }else{
                                        if(row.diagnosisover == 1){
                                            success="就诊结束";
                                        }
                                    }
                                }
                            }
                            return success;
                        }},
                        {field: 'updatetime', title: __('状态更新时间'),formatter:function(value, row, index){
                            var success="";
                            if(row.customdetail==''||row.customdetail==null||row.customdetail==undefined){
                                success=""
                            }else{
                                success=row.kfsendtime;
                                if(row.overtime != ''&&row.overtime != null&&row.overtime!=undefined){
                                    success=row.overtime;
                                }else{
                                    if(row.diagnosisupdatetime!=""&&row.diagnosisupdatetime != null&&row.diagnosisupdatetime!=undefined){
                                        success=row.diagnosisupdatetime;
                                    }else{
                                        if(row.appointsendtime!=""&&row.appointsendtime != null&&row.appointsendtime!=undefined){
                                            success=row.appointsendtime;
                                        }
                                    }
                                }
                            }
                            return success;
                        }},
                        {field: 'paytime', title: __('Paytime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        {field: 'orderinfo.productname', title: __('Orderinfo'), visible: true},
                        {field: 'operate', title: __('Operate'), table: table,
                            events: Table.api.events.operate,
                            buttons: [{
                                    name: 'detail',
                                    text: "订单",
                                    icon: 'fa fa-list',
                                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                    url: 'order/order/custom'
                                }],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        // add: function () {
        //     Controller.api.bindevent();
        // },
        // edit: function () {
        //     Controller.api.bindevent();
        // },
        detail:function(){
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                browser: function (value, row, index) {
                    return '<a class="btn btn-xs btn-browser">' + row.useragent.split(" ")[0] + '</a>';
                },
            },
        }
    };

    var opt = {
        url: "order/order/index",
        silent: true,
        query:{
            type:1,
            level:2
        }
    };

    setInterval(function(){$("#table").bootstrapTable('refresh', opt);},60000);
    return Controller;
});
