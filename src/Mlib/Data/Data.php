<?php
namespace Mlib\Data;

/**
 * @todo raise errors for invalid formatted validator
 */ 

class Data {
	const EMAIL_RE = "/^[a-z0-9!#$%&'\*\+\/\=\?\^_`{|}~-]+(?:\.[a-z0-9!#$%&\'\*\+\/\=\?\^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i";	
	
	public static function init(Array $config) {
		for($i = 0; $i < count($config); $i++) {
			if(!array_key_exists('validators', $config[$i])) {
				$config[$i]['validators'] = $this->validators($config[$i]['type']);
			}
		}
		
		return $config;
	}
	
	protected function validators($type) {
		if(method_exists($this, $type.'_validators')) {
			return $this->{$type.'_validators'}();
		} else {
			// Raise exception
		}
	}
	
	protected function email_validators() {
		return array(
			'regex' => self::EMAIL_RE
		);
	}
}