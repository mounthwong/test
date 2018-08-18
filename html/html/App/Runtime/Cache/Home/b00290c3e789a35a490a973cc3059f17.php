<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>药品编辑</title>
<link href="/public/Home//style/manage.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/public/Home//js/jquery.min.js"></script> 
<script type="text/javascript" src="/public/Home//js/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="/public/Home//js/iframeTools.js"></script>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form_table">
  <tr style="display:none;">
    <td class="td_right">药品编码：</td>
    <td>
      <input type="text" name="edit_sectionid" id="code" class="input-text lh30" size="15" value="<?php echo ($medicine_code); ?>">
    </td>
  </tr>
  <tr>
    <td class="td_right">药品名称：</td>
    <td><input type="text" name="edit_sortid" id="name" class="input-text lh30" size="30" value="<?php echo ($medicine_name); ?>"/></td>
  </tr>
  <tr>
    <td class="td_right">药品比例：</td>
    <td><input type="text" name="edit_enbefore" id="rate" class="input-text lh30" size="5" value="<?php echo ($medicine_rate); ?>"/></td>
  </tr>
</table>

<script type="text/javascript">
$(function(){
 $.ajaxSetup({async:false});
});
  
function editMedicine()
  { 
    $.ajaxSetup({async:false});
    var medicine_code = $.trim($("#code").val());
    var medicine_name = $.trim($("#name").val());
    var medicine_rate = $.trim($("#rate").val());
    if (medicine_name == "") { art.dialog.alert("请添加药品名称！");return false;}
    var id = "<?php echo ($id); ?>";
    var closeflag = false; 
    var data={};
    data.code=medicine_code;
    data.price=medicine_rate;
    data.name=medicine_name;
    $.getJSON('medicineedit',{id:id,data:JSON.stringify(data),random:Math.random()},function(data){
      if(data.suc==1){
        closeflag=true;
      }else{
        art.dialog.tips(data.msg);
        closeflag=false;
      }
      
    }); 
    return closeflag;
  }
</script>
</body>
</html>