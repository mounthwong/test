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
<style> 
  html, body{ margin:0; padding:0; }
  body{background:#F3F6FC url(/public/Home//images/welcome.gif)  fixed center no-repeat;}
</style>
<body userid=<?php echo ($userid); ?>>
<script type="text/javascript">  
var id =$("body").attr("userid");
$(function(){ 
  $.ajaxSetup({async:false});
  var myDialog = $.dialog.open('../user/pwd?id='+id+'&random=' + Math.random(),{
      id:'user_edit',
      title:'修改密码',
      window:'top',
      width:520,
      height:170,
      lock:true,
      opacity:0.3,
      button: [
        {
          name: '提交',
          callback: function () {  
            var iframe = this.iframe.contentWindow;     
            var re = iframe.saveuser();
            //alert(re);
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
});



</script> 
</body>
</html>