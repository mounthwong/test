<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>菜单</title>
        <script language="JavaScript" src="/public/Home//js/jquery.min.js"></script>
        <link href="/public/Home//style/menu.css" rel="stylesheet" type="text/css" />
        <script  type="text/javascript">
            $(function() {
                //导航切换
                $(".menuson li").click(function() {
                    $(".menuson li.active").removeClass("active")
                    $(this).addClass("active");
                });

                $('.title').click(function() {
                    var $ul = $(this).next('ul');
                    $('dd').find('ul').slideUp();
                    if ($ul.is(':visible')) {
                        $(this).next('ul').slideUp();
                    } else {
                        $(this).next('ul').slideDown();
                    }
                });
            })
        </script>
    </head>
    <body>
        <div class="lefttop"><span></span>功能菜单</div>
        <dl class="leftmenu">
            <dd>
                <div class="title"><span></span>信息管理</div>
                <ul class="menuson">
                    <li><cite></cite><a href="<?php echo U('word/in');?>" target="mainFrame">信息匹配管理</a><i></i></li>
                    <?php if($_SESSION['ifadmin']== 1): ?><li><cite></cite><a href="<?php echo U('word/list');?>" target="mainFrame">药品信息管理</a><i></i></li><?php endif; ?>
                    <!-- <li><cite></cite><a href="<?php echo U('patient/list');?>" target="mainFrame">病人历史记录</a><i></i></li> -->
                </ul>
            </dd>
            <?php if($_SESSION['ifadmin']== 1): ?><dd>
                  <div class="title"><span></span>基础信息管理</div>
                  <ul class="menuson">
                      <li><cite></cite><a href="<?php echo U('user/pwdmgr');?>" target="mainFrame">登陆密码修改</a><i></i></li>
                      <li><cite></cite><a href="<?php echo U('user/list');?>" target="mainFrame">人员信息设置</a><i></i></li>
                  </ul>
              </dd><?php endif; ?>
        </dl>
    </body>
</html>