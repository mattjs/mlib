<?php
namespace Mlib\Form;

class Form {
	protected $forms;
	protected $_forms;	
	protected $fields;
	
	public function __construct(FormConfigInterface $config) {
		$forms = $config->forms();
		$fields = $config->fields();
		
		for($i = 0; $i < count($forms); $i++) {
			$this->add_form($forms[$i]['name'], $forms[$i]['fields']);
		}
		for($i = 0; $i < count($fields); $i++) {
			$this->add_field($fields[$i]);
		}
	}
	
	public function add_form($name, $fields) {
		$this->forms[$name] = $fields;
	}
	
	public function add_field($info) {
		$field = new Field($info);
		$this->fields[$field->name] = $field;
	}

	public function get($name) {
		if(!isset($this->_forms[$name])) {
			$this->_forms[$name] = array();
			for($i = 0; $i < count($this->forms[$name]); $i++) {
				if(isset($this->forms[$name][$i]['verifies'])) {
					$field = clone $this->fields[$this->forms[$name][$i]['verifies']];
					$field->set_name($this->forms[$name][$i]['name']);
					$field->set_title('Verify '.$field->title);
				} else {
					$field = clone $this->fields[$this->forms[$name][$i]['name']];
				}
				
				$field->required = $this->forms[$name][$i]['required'];
				$this->_forms[$name][$this->forms[$name][$i]['name']] = $field;
			}
		}
		return $this->_forms[$name];
	}
	
	public function match($name, $request) {
		$fields = $this->forms[$name];
		$result = $this->_same_fields($fields, $request);
		if($result === true) {
			
		} else {
			
		}
	}
	
	protected function _same_fields($fields, $request) {
		
		/*$keys = array_keys($request);
		$flat = $this->flat_fields($fields);
		$diff = array_diff($flat, $keys);
		return empty($diff) || array('missing' => $diff, 'extra' => array_diff($keys, $flat));*/
	}
	
	protected function flat_fields($fields) {
		$result = array();
		for($i = 0; $i < count($fields); $i++) {
			$result[] = $fields[$i]['name'];
		}
		return $result;
	}
}
