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
	}
	
	public function set_property($name, $value) {
		$properties[$name] = $value;
	}
	
	public function properties() {
		// Build html string
		return '';
	}
}