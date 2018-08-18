<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改密码</title>
<link href="/public/Home//style/manage.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/public/Home//js/jquery.min.js"></script> 
<script type="text/javascript" src="/public/Home//js/manager.js"></script>
<script type="text/javascript" src="/public/Home//js/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/public/Home//js/iframeTools.js"></script> 
<body> 
<div class="container">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="box_border" bid=<?php echo ($userid); ?>>
  <tr>
    <td class="box_top pl_10 f14"><strong>修改密码</strong></td> 
  </tr>
  <tr>
    <td class="p5">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="form_table">
      <tr>
        <td class="td_right">请输入新密码：</td>
        <td>
          <input type="text" id="pwd" name="pwd" class="input-text lh30" size="20" value="" />
        </td>
      </tr>
      <tr>
        <td class="td_right">请再次输入新密码：</td>
        <td><input type="text" id="repwd" name="repwd" value="" class="input-text lh30" size="20" /></td>
      </tr>
   
      </table>

  <div class="h10"></div>
    </td> 
  </tr> 
</table>
</div>
<script type="text/javascript">  

function saveuser(){
  $.ajaxSetup({async:false});
  var pwd = $("#pwd").val();  
  var repwd = $("#repwd").val(); 
  if ($.trim(pwd) == "") {
    dialogTips('密码不能为空');
    return false;
  }
  if ($.trim(pwd) != $.trim(repwd)) {
    dialogTips('两次输入的密码不一致');
    return false;
  }
  var closeflag = true;
  var id=$(".box_border").attr("bid");
  $.getJSON("../user/pwdedit", {pwd:pwd,userid:id,random:Math.random()}, function(result){
    alert(result.resultflag);
  //dialogNotice("系统提示","密码修改成功",1);
  }); 
 // alert(closeflag);
  return closeflag;
}
</script> 
</body>
</html>