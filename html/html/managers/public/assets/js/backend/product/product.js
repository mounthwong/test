define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function ($, undefined, Backend, Table, Form,Template) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'product/product/index',
                    add_url: 'product/product/add',
                    edit_url: 'product/product/edit',
                    del_url: 'product/product/del',
                    multi_url: 'product/product/multi',
                    table: 'product',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
		search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'cataglory.name', title: __('商品分类'),formatter: function(value,row,index){
                            var selector=$('<select class="form-control" style="width:100px;" onchange="var catid=$(this).val();$.get(\'product/product/changeCataglory?cataid=\'+catid+\'&id='+row.id+'\');">');
                            $.ajax({  
                                type : "post",  
                                url : "product/product/getCataglory",  
                                async : false,  
                                success : function(data){  
                                    var dataObj=eval("("+data+")");
                                    $.each(dataObj,function(k,v){
                                        var option="";
                                        if(row.cataglory.id==v.id){
                                            option=$("<option>").val(v.id).text(v.name).attr("selected",true); 
                                        }else{
                                            option=$("<option>").val(v.id).text(v.name);
                                        }
                                        selector.append(option);
                                    })
                                }  
                            });
                            //content=content+"</select>";
                            return selector.prop("outerHTML");
                        }},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'pic', title: __('ProductPic'), formatter: Table.api.formatter.image, operate: false},
                        {field: 'price', title: __('Price'), operate: false},
                        {field: 'discount', title: __('Discount'), operate: false},
                        {field: 'issale', title: __('Issale'), visible: false, searchList: {1: __('可卖'), 0: __('不可卖')}},
                        {field: 'isshow', title: __('Isshow'), visible: false, searchList: {1: __('显示'), 0: __('不显示')}},
                        {field: 'collectnum', title: __('Collectnum'), operate: false,visible:false},
                        {field: 'buynum', title: __('Buynum'), operate: false,visible:false},
                        {field: 'cartnum', title: __('Cartnum'), operate: false,visible:false},
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
                
            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
