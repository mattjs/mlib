<?php

namespace Mlib\Data;

use Config;

class User extends Config {
	public static function basic() {
		return array(
			array(
				'name' => 'id',
				'type' => 'integer',
				'options' => array(
					'autoincrement'
				)
			),
			array(
				'name' => 'ts',
				'type' => 'timestamp',
				'options' => array(
					'default' => 'current_timestamp'
				)
			),
		);
	}
	
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