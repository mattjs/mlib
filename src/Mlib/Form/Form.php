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
					$field->name = $this->forms[$name][$i]['name'];
					$field->title = 'Verify '.$field->title;
					$field->verifies = $this->forms[$name][$i]['verifies'];
				} else {
					$field = clone $this->fields[$this->forms[$name][$i]['name']];
				}
				
				$field->required = $this->forms[$name][$i]['required'];
				$this->_forms[$name][$this->forms[$name][$i]['name']] = $field;
			}
		}
		return $this->_forms[$name];
	}
	
	public function match($form_name, &$request) {
		$result = array();
		
		$missing = array();
		$extra = array_intersect($this->forms[$form_name], $request);
		$bad_verify = array();
		
		$fields = $this->get($form_name);
		foreach($fields as $name => $field) {
			if($field->required) {
				if(!isset($request[$name])) {
					$missing[] = $name;
				}
			}
			
			if($field->verifies
			&& $request[$name] != $request[$field->verifies]) {
				$bad_verify[] = $field->verifies;
			} else {
				unset($request[$name]); // Unset verify field
			}
		}
		
		if(count($missing)) {
			$result['missing'] = $missing;
		}
		if(count($extra)) {
			$result['missing'] = $missing;
		}
		if(count($bad_verify)) {
			$result['missing'] = $missing;
		}
		
		if(count($result)) {
			return $result;
		} else {
			return true;
		}
	}
}
