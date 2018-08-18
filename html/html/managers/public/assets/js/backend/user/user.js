define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function ($, undefined, Backend, Table, Form,Template) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/user/index',
                    add_url: 'user/user/add',
                    edit_url: 'user/user/edit',
                    del_url: '',
                    multi_url: 'user/user/multi',
                    table: 'user',
                }
            });

            var table = $("#table");

            // 初始化表格
	    table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                detailView: true,//父子表
                sortName: 'user.id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'group.name', title: __('客户类型')},
                        {field: 'nickname', title: __('姓名'), operate: 'LIKE'},
                        {field: 'idcard', title: __('IDcard'), operate: 'LIKE'},
                        {field: 'gender', title: __('Gender'), searchList: {1: __('Male'), 0: __('Female')},operate:false},
                        {field: 'age', title: __('Age'),operate:false},
                        {field: 'mobile', title: __('Mobile'), operate: 'LIKE'},
                        {field: 'city', title: __('City'),operate:false},
                        {field: 'region', title: __('Region'),operate:false},
                        {field: 'username', title: __('Username'), operate: 'LIKE',operate:false},
                        {field: 'order', title: __('付款时间'),formatter: function(value,row,index){
                            if(row.order.length>0){
                                if(row.order[0].paytime==null||row.order[0].paytime==""){

                                }else{
                                    console.log(row.order[0].paytime);
                                    var date = new Date(parseInt(row.order[0].paytime) * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000

                                    Y = date.getFullYear() + '-';
                                    M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
                                    D = date.getDate() + ' ';
                                    h = date.getHours() + ':';
                                    m = date.getMinutes() + ':';
                                    s = date.getSeconds();
                                    return Y+M+D+h+m+s;
                                }
                            }
                        }},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                        buttons: [{
                                    name: 'detail',
                                    text: '下订单',
                                    icon: 'fa fa-list',
                                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                    url: 'order/order/order'
                                },{
                                    name: 'detail',
                                    text: '历史订单',
                                    icon: 'fa fa-list',
                                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                    url: 'user/user/history'
                                }], 
                        formatter: Table.api.formatter.operate}
                    ]
                ],
                onExpandRow: function (index, row, $detail) {
                    //这一步就是相当于在当前点击列下新创建一个table
                    var html = "";
                    html += "<table class='table'>";
                    html += "<thead>";
                    html += "<tr style='height: 40px;'>";
                    html += "<th>卡号</th>";
                    html += "<th>卡类型</th>";
                    html += "<th>商品</th>";
                    html += "<th>截至时间</th>";
                    html += "</tr>";
                    html += "</thead>";
                    $.ajax({
                        type: "get",
                        url: "user/user/getUserCard",       //子表请求的地址
                        data: {username:row.username,ran:Math.random()},//我这里是点击父表后，传递父表列id和nama到后台查询子表数据
                        async: false,           //很重要，这里要使用同步请求
                        success: function(data) {
                            //遍历子表数据
                            $.each(data,function(n, value) {
                                html += "<tr  align='center'>" 
                                    + "<td>" + value.cardnum + "</td>" 
                                    + "<td>" + value.card.cardtype + "</td>"
                                    + "<td>";
                                $.each(value.card.cardproduct.productinfos,function(pk,pv){
                                    html += pv.name+",";
                                })
                                console.log(value.card.endtime)
                                var date = new Date(parseInt(value.card.endtime)*1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
                                Y = date.getFullYear() + '-';
                                M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
                                D = (date.getDate()+1 < 10 ? '0'+(date.getDate()+1) : date.getDate()+1) + '&nbsp;';
                                h = (date.getHours()+1 < 10 ? '0'+(date.getHours()+1) : date.getHours()+1) + '-';
                                m = (date.getMinutes()+1 < 10 ? '0'+(date.getMinutes()+1) : date.getMinutes()+1) + '-';
                                s = (date.getSeconds()+1 < 10 ? '0'+(date.getSeconds()+1) : date.getSeconds()+1);
                                html +=  "</td><td>" + Y+M+D+h+m+s + "</td>" 
                                    + "</tr>";
                            });
                            html += '</table>';
                            $detail.html(html); // 关键地方
                        }
                    });
                }
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
                    return '<a href="../admin/card/card/index/inds/'+row.id+'" data-toggle="tooltip" title="' + __('Card') + '" data-table-id="table"  class="btn btn-xs btn-detail btn-dialog btn-success btn-node-sub"><i class="fa fa-plus-square"></i></a>';
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
