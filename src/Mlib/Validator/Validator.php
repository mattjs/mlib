<?php
namespace Mlib\Validator;

class Validator {
	
	protected $fields = array();		
	
	public function __construct(Array $config) {
		for($i = 0; $i < count($config); $i++) {
			$this->add($config[$i]['name'], $config[$i]['validators']);
		}
	}
	
	public function add($name, Array $config) {
		$this->fields[$name] = $config;
	}
	
	/**
	 * @todo return errors if field not set
	 */
	public function test($name, $value) {
		return isset($this->fields[$name]) && $this->_test($this->fields[$name], $value);
	}
	
	public function error() {
		// Get recent error
	}
	
	/**
	 * @todo add errors if test does not exist, return more detailed errors about invalid
	 */
	protected function _test(Array $validators, $value) {
		$valid = true;
		for($i = 0; $i < count($validators); $i++) {
			switch($validators[$i]['name']) {
				case 'string_length':
					$valid = $this->string_length($validators[$i]['options'], $value);
					break;
					
				case 'regex':
					$valid = $this->regex($validators[$i]['expression'], $value);
					break;
				default:
					// raise error
			}
			
			if(!$valid) {
				break;
			}
		}
		return $valid;
	}
	
	private function string_length(Array $options, $value) {
		$length = strlen($value);
		return $length >= $options['min'] && $length <= $options['max'];
	}
	
	private function regex(Array $options, $value) {
		return preg_match($options['expression'], $value);
	}
}