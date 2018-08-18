<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>文件上传</title>
    <link rel="stylesheet" type="text/css" href="/public/webuploader//webuploader.css" />
    <link rel="stylesheet" type="text/css" href="/public/webuploader//style.css" />
    <script type="text/javascript" src="/public/Home//js/jquery.min.js"></script>
    <script type="text/javascript" src="/public/webuploader//webuploader.js"></script>
    <script type="text/javascript" src="/public/webuploader//uploader.js"></script>
    <script type="text/javascript" src="/public/Home//js/jquery.artDialog.js?skin=default"></script>
    <script type="text/javascript" src="/public/Home//js/iframeTools.js"></script>
</head>
<body>
    <div id="wrapper">
      <div id="uploader" class="wu-example">
            <div id="thelist" class="uploader-list"></div>
            <div class="btns">
                <div id="picker">选择药品文件</div>
                <button id="ctlBtn" class="btn btn-default">开始上传</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var flag=false;
        $(function(){
          $.ajaxSetup({async:false});
        });

        function importExcel()
        {
          var filename=$( '.fileuploaders' ).find('p.state').attr("path");
          var closeflag=false;
          if(filename==""||filename=='undefined'||filename==undefined){
            art.dialog.tips("请先上传在导入");
            return false;
          }
          if(flag){
            alert("正在导入，请稍后。。。");
            return false;
          }
          var dloading = art.dialog({time: 30, title: '导入中……', width: 130, height: 30, opacity: 0.3, lock: true});
          flag=true;
          setTimeout(function(){
            $.getJSON('importMedicine',{filename:filename,random:Math.random()},function(data){
                flag=false;
                dloading.close();
                if (data.iserr == '0') {
                  alert("导入成功!")
                  closeflag=true;
                }else{
                  art.dialog({
                    icon: 'error',
                    content: data.errmsg
                  });
                }
            });
            return closeflag;
          },1000);
        }
    </script>
</body>
</html>