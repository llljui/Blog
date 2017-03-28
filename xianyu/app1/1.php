<?php
header('charset:utf-8;Content_Type:text/plain');


$arr=array('name'=>'lixijiang','age'=>'25','grilfriend'=>'xianyu');
$io=$_REQUEST["io"];
if (!empty($io)) {
foreach ($arr as $k => $v) {
	if ($io===$k) {
		echo $v;
		die();
	}
    if ($k=='grilfriend') {
    	echo "未找到相关信息";
    }
}

	}
else{echo '请输入正确的数据';}

?>