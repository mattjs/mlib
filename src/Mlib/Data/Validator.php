<?php
namespace Mlib\Data;

class Validator {
	protected $fields = array();
	
	public function add($name, $config) {
		$this->fields[$name] = $config;
	}
	
	/**
	 * @todo return errors if field not set
	 */
	public function test($name, $value) {
		return isset($this->fields[$name]) && $this->_test($this->fields[$name], $value);
	}
	
	protected function _test($validators, $value) {
		$valid = true;
		for($i = 0; $i < count($validators); $i++) {
			if(!$this->run_test($validators[$i]['name'], $validators[$i], $value)) {
				$valid = false;
				break;
			}
		}
		return $valid;
	}
	
	protected function run_test() {
		
	}
}
