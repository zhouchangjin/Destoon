<?php
$time = time();
$starttime = $time - 24*3600;//24小时
$query = "select linkurl FROM {$DT_PRE}article_21 where edittime > $starttime ORDER BY itemid ASC";
$result = $db->query($query);
$urls="";

while ($r=$db->fetch_array(($result)))
{
	$linkurl = $r['linkurl'];
	$urls.="http://www.51meiyu.cn/news/".$linkurl.",";
}
$urls=substr($urls,0,-1);
$urls = explode(",",$urls);
//修改下一行
$api = 'http://data.zz.baidu.com/urls?site=www.51meiyu.cn&token=kXk87m52D6hHSGkb';
$ch = curl_init();
$options = array(
CURLOPT_URL => $api,
CURLOPT_POST => true,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_POSTFIELDS => implode("\n", $urls),
CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
);
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
echo $result;
?>