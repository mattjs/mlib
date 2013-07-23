<?php
namespace Mlib\Validator;

class Validator {
	
	protected $fields = array();
	protected $errors = array();	
	
	public function __construct(ValidatorConfigInterface $config) {
		$details = ValidatorBuilder::build($config->details());
		for($i = 0; $i < count($details); $i++) {
			$this->add($details[$i]['name'], $details[$i]);
		}
	}
	
	public function add($name, Array $config) {
		$this->fields[$name] = $config;
	}
	
	/**
	 * @todo return errors if field not set
	 */
	public function test($name, $value) {
		$valid = false;
		if(isset($this->fields[$name])) {
			$valid = $this->_test($this->fields[$name]['validators'], $value, $name);
		} else {
			// Raise error
		}
		return $valid;
	}
	
	public function errors() {
		// Get recent errors
		return $this->errors;
	}
	
	public function clear_errors() {
		$this->errors = array();		
	}
	
	protected function add_error($type, $message) {
		$this->errors[] = array('type' => $type, 'message' => $message);
	}
	
	/**
	 * @todo add errors if test does not exist, return more detailed errors about invalid
	 */
	protected function _test(Array $validators, $value, $name) {
		$this->clear_errors();
		$valid = true;
		for($i = 0; $i < count($validators); $i++) {
			switch($validators[$i]['name']) {
				case 'string_length':
					if(!$this->string_length($validators[$i]['options'], $value, $name)) {
						$valid = false;
					}
					break;
					
				case 'regex':
					if(!preg_match($validators[$i]['expression'], $value)) {
						$valid = false;
						$this->add_error('Invalid', ucfirst($name).' is not valid');
					}
					break;
				default:
					// raise error
			}
		}
		return $valid;
	}
	
	private function string_length(Array $options, $value, $name) {
		$valid = true;
		
		$length = strlen($value);
		if(isset($options['min'])) {
			if($length < $options['min']) {
				$valid = false;
				$this->add_error('TooShort', ucfirst($name).' must be greater than '.$options['min'].' characters');
			}
		} elseif(isset($options['max'])) {
			if($length >  $options['max']) {
				$valid = false;
				$this->add_error('TooLong', ucfirst($name).' must be less than than '.$options['max'].' characters');
			}
		} else {
			// Usage error
		}
		return $valid;
	}
}