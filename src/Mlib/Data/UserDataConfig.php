<?php

namespace Mlib\Data;

class UserDataConfig implements DataConfigInterface {
		
	public function name() {
		return 'user';
	}
	
	public function details() {
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
	
	
}