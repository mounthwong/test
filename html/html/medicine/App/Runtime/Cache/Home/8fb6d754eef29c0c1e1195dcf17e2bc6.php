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
        <div class="place"><strong>位置</strong>：首页 &gt; 同步单词管理</div>
        <div class="container">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="box_border">
                <tr>
                    <td class="box_top pl_10 f14"><strong>搜索</strong></td>
                </tr>
                <tr>
                    <td class="pl_5"><table border="0" cellspacing="0" cellpadding="0" class="form_table">
                            <tr>
                                <td width="100" align="right">药品名称：</td>
                                <td>
                                    <input type="text" id="pwd" name="pwd" value="" class="input-text lh30" size="40"  />
                                </td>
                                <td>
                                    &nbsp;
                                    <input type="button" class="btn btn82 btn_search"  value="查询" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="h10"></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="box_border">
                <tr>
                    <td class="pl_10" height="42">
                        <input id="addmedicine" name="addmedicine" type="button" class="ext_btn ext_btn_submit" value="添加药品" />&nbsp;
                        <input id="uploader" name="uploader" type="button" class="ext_btn ext_btn_submit"  value="药品EXCEL导入" />
                        <input id="download" name="download" type="button" onClick="window.location.href='downloadMedicineExcelDemo';" class="ext_btn ext_btn_submit transfer" value="模板下载"  />
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="list_table" id="table_data">
                <tr>
                    <th width="50" align="center">序号</th>
                    <th width="200">药品</th>
                    <th width="80">自费比例</th>
                    <th>&nbsp;</th>
                </tr>
            </table>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="display:none" id="table_demo">
                <tr class="tr">
                    <td align="center"></td>
                    <td></td>
                    <td></td>
                    <td align="left">
                        &nbsp;&nbsp;
                        <input type="button" class="ext_btn ext_btn_edit" value="编辑" />
                        <input type="button" class="ext_btn ext_btn_error" value="删除" />
                    </td>
                </tr>
            </table>
            <div class="h5"></div>
        </div>
        <script type="text/javascript">
        var searchtype = 1;
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
                getlist("",0);
                /**
                 查询按钮单击事件
                 **/
                $(".btn_search").click(function() {
                    var name=$("#pwd").val();
                    getlist(name,0);
                });

                //编辑药品
                $("#table_data .ext_btn_edit").live("click", function() {
                    var id = $(this).attr("bid");
                    var name=$("#pwd").val();
                    var curpage=parseInt($("#curpage").html())-1;
                    art.dialog.open('edit?id=' + id+"&ran="+Math.random(), {
                        title: "药品编辑",
                        width: 400,
                        height: 100,
                        lock: true,
                        opacity: 0.3,
                        button: [
                            {
                                name: '保存',
                                callback: function() {
                                    var iframe = this.iframe.contentWindow;
                                    var re = iframe.editMedicine();
                                    if (re) {
                                        getlist(name,curpage);
                                        return true;
                                    }
                                    else {
                                        return false;
                                    }
                                },
                                focus: true
                            },
                            {
                                name: '关闭',
                                callback: function() {
                                    //$("#gradeid").change();
                                },
                                focus: false
                            }
                        ]
                    });
                });

                //添加药品
                $("#addmedicine").live("click", function() {
                    var id = 0;
                    var name=$("#pwd").val();
                    var curpage=parseInt($("#curpage").html())-1;
                    art.dialog.open('edit?id=' + id+"&ran="+Math.random(), {
                        title: "药品编辑",
                        width: 400,
                        height: 100,
                        lock: true,
                        opacity: 0.3,
                        button: [
                            {
                                name: '保存',
                                callback: function() {
                                    var iframe = this.iframe.contentWindow;
                                    var re = iframe.editMedicine();
                                    if (re) {
                                        getlist(name,curpage);
                                        return true;
                                    }
                                    else {
                                        return false;
                                    }
                                },
                                focus: true
                            },
                            {
                                name: '关闭',
                                callback: function() {
                                    //$("#gradeid").change();
                                },
                                focus: false
                            }
                        ]
                    });
                });

                /**
                 删除按钮单击事件
                 **/
                $("#table_data .ext_btn_error").live("click", function() {
                    var tr = $(this).parents("tr");
                    var name=$("#pwd").val();
                    var curpage=parseInt($("#curpage").html())-1;
                    var wordid = $(this).attr("BID");
                    art.dialog.confirm('你确定要删除这个药品吗？', function() {
                        $.get("updel", {id: wordid,random: Math.random()});
                        tr.remove();
                        getlist(name,curpage);
                    });
                });

                //全选
                $("#all").click(function(){
                    if($(this).is(":checked")){
                       $(".tras").attr("checked",true);
                    }else{
                       $(".tras").attr("checked",false);
                    }
                });

                $("#uploader").click(function(){
                  console.log("fasdfasdas");
                  art.dialog.open('upload?&ran='+Math.random(), {
                      title: "上传EXCEL文件",
                      width: 400,
                      height: 300,
                      lock: true,
                      opacity: 0.3,
                      button: [
                              {
                                  name: '导入',
                                  callback: function() {
                                      var iframe = this.iframe.contentWindow;
                                      var re = iframe.importExcel();
                                      if (re) {
                                          //$.getJSON("getBookPicList", {bookid:bookid,random: Math.random() }, function (data) {
                                           //   $("#piclist").tmpl(data.photolist).appendTo('#photo');
                                              window.location.reload();
                                          //});
                                          return true;
                                      }
                                      else {
                                          return false;
                                      }
                                  },
                                  focus: true
                              },
                              {
                                  name: '关闭',
                                  callback: function() {
                                      //$("#gradeid").change();
                                      window.location.reload();
                                  },
                                  focus: false
                              }
                          ]
                  });
              });
            });

            function  getlist(name,pagenum){
                $("#table_data td").parents("tr").remove();
                var dloading = art.dialog({time: 30, title: '加载中……', width: 130, height: 30, opacity: 0.3, lock: true});
                $.getJSON("getlist", {name: name, pagenum:pagenum,random: Math.random()}, function(data) {
                var i = 0;
                $.each(data.list, function(i, val) {
                    i++;
                    var tr = $("#table_demo tr").eq(0).clone();
                    var td = tr.children('td').eq(0);
                    td.html((data.cur-1)*200+i);
                    td = tr.children('td').eq(1);
                    td.html(val.medicine_name);
                    td = tr.children('td').eq(2);
                    td.html(val.medicine_rate);
                    tr.find("input").attr("BID", val.id);
                    tr.appendTo("#table_data");
                  });
                  $("#pagination-flickr").remove();
                  var content='<ul id="pagination-flickr">';
                  for(var page=1;page<=data.count;page++) {
                    if(data.cur==page){
                      content=content+'<li class="active">'+page+'</li>';                      
                    }else{
                      if(name==""){
                        content=content+'<li><a href="javascript:void(0);" onClick="getlist(\'\','+(page-1)+')">'+page+'</a></li>';
                      }else{
                        content=content+'<li><a href="javascript:void(0);" onClick="getlist('+name+','+(page-1)+')\">'+page+'</a></li>';
                      }
                    }
                    
                  }
                  content=content+'</ul>';
                  $(content).insertAfter($("#table_data"))
                });
                //分页的样式

                $(".tr:odd").css("background", "#F5F8FA");
                dloading.close();

            }
        </script>
    </body>
</html>