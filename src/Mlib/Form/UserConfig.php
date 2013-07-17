<?php
namespace Mlib\Form;

class UserConfig implements FormConfig {
	public static function fields() {
		return array(
			 array(
				'name' => 'email',
				'type' => 'text',
				'element' => 'input',
				'properties' => array(
					'placeholder' => 'Email Address'
				)
			),
			array(
				'name' => 'password',
				'type' => 'password',
				'element' => 'input',
				'properties' => array(
					'placeholder' => 'Password'
				)
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