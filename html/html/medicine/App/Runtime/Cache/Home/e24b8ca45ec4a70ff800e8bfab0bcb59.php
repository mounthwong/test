<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>版本列表</title>
        <link href="/medicine/public/Home//style/manage.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/medicine/public/Home//js/jquery.min.js"></script>
        <script type="text/javascript" src="/medicine/public/Home//js/jquery.artDialog.js?skin=default"></script>
        <script type="text/javascript" src="/medicine/public/Home//js/iframeTools.js"></script>
        <style>
          ul{border:0; margin:0; padding:0;}

          #pagination-flickr li{

          border:0; margin:0; padding:0;
          font-size:11px;
          list-style:none;
          }
          #pagination-flickr a{

          border:solid 1px #DDDDDD;
          margin-right:2px;
          }
          #pagination-flickr .previous-off,
          #pagination-flickr .next-off {

          color:#666666;
          display:block;
          float:left;
          font-weight:bold;
          padding:3px 4px;
          }
          #pagination-flickr .next a,
          #pagination-flickr .previous a {

          font-weight:bold;
          border:solid 1px #FFFFFF;
          }
          #pagination-flickr .active{

          color:#ff0084;
          font-weight:bold;
          display:block;
          float:left;
          padding:4px 6px;
          }
          #pagination-flickr a:link,
          #pagination-flickr a:visited {

          color:#0063e3;
          display:block;
          float:left;
          padding:3px 6px;
          text-decoration:none;
          }
          #pagination-flickr a:hover{

          border:solid 1px #666666;
          }
        </style>
    </head>
    <body>
        <div class="place"><strong>位置</strong>：首页 &gt; 未匹配药品管理</div>
        <div class="container">
            <div class="h10"></div>
            <!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" class="box_border">
                <tr>
                    <td class="pl_10" height="42">
                        <input id="download" name="download" type="button"  class="ext_btn ext_btn_submit transfer" value="下载"  />
                    </td>
                </tr>
            </table> -->
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="list_table" id="table_data">
                <tr>
                    <th width="50" align="center">序号</th>
                    <th width="80">药品</th>
                    <th width="" align="left">自费比例</th>
                    <th width="" align="left">操作</th>
                </tr>
            </table>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="display:none" id="table_demo">
                <tr class="tr">
                    <td align="center"></td>
                    <td><input type="text"  value="" class="input-text lh30" size="60"  disabled="true"/></td>
                    <td align="left"><input type="text"  value="" class="input-text lh30" size="10"  /></td>
                    <td align="left">
                        &nbsp;&nbsp;
                        <input type="button" class="ext_btn ext_btn_error" value="删除" />
                    </td>
                </tr>
            </table>
            <div class="h5"></div>
        </div>
        <script type="text/javascript">
        var searchtype = 1;
        var request=urlParse();
        var batchids=request["batchids"];
            $(function() {
                $.ajaxSetup({async: false});
                $(".tr:odd").css("background", "#F5F8FA");
                $('.tr:odd').live('hover', function(event) {
                    if (event.type == 'mouseenter') {
                        $(this).css("background-color", "#E5EBEE");
                    } else {
                        $(this).css("background-color", "#F5F8FA");
                    }
                });

                /**
                 删除按钮单击事件
                 **/
                $("#table_data .ext_btn_error").live("click", function() {

                    //后面的序号都减少
                    $(this).parents("tr").nextAll().each(function(key,value){
                      var index=parseInt($(value).find("td").eq(0).html());
                      $(value).find("td").eq(0).html(index-1);
                    })
                    $(this).parents("tr").remove();
                });

                $('.tr:even').live('hover', function(event) {
                    if (event.type == 'mouseenter') {
                        $(this).css("background-color", "#E5EBEE");
                    } else {
                        $(this).css("background-color", "#FFF");
                    }
                });
                /**
                 初始化
                 **/
                getlist(batchids);

                //将没有匹配的下载下来
                $("#download").click(function(){
                  window.location.href='downloadNullMedicineExcel?batchids='+batchids+"&ran="+Math.random();
                })
            });

            function  getlist(name,pagenum){
                $("#table_data td").parents("tr").remove();
                var dloading = art.dialog({time: 30, title: '加载中……', width: 130, height: 30, opacity: 0.3, lock: true});
                $.getJSON("getEditPatientMedical", {batchids: batchids,random: Math.random()}, function(data) {
                var i = 0;
                $.each(data, function(i, val) {
                    i++;
                    var tr = $("#table_demo tr").eq(0).clone();
                    var td = tr.children('td').eq(0);
                    td.html(i);
                    td = tr.children('td').eq(1);
                    td.find("input").val(val.medicine_name);
                    td = tr.children('td').eq(2);
                    td.find("input").val(val.medicine_rate);
                    tr.appendTo("#table_data");
                  });
                });
                //分页的样式

                $(".tr:odd").css("background", "#F5F8FA");
                dloading.close();

            }

            function urlParse(){
                var url = window.location.search;
                    var obj = {};
                    var reg = /[?&][^?&]+=[^?&]+/g;
                    var arr = url.match(reg);

                    if (arr) {
                        arr.forEach(function (item) {
                            var tempArr = item.substring(1).split('=');
                            var key = decodeURIComponent(tempArr[0]);
                            var val = decodeURIComponent(tempArr[1]);
                            obj[key] = val;
                        });
                    }
                    return obj;
            }

            function save(){
                //将数据全部上传到服务器中
                var arr=[];
                var tr=$("#table_data").find("td").parents("tr");
                var i=1;
                var isblank=0;
                $.each(tr,function(key,val){
                    var temp={};
                    temp.id=i;
                    temp.medicine_name=$(val).find("td").eq(1).find("input").val().replace(/\"/g, "\\\"");
                    temp.medicine_rate=$(val).find("td").eq(2).find("input").val().replace(/\"/g, "\\\"");
                    if(temp.medicine_rate==""){
                      isblank=isblank+1;
                    }
                    arr.push(temp);
                    i++;
                })
                if(isblank>0){
                  art.dialog({
                      id: 'testID',
                      content: '存在未匹配的药品比例,未匹配的自费比例自动为1,您是否导入?',
                      button: [
                          {
                              name: '是',
                              callback: function () {
                                var dloading = art.dialog({time: 30, title: '导入中……', width: 130, height: 30, opacity: 0.3, lock: true});
                                var flag=false;
                                $.post('editPatientMedical',{data:JSON.stringify(arr),random:Math.random()},function(data){
                                    dloading.close();
                                    window.parent.window.getlist(batchids);
                                    window.parent.window.art.dialog({ id: 'editmedicine' }).close();
                                    //window.parent.window.getlist(batchids);
                                    //flag=true;
                                });
                              },
                              focus: true
                          },
                          {
                              name: '否',
                              callback: function () {
                                  flag=false;
                                  return true;

                              }
                          }
                      ]
                  });
                }else{
                  var dloading = art.dialog({time: 30, title: '导入中……', width: 130, height: 30, opacity: 0.3, lock: true});
                  var flag=false;
                  $.post('editPatientMedical',{data:JSON.stringify(arr),random:Math.random()},function(data){
                      dloading.close();
                      window.parent.window.getlist(batchids);
                      window.parent.window.art.dialog({ id: 'editmedicine' }).close();
                      //flag=true;
                  });

                }


            }
        </script>
    </body>
</html>