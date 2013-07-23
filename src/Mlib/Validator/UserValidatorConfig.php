<?php

namespace Mlib\Validator;

class UserValidatorConfig implements ValidatorConfigInterface {
	public function details() {
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