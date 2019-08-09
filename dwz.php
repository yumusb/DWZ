<?php
include './includes/common.php';

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-Type: text/html; charset=utf-8");


function getsinaurl($longurl) {
	$appkey = '31641035';
	$url='http://api.t.sina.com.cn/short_url/shorten.json?source='.$appkey.'&url_long='.urlencode($longurl);
	$result = httpGet($url);
	$arr = json_decode($result, true);
	return isset($arr[0]['url_short'])?$arr[0]['url_short']:false;
}
function getdwz($longurl) {
	$url='https://302.pub/api.php?url='.rawurlencode($longurl);
	$result = httpGet($url);
	$arr = json_decode($result, true);
	return isset($arr['code'])?$arr['code']:false;
}
function geturlcn($longurl){
	$url = "https://vip.video.qq.com/fcgi-bin/comm_cgi?name=short_url&need_short_url=1&url=".rawurlencode($longurl);
	$result = httpGet($url);
	$result = explode('"',explode('short_url" : "', $result)[1])[0];
	return $result;
}
function get_curl($url, $post=0, $referer=0, $cookie=0, $header=0, $ua=0, $nobaody=0)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$httpheader[] = "Accept:application/json";
	$httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
	$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
	$httpheader[] = "Connection:close";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
	if ($post) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if ($header) {
		curl_setopt($ch, CURLOPT_HEADER, true);
	}
	if ($cookie) {
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	if($referer){
		if($referer==1){
			curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
		}else{
			curl_setopt($ch, CURLOPT_REFERER, $referer);
		}
	}
	if ($ua) {
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
	}
	else {
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; U; Android 4.0.4; es-mx; HTC_One_X Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0");
	}
	if ($nobaody) {
		curl_setopt($ch, CURLOPT_NOBODY, 1);
	}
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}
function httpGet($url) {
	$curl = curl_init();
	$httpheader[] = "Accept:*/*";
	$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
	$httpheader[] = "Connection:close";
	curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 3);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_URL, $url);
	$res = curl_exec($curl);
	curl_close($curl);
	return $res;
}
if(!empty($_POST['longurl'])){
	if(strpos($_POST['longurl'],'http')===false)
		$longurl = 'http://'.daddslashes($_POST['longurl']);
	else
		$longurl = daddslashes($_POST['longurl']);
}else{
    exit('{"msg":"URL不能为空"}');
}



$sina_url = getsinaurl($longurl);
$dwz_url = getdwz($longurl);
$ten_url = geturlcn($longurl);
$data = '{
    "sina_url":"'.$sina_url.'",
	"dwz_url":"'.$dwz_url.'",
	"ten_url":"'.$ten_url.'",
    "qr":"https://www.kuaizhan.com/common/encode-png?large=true&data='.$sina_url.'"
}';
echo $data;