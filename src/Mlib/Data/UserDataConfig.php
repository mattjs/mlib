<?php

namespace Mlib\Data;

class UserDataConfig implements DataConfigInterface {
		
	public function name() {
		return 'users';
	}
	
	public function details() {
		return array_merge(
			$this->base_details(),
			array(
				array(
					'name' => 'email',
					'type' => 'string',
					'options' => array(
						'length' => 100 /* Fuck your email if its longer than this */
					)
				)
			)
		);
	}
	
	public function base_details() {
		return array(
			array(
				'name' => 'id',
				'type' => 'integer',
				'options' => array(
					'autoincrement',
					'length' => 11
				)
			),
			array(
				'name' => 'ts',
				'type' => 'timestamp',
				'options' => array(
					'default' => 'current_timestamp'
				)
			),
			array(
				'name' => 'password',
				'type' => 'string',
				'options' => array(
					'length' => 64
				)
			),
			array(
				'name' => 'salt',
				'type' => 'string',
				'options' => array(
					'length' => 10
				)
			),
		);
	}
	
	/**
	 * @todo invalidate multiple primary keys
	 */
	public function keys() {
		return array(
			array(
				'field' => 'id',
				'type' => 'primary'
			)
		);
	}
}