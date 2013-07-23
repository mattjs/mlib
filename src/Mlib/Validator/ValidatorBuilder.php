<?php
namespace Mlib\Validator;

/**
 * @todo raise errors for invalid formatted validator
 */ 

class ValidatorBuilder {
	const EMAIL_RE = "/^[a-z0-9!#$%&'\*\+\/\=\?\^_`{|}~-]+(?:\.[a-z0-9!#$%&\'\*\+\/\=\?\^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i";	
	
	public function build(ValidatorConfigInterface $config) {
		$details = $config->details();
		for($i = 0; $i < count($details); $i++) {
			if(!array_key_exists('validators', $details[$i])) {
				$details[$i]['validators'] = $this->validators($details[$i]['type']);
			}
		}
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