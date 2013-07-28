<?php
namespace Mlib\Form;

class Field {
	public $name;
	public $type;
	public $element;
	public $title;
	public $required;
	public $verifies;
	public $options;
	
	protected $properties = array();
	
	public function __construct(Array $info) {
		$this->name = $info['name'];
		$this->element = $info['element'];
		
		if(isset($info['type'])) {
			$this->type = $info['type'];
		}
		
		if(isset($info['title'])) {
			$this->title = $info['title'];
		}
		
		if(isset($info['properties'])) {
			$this->properties = $info['properties'];
		}
		
		if($this->element == 'select') {
			$this->options = $info['options'];
		} 
	}
	
	public function set_property($name, $value) {
		$this->properties[$name] = $value;
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