<?php
namespace Mlib\Form;

use Field;

class Form {
	protected $forms;
	protected $fields;
	
	public function __construct(Array $forms, Array $fields) {
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
	
	public function fields() {
		return $this->fields;
	}
	
	public function match($name, $request) {
		
	}
}
