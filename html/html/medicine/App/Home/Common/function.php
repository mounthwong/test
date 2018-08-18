<?php
/*定义分组公共函数的地方*/
function clearSQL($str){
	$str = str_replace( '"', '\"' ,$str ) ;
	$str = str_replace( '\\\\', '\\' ,$str ) ;
	return trim($str);
}
function daddslashes($string, $force = 0, $strip = false) {
	if (!get_magic_quotes_gpc() || $force) {
		if (is_array($string)) {
			// 如果其为一个数组则循环执行此函数
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force, $strip);
			}
		} else {
			// 下面是一个三元操作符，如果$strip为true则执行stripslashes去掉反斜线字符，再执行addslashes
			// 这里为什么要将$string先去掉反斜线再进行转义呢，因为有的时候$string有可能有两个反斜线，stripslashes是将多余的反斜线过滤掉
			$string = addslashes($strip ? stripslashes($string) : $string);
		}
	}
	return $string;
}
//判断字符串的的编码类型
function getchar($str,$formcode,$aftercode){
	// $encode = mb_detect_encoding($str, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
	// //return $encode;
 //    if ($encode != $aftercode){
 //      $str = iconv($formcode,$aftercode,$str);
 //    }
    return $str;
}


//文件下载
function downfile($file_path,$file_name)
{
	header("Content-type:text/html;charset=utf-8");
	//首先要判断给定的文件存在与否
	if(!file_exists($file_path)){
		echo "没有该文件文件";
		exit;
	}
	$fp=fopen($file_path,"r");
	$file_size=filesize($file_path);

	//下载文件需要用到的头
	Header("Content-type: application/octet-stream");
	Header("Accept-Ranges: bytes");
	Header("Accept-Length:".$file_size);
	Header("Content-Disposition: attachment; filename=".$file_name);
	$buffer=1024;
	$file_count=0;
	$file_con="";
	//向浏览器返回数据
	while(!feof($fp) && $file_count<$file_size){
		$file_con=$file_con.fread($fp,$buffer);
		$file_count+=$buffer;
	}
	echo $file_con;
	fclose($fp);
}

  function Chinese_Money_Max($i,$s=1){
    $c_digIT_min = array("零","十","百","千","万","亿","兆");
    $c_num_min = array("零","一","二","三","四","五","六","七","八","九","十");

    $c_digIT_max = array("零","十","百","千","万","亿","兆");
    $c_num_max = array("零","一","二","三","四","五","六","七","八","九","十");

    if($s==1){
        $c_digIT = $c_digIT_max;
        $c_num = $c_num_max;
    }else{
        $c_digIT = $c_digIT_min;
        $c_num = $c_num_min;
    }

    if($i<0)
        return "负".Chinese_Money_Max(-$i);
        //return "-".Chinese_Money_Max(-$i);
    if ($i < 11)
        return $c_num[$i];
    if ($i < 20)
        return $c_num[1].$c_digIT[1] . $c_num[$i - 10];
    if ($i < 100) {
        if ($i % 10)
            return $c_num[$i / 10] . $c_digIT[1] . $c_num[$i % 10];
        else
            return $c_num[$i / 10] . $c_digIT[1];
    }
    if ($i < 1000) {
        if ($i % 100 == 0)
            return $c_num[$i / 100] . $c_digIT[2];
        else if ($i % 100 < 10)
            return $c_num[$i / 100] . $c_digIT[2] . $c_num[0] . Chinese_Money_Max($i % 100);
        else if ($i % 100 < 10)
            return $c_num[$i / 100] . $c_digIT[2] . $c_num[1] . Chinese_Money_Max($i % 100);
        else
            return $c_num[$i / 100] . $c_digIT[2] . Chinese_Money_Max($i % 100);
    }
    if ($i < 10000) {
        if ($i % 1000 == 0)
            return $c_num[$i / 1000] . $c_digIT[3];
        else if ($i % 1000 < 100)
            return $c_num[$i / 1000] . $c_digIT[3] . $c_num[0] . Chinese_Money_Max($i % 1000);
        else
            return $c_num[$i / 1000] . $c_digIT[3] . Chinese_Money_Max($i % 1000);
    }
    if ($i < 100000000) {
        if ($i % 10000 == 0)
            return Chinese_Money_Max($i / 10000) . $c_digIT[4];
        else if ($i % 10000 < 1000)
            return Chinese_Money_Max($i / 10000) . $c_digIT[4] . $c_num[0] . Chinese_Money_Max($i % 10000);
        else
            return Chinese_Money_Max($i / 10000) . $c_digIT[4] . Chinese_Money_Max($i % 10000);
    }
    if ($i < 1000000000000) {
        if ($i % 100000000 == 0)
            return Chinese_Money_Max($i / 100000000) . $c_digIT[5];
        else if ($i % 100000000 < 1000000)
            return Chinese_Money_Max($i / 100000000) . $c_digIT[5] . $c_num[0] . Chinese_Money_Max($i % 100000000);
        else
            return Chinese_Money_Max($i / 100000000) . $c_digIT[5] . Chinese_Money_Max($i % 100000000);
    }
    if ($i % 1000000000000 == 0)
        return Chinese_Money_Max($i / 1000000000000) . $c_digIT[6];
    else if ($i % 1000000000000 < 100000000)
        return Chinese_Money_Max($i / 1000000000000) . $c_digIT[6] . $c_num[0] . Chinese_Money_Max($i % 1000000000000);
    else
        return Chinese_Money_Max($i / 1000000000000) . $c_digIT[6] . Chinese_Money_Max($i % 1000000000000);
}

    function Chinese_Money($i){
 	$j=Floor($i);
    $x=($i-$j)*100;
    //return $x;
    //return Chinese_Money_Max($j)."元".Chinese_Money_Min($x)."整";
    return Chinese_Money_Max($j,'0');
}

    function split_str($string, $type='array', $charset='utf-8'){
    //通过ord()函数获取字符的ASCII码值，如果返回值大于 127则表示为中文字符的一半，再获取后一半组合成一个完整字符
    $flag = false;
    if(strtolower($charset) == 'utf-8'){
        //如果utf-8环境
        $flag = true;
        $string = iconv('utf-8', 'gbk//IGNORE', $string);//由于ord函数在gbk下单个中文长度为2，utf-8下长度为3
    }
    if(strtolower($charset) != 'gbk' && strtolower($charset) != 'utf-8')
    {
        exit('参数不合法!');
    }

    //把字符串转化为ascii码存入数组,如果是中文是由两个ASCII码组成，英文是一个
    $length = strlen($string);
    $result = array();
    for($i=0; $i<$length; $i++){
        if(ord($string[$i])>127){
            $result[] = ord($string[$i]).' '.ord($string[++$i]);
        }else{
            $result[] = ord($string[$i]);
        }
    }
    if(strtolower($type) == 'array'){
        //如果返回值要数组
        $str = '';
        foreach($result as $v){
            $isEmpty = strstr($v,' ');
            if(empty($isEmpty)){
                $tmpstr = chr($v);
                if($flag){
                    $tmpstr = iconv('gbk', 'utf-8', $tmpstr);
                }
                $data[] = $tmpstr;
            }else{
                list($a,$b) = explode(' ',$v);
                $tmpstr = chr($a).chr($b);
                if($flag){
                    $tmpstr = iconv('gbk', 'utf-8', $tmpstr);
                }
                $data[] = $tmpstr;
            }
        }
        return $data;
    }elseif(strtolower($type) == 'string'){
        $data = array();
        foreach($result as $v){
            $isEmpty = strstr($v,' ');
            if(empty($isEmpty)){
                $str .= chr($v);
            }else{
                list($a,$b) = explode(' ',$v);
                $str .= chr($a).chr($b);
            }
        }
        $str=iconv('gbk', 'utf-8', $str);
        return $str;
    }else{
        exit('参数不合法！');
    }
}

  //进行二维数组的排序
function sortt($data,$key) {
    if (count ( $data ) <= 1) {
      return $data;
    }
    $tem = $data [0][$key];
    $leftarray = array ();
    $rightarray = array ();
    for($i = 1; $i < count ( $data ); $i ++) {
        if ($data [$i][$key] >= $tem ) {
            $leftarray[] = $data[$i];
        } else {
            $rightarray[] = $data[$i];
        }
    }
    $leftarray=sortt($leftarray,$key,$type);
    $rightarray=sortt($rightarray,$key,$type);
    $sortarray = array_merge ( $leftarray, array ($data[0]), $rightarray );
    return $sortarray;
}

function change_to_quotes($str) {
    return sprintf("'%s'", $str);
}
?>
