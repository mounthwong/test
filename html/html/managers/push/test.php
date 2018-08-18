<?php
$content=file_get_contents('1.txt');
$pattern = '/<div class="t_fsz">([\s\S]*?)<\/div>/i';
//声明一个包含多个URL链接地址的多行文
$i = 1;    //定义一个计数器，用来统计搜索到的结果数
//搜索全部的结果
if(preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
  foreach($matches as $urls) {     //循环遍历二维数组$matches
      var_dump($urls);
  }
} else {
        echo "搜索失败！";
}
?>
