<?php
	
	function __ncce_request($request = '', $param = '', $database = '', $requester = 'nc10percent')
	{
		$debug = 0;
		if ($debug) echo '('.$request.':'.$param.')';
		$url = "https://newton.ces.ncsu.edu/ncce_connect/".$requester."_".$database.".php?r=".$request."&p=".$param;
		$encKey = time() . $url . date(DATE_RFC822);
		$encKeymd5 = md5($encKey);
		$url .= '&x='.$encKeymd5;
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_CBC), MCRYPT_RAND);
		$url .= '&y='. str_replace('+', '_', base64_encode(serialize($iv)));
		if ($debug) echo $url;
	
		$result = false;
		$ch = curl_init();
		if ($ch) {
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$content = @curl_exec($ch);
			if (($content === false) || (strlen($content) === 0)) {
				return false;
			} else {
				// ECB doesn't use $iv, if you use a mode that uses $iv it has to be the same for encrypt/decrypt
				$content = mcrypt_decrypt(MCRYPT_TRIPLEDES, sha1($encKeymd5), base64_decode($content), MCRYPT_MODE_CBC, $iv);
				$result = unserialize($content);
				if ($debug) print_r($result);
			}
			/*
				$info = curl_getinfo($ch);
				print_r($info);
			*/
			curl_close($ch);
			return $result;
		}
		return false;
	}
	
	/*
	$result = __ncce_request('request', 'param', 'xemp');
	if ($result === false) {
		echo '<p>Error attempting to load URL</p>';
	} else {
		echo '<p>'.count($result) . ' records found</p>';
		foreach ($result as $k => $v) {
			print_r($v); echo '<br />';
		}
	}
	*/
?>