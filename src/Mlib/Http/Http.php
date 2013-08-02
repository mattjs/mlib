<?php

namespace Mlib\Http;

class Http {
	protected $curl;
	
	public static function init() {
		$this->curl = curl_init();
		
		// Default settings
		curl_setopt_array($this->curl, array(
    		CURLOPT_RETURNTRANSFER => 1,
		));
	}
	
	public static function get($url, $data=array()) {
		self::init();
		curl_setopt($this->curl, CURLOPT_URL, $url.(!empty($data)?'?'.http_build_query($data):''));
		
		$result = curl_exec($this->curl);
		if($result === false) {
			$result = self::error();
		}
		self::close();
	}
	
	public static function post($url, $data=array()) {
		self::init();
		curl_setopt_array($this->curl, array(
			CURLOPT_URL => $url,
			CURLOPT_POST => 1
		));
		
		if(!empty($data)) {
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
		}
		
		$result = curl_exec($this->curl);
		if($result === false) {
			$result = self::error();
		}
		self::close();
	}
	
	protected static function error() {
		return array(
			'error' => array(
				'number' => curl_errno($this->curl),
				'message' => curl_error($this->curl)
			)
		);
	}
	
	protected static function close() {
		curl_close($this->curl);
	}
}