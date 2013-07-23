<?php
namespace Mlib\Validator;

/**
 * @todo raise errors for invalid formatted validator
 */ 

class ValidatorBuilder {
	const EMAIL_RE = "/^[a-z0-9!#$%&'\*\+\/\=\?\^_`{|}~-]+(?:\.[a-z0-9!#$%&\'\*\+\/\=\?\^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i";	
	
	public static function build(Array $details) {
		for($i = 0; $i < count($details); $i++) {
			if(!array_key_exists('validators', $details[$i])) {
				$details[$i]['validators'] = self::validators($details[$i]['type']);
			}
		}
		return $details;
	}
	
	protected static function validators($type) {
		switch($type) {
			case 'email':
				$validators = self::email_validators();
				break;
			default:
				// Raise exception
		}
		return $validators;
	}
	
	protected static function email_validators() {
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