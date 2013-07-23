<?php
namespace Mlib\Form;

class EmptyFormConfig implements FormConfigInterface {
	public static function fields() {
		return array();
	}
	
	public static function forms() {
		return array();
	}
}