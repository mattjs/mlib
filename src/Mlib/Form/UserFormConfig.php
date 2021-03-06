<?php
namespace Mlib\Form;

class UserFormConfig implements FormConfigInterface {
	public static function fields() {
		return array(
			 array(
				'name' => 'email',
				'type' => 'text',
				'element' => 'input',
				'title' => 'Email Address'
			),
			array(
				'name' => 'password',
				'type' => 'password',
				'element' => 'input',
				'title' => 'Password'
			)
		);
	}
	
	public static function forms() {
		return array(
			array(
				'name' => 'login',
				'fields' => array(
					array(
						'name' => 'email',
						'required' => true
					),
					array(
						'name' => 'password',
						'required' => true
					)
				)
			),
			array(
				'name' => 'create',
				'fields' => array(
					array(
						'name' => 'email',
						'required' => true
					),
					array(
						'name' => 'password',
						'required' => true
					),
					array(
						'name' => 'passwordVerify',
						'verifies' => 'password',
						'required' => true
					)
				)
			)
		);
	}
}