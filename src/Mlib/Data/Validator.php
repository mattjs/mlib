<?php
namespace Mlib\Data;

class Validator {
	protected $fields = array();
	
	public function add($name, Array $config) {
		$this->fields[$name] = $config;
	}
	
	/**
	 * @todo return errors if field not set
	 */
	public function test($name, $value) {
		return isset($this->fields[$name]) && $this->_test($this->fields[$name], $value);
	}
	
	protected function _test(Array $validators, $value) {
		$valid = true;
		for($i = 0; $i < count($validators); $i++) {
			$name = $validators[$i]['name']; unset($validators[$i]['name']);
			if(!$this->run_test($name, $validators[$i], $value)) {
				$valid = false;
				break;
			}
		}
		return $valid;
	}
	
	/**
	 * @todo add errors if test does not exist
	 */
	protected function run_test($name, Array $options, $value) {
		if(method_exists($this, $name)) {
			return $this->$name($options, $value);
		} else {
			return false;
		}
	}
	
	private function string_length(Array $options, $value) {
		$length = strlen($value);
		return $length >= $options['min'] && $length <= $options['max'];
	}
	
	private function regex(Array $options, $value) {
		return preg_match($options['expression'], $value);
	}
}
