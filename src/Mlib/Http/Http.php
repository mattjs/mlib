<?php

namespace Mlib\Http;

class Http {
	public static function init() {
		$curl = curl_init();
		
		// Default settings
		curl_setopt_array($curl, array(
    		CURLOPT_RETURNTRANSFER => 1,
		));
		
		return $curl;
	}
	
	public static function get($url, $data=array()) {
		$curl = self::init();
		curl_setopt($curl, CURLOPT_URL, $url.(!empty($data)?'?'.http_build_query($data):''));
		
		$result = curl_exec($curl);
		if($result === false) {
			$result = self::error($curl);
		}
		curl_close($curl);
		return $result;
	}
	
	public static function post($url, $data=array()) {
		$curl = self::init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_POST => 1
		));
		
		if(!empty($data)) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		}
		
		$result = curl_exec($curl);
		if($result === false) {
			$result = self::error($curl);
		}
		curl_close($curl);
		return $result;
	}
	
	public static function put($url) {
		$curl = self::init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_PUT => 1
		));
		
		$result = curl_exec($curl);
		if($result === false) {
			$result = self::error($curl);
		}
		curl_close($curl);
		return $result;
	}
	
	protected static function error($curl) {
		return array(
			'error' => array(
				'number' => curl_errno($curl),
				'message' => curl_error($curl)
			)
		);
	}
}