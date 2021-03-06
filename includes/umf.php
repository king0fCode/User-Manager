<?php
include_once(__DIR__.'/config.php');
function UM_randomString($len){
	$string = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
	$length=strlen($string);
	$out='';
	if(function_exists('random_bytes')){
		$r=random_bytes($len);
		for($i=0;$i<$len;$i++){
			$k=intval(floor(ord($r[$i])*($length-1)/255));
			$out.=$string[$k];
		}
	}else{
		for($i=0;$i<$len;$i++){
			$rand=rand(0,$length-1);
			$out.=$string[$rand];
		}
	}
	return $out;
}
function UM_PASSWORD($password){
	return password_hash($password,PASSWORD_DEFAULT);
}

function UM_PASSWORD_VERIFY($password,$hash){
	return password_verify($password,$hash);
}
function UM_VerifyCaptcha($response){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,'secret='.urlencode(UM_CAPTCHA_SECRET).'&response='.urlencode($response));
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	$json=json_decode($result,true);
	curl_close($ch);
	return @$json['success'];
}
function decodeConfig($c){
	global $UM_PERM;
	$c=intval($c);
	$o=array();
	foreach($UM_PERM as $k=>$v){
		$k=intval($k);
		$o[$k]=((($c&pow(2,$k))>>$k)==1);
	}
	return $o;
}
function encodeConfig($setting){
	if(!$setting)
		return 0;
	global $UM_PERM;
	$o=0;
	foreach($UM_PERM as $k=>$v){
		$k=intval($k);
		if($setting[$v])
			$o+=pow(2,$k);
	}
	return $o;
}
?>
