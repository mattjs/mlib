<?php
namespace Mlib\Form;

class Field {
	public $name;
	public $type;
	public $element;
	
	protected $properties = array();
	
	public function __construct(Array $info) {
		$this->name = $info['name'];
		$this->type = $info['type'];
		$this->element = $info['element'];
		
		if(isset($info['properties'])) {
			$this->properties = $info['properties'];
		}
	}
	
	public function set_property($name, $value) {
		$properties[$name] = $value;
	}
	
	public function set_name($name) {
		$this->name = $name;
	}
	
	public function set_placeholder($placeholder) {
		$this->properties['placeholder'] = $placeholder;
	}
	
	public function get_placeholder() {
		if(isset($this->properties['placeholder'])) {
			return $this->properties['placeholder'];
		}
	}
	
	public function properties() {
		// Build html string
		$str = '';
		for($i = 0; $i < count($this->properties); $i++) {
			
		}
		return $str;
	}
}