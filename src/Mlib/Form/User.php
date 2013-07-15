<?php

namespace Mlib\Form;

use FormConfig;

class User implements FormConfig {
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
	
	public static function forms() {
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