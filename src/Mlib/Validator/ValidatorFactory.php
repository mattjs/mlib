<?php
namespace Mlib\Validator;

/**
 * @todo raise errors for invalid formatted validator
 */ 

class ValidatorFactory {
	const EMAIL_RE = "/^[a-z0-9!#$%&'\*\+\/\=\?\^_`{|}~-]+(?:\.[a-z0-9!#$%&\'\*\+\/\=\?\^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i";	
	
	public function configure(Array $config) {
		for($i = 0; $i < count($config); $i++) {
			if(!array_key_exists('validators', $config[$i])) {
				$config[$i]['validators'] = $this->validators($config[$i]['type']);
			}
		}
		
		return $config;
	}
	
	protected function validators($type) {
		$method = $type.'_validators';
		if(method_exists($this, $method)) {
			return $this->$method();
		} else {
			// Raise exception
		}
	}
	
	protected function email_validators() {
		return array(
			array(
				'name' => 'regex',
				'expression' => self::EMAIL_RE
			),
			array(
				'name' => 'string_length',
				'options' => array(
					'max' => 100
				)
			)
		);
	}
}