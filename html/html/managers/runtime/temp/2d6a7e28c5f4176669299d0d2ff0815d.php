<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"/usr/share/nginx/html/managers/public/../application/index/view/index/infos.html";i:1531931110;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=0.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<meta charset="UTF-8">
		<title>自救资讯</title>
        <style>
            body {background: #fcfcfc;}
            /*.mui-media-body h3{margin-right: 50px;}*/
            p{
                word-wrap: break-word;
            }
        </style>
    </head>
<body>  	
    <p><b><?php echo $title; ?></b></p> 
    <p><?php echo $content; ?> <p> 						 
</body>
<script>
    window.onload=function () {
	var imgs = document.getElementsByTagName("img");
	for(var i=0; i<imgs.length;i++)
	{
	    imgs[i].setAttribute("width","100%");;
	}	
    };
</script>
</html>
