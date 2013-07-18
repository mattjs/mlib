<?php
namespace Mlib\Form;

class Field {
	public $name;
	public $type;
	public $element;
	public $title;
	public $required;
	
	protected $properties = array();
	
	public function __construct(Array $info) {
		$this->name = $info['name'];
		$this->type = $info['type'];
		$this->element = $info['element'];
		
		if(isset($info['title'])) {
			$this->title = $info['title'];
		}
		
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
	
	public function set_title($title) {
		$this->title = $title;
	}
	
	public function properties() {
		// Build html string
		$str = '';
		foreach($this->properties as $name => $value) {
			$str .= $name.'="'.$value.'" ';
		}
		return substr($str,  0, -1);
	}
}