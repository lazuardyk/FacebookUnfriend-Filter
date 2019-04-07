<?php
set_time_limit(0);

function friendlist($token){
	$a = json_decode(file_get_contents('https://graph.facebook.com/me/friends?access_token='.$token), true);
	return $a['data'];
}

function filterkata($kata,$id, $tok){
	$arr = explode(',', $kata);
	$a = file_get_contents('https://graph.facebook.com/'.$id.'/posts?access_token='.$tok);
	$unf = false;
	foreach($arr as $kat){
		if (preg_match('@'.$kat.'@i', $a, $matches)){
			$unf = true;
		} else {
			$unf = false;
		}
	}
	return $unf;
}
function unfriend($id, $token){
	$url = 'https://graph.facebook.com/me/friends?uid='.$id.'&access_token='.$token;
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    $result = curl_exec($ch);
    curl_close($ch);
	return $result;
}

echo "Masukkan access token fb:";
$access_token = trim(fgets(STDIN));
echo "Masukkan kata-kata (pisah dengan koma):";
$kata = trim(fgets(STDIN));
$FL = friendlist($access_token);
foreach($FL as $list){
	$name = $list['name'];
	$id = $list['id'];
	if(filterkata($kata,$id,$access_token)){
		echo $name.' => FILTERED => '.unfriend($id, $access_token);
		echo "\r\n";
	}else{
		echo $name.' => AMAN';
		echo "\r\n";
	}
}

?>