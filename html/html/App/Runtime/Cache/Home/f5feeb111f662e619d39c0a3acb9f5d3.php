<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>版本列表</title>
<link href="/public/Home//style/manage.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/public/Home//js/jquery.min.js"></script>
<script type="text/javascript" src="/public/Home//js/manager.js"></script>
<script type="text/javascript" src="/public/Home//js/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/public/Home//js/iframeTools.js"></script>
</head>
<body>
<div class="place"><strong>位置</strong>：首页 &gt; 用户信息管理</div>
<div class="container">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="box_border">
  <tr>
    <td class="pl_10" height="42"><input name="按钮" id="btn_add" type="button" class="ext_btn ext_btn_submit" value="添加用户" /></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="list_table" id="table_data">
  <tr>
    <th width="50" align="center">序号</th>
    <th width="200">账号</th>
    <th width="150">真实姓名</th>
    <th width="50">启用</th>
    <th width="150">管理员</th>
    <th>操作</th>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="display:none" id="table_demo">
  <tr class="tr">
    <td align="center">1</td>
    <td align="center"></td>
    <td align="center"></td>
    <td align="center"></td>
    <td align="center"></td>
    <td align="left">
      &nbsp;&nbsp;
      <input type="button" class="ext_btn ext_btn_edit" value="编辑"   />&nbsp;&nbsp;
      <input type="button" class="ext_btn ext_btn_error" value="禁用" />
    </td>
  </tr>
</table>
<div class="h5"></div>

</div>
<script type="text/javascript">
$(function(){
  $.ajaxSetup({async:false});
  var dloading = art.dialog({time:30,title:'加载中……',width:130,height:30,opacity:0.3,lock:true});
  getUserList();
  dloading.close();

  $("#btn_add").click(function()
  {
    useradd(0);
  });

  $("#table_data .ext_btn_edit").live("click",function(){
    var userid = $(this).attr("BID");
    useredit(userid);
  });

  });

  $("#table_data .ext_btn_error").live("click",function(){
    var tr = $(this).parents("tr");
    var userid = $(this).attr("BID");
    art.dialog.confirm('你确定要禁用这个用户吗？', function () {
      $.get("../user/upisuse",{id:userid,random:Math.random()},function(data){
        //prompt(data);
        getUserList();
      });

    });
  });

function getUserList()
{
    var dloading = art.dialog({time:30,title:'加载中……',width:130,height:30,opacity:0.3,lock:true});
     $("#table_data td").parents("tr").remove();
    $.getJSON("../user/getuserlist", {random:Math.random()}, function(data){
      //alert(data.length);
      var i = 0;
      $.each(data, function(i,val){
        i++;
        var tr = $("#table_demo tr").eq(0).clone();
        var td = tr.children('td').eq(0);
       // alert(val.username);
        td.html(i);
        td = tr.children('td').eq(1);
        td.html(val.username);
        td = tr.children('td').eq(2);
        td.html(val.truename);
        td = tr.children('td').eq(3);
        if (val.ifuse == 1) {
           td.html('启用');
           //tr.find(".ext_btn_error").value("禁用");
        }else{
          td.html('未启用');
          //tr.find(".ext_btn_error").value("启用");
        }
       td = tr.children('td').eq(4);
       if (val.ifadmin == 1) {
           td.html('是');
           tr.find(".ext_btn_error").hide();
        }else{
          td.html('否');
        }
        tr.find("input").attr("BID",val.id);
        tr.appendTo("#table_data");
      });
    });
    $(".tr:odd").css("background", "#F5F8FA");
    dloading.close();

}

function useradd(id){
  var myDialog = $.dialog.open('../user/add?random=' + Math.random(),{
      id:'user_add',
      title:'添加用户',
      window:'top',
      width:520,
      height:270,
      lock:true,
      opacity:0.3,
      button: [
        {
          name: '保存',
          callback: function () {
            var iframe = this.iframe.contentWindow;
            var re = iframe.saveuser();
            //alert(re);
            if (re) {
              getUserList();
            }

            return re;
          },
          focus: true
        },
        {
          name: '关闭',
          callback: function () {
          },
          focus: false
        }
      ]
    });
}


function useredit(id){
  var myDialog = $.dialog.open('../user/edit?id='+id+'&random=' + Math.random(),{
      id:'user_edit',
      title:'修改用户',
      window:'top',
      width:520,
      height:270,
      lock:true,
      opacity:0.3,
      button: [
        {
          name: '保存',
          callback: function () {
            var iframe = this.iframe.contentWindow;
            var re = iframe.saveuser();
            //alert(re);
            getUserList();
            return re;
          },
          focus: true
        },
        {
          name: '关闭',
          callback: function () {
          },
          focus: false
        }
      ]
    });
}
</script>
</body>
</html>