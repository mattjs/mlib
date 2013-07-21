<?php

namespace Mlib\Data;

class SessionDataConfig implements DataConfigInterface {
		
	public function name() {
		return 'sessions';
	}
	
	public function details() {
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
				'name' => 'expires',
				'type' => 'timestamp',
				'options' => array(
					'default' => 'current_timestamp'
				)
			),
			array(
				'name' => 'token',
				'type' => 'string',
				'options' => array(
					'length' => 32
				)
			),
			array(
				'name' => 'user_id',
				'type' => 'integer',
				'options' => array(
					'length' => 11
				)
			)
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