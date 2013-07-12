<?php

namespace Mlib\Form;

use Config;

class User extends Config {
	public static function fields() {
		return array(
			 array(
				'name' => 'email',
				'type' => 'text',
				'element' => 'input'
			),
			array(
				'name' => 'password',
				'type' => 'password',
				'element' => 'input'
			)
		);
	}
	
	public static function config() {
		return array(
			array(
				'name' => 'login',
				'fields' => array(
					'email',
					'password'
				)
			),
			array(
				'name' => 'create',
				'fields' => array(
					'email',
					'password',
					array(
						'name' => 'passwordVerify',
						'verifies' => 'password'
					)
				)
			)
		);
	}
}