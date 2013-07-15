<?php

namespace Mlib\Validator;

use ValidatorConfig;

class User implements ValidatorConfig {
	public static function config() {
		return array(
			array(
				'name' => 'email',
				'type' => 'email'
			),
			array(
				'name' => 'password',
				'type' => 'string',
				'validators' => array(
					array(
						'name' => 'string_length',
						'options' => array(
							'min' => 6,
							'max' => 30
						)
					),
					array(
						'name' => 'regex',
						'expression' => "/[\w\d\!\@\#\$\%\^\&\*\(\)\_\-\+\?]+/"
					)
				)
			),
		);
	}	
}