<?php
/**
 * 技术交流群：287546175  欢迎大家加入
 * http://www.jscto.net/html/616.html
 */
require 'QueryList.class.php';





//采iteye的内容页内容
$url = "http://gu.qq.com/usVIPS.N/gg";
$reg = array("xianjia"=>array(".item-1 span:eq(0)","text"),"jinkai"=>array(".col-2.fr span:eq(1)","text"),"zuoshou"=>array(".col-2.fr span:eq(9)","text"),"zuigao"=>array(".col-2.fr span:eq(3)","text"),"zuidi"=>array(".col-2.fr span:eq(11)","text"));
$qy = new QueryList($url,$reg);
$arr = $qy->jsonArr;
echo "<pre>";
print_r($arr);
echo "</pre>";

?>