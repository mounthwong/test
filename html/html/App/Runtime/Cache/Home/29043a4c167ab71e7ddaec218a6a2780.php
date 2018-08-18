<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MEDICINE平台</title>
<link href="/public/Home//style/login.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="/public/Home//js/jquery.min.js"></script>
<script language="JavaScript" src="/public/Home//js/jquery.form.js"></script>
<script type="text/javascript" src="/public/Home//js/cloud.js"></script>
<script type="text/javascript" src="/public/Home//js/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/public/Home//js/iframeTools.js"></script>

<script  language="javascript">

function ckit(){
   if($('#username').val()==""){
      art.dialog.alert("请输入用户名！");
      $('#username').focus();
	  return false;
   }
   if($('#pwd').val()==""){
      art.dialog.alert("请输入密码！");
      $('#pwd').focus();
	  return false;
   }
   return true;
}
function login()
{
	if(ckit())
	{
		var username=$('#username').val();
		var pwd=$('#pwd').val();
		$.getJSON("../login/login",{username:username,pwd:pwd},function(data){

		    	if(data.flag == 1){
		    		location.href="../index/index";
		    		//alert(data.username+data.password);
		    	}
		    	else
		    	{
		    		art.dialog.alert(data.errorinfo);
		    	}
		});

		//location.replace("main.jsp");
	}
}

function reset(){
	$('#username').val("");
	$('#pwd').val("");
	return true;
}

document.onkeydown=function(evt){
	evt = (evt) ? evt : ((window.event) ? window.event : ""); //兼容IE和Firefox获得keyBoardEvent对象
	var key = evt.keyCode?evt.keyCode:evt.which;//兼容IE和Firefox获得keyBoardEvent对象的键值
	if(key == 13){
	     login();
	}
}
</script>
</head>
<body>
<div id="mainBody">
  	<div id="cloud1" class="cloud"></div>
  	<div id="cloud2" class="cloud"></div>
</div>
<div class="logintop">
    <span style="margin:0;padding:0;display:block;">欢迎登录MEDICINE平台</span>
    <ul>
	    <li><a href="javascript:;">帮助</a></li>
	    <li><a href="javascript:;">关于</a></li>
    </ul>
</div>
<div class="loginbody">
    <span style="margin:0;padding:0;display:block;" class="systemlogo"></span>
    <div class="loginbox">
	    <ul>
		    <li><input name="username" id="username" type="text" class="loginuser" /></li>
		    <li><input name="pwd" id="pwd" type="password" class="loginpwd"/></li>
				<input name="action" id="action" type="hidden" value="userlogin"/>
		    <li>
		    	<input  type="button" class="loginbtn" value="登录"  onclick="javascript:login();"  />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    	<input  type="button" class="loginbtn" value="重置"  onclick="javascript:reset();"  />
		    </li>
	    </ul>
    </div>
</div>
<div class="loginbm"></a></div>
</body>
</html>