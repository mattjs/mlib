<?php

namespace Mlib\Data;

class UserConfig implements DataConfig {
	public $name = 'user';
	
	public static function config() {
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