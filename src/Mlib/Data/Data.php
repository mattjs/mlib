<?php
namespace Mlib\Data;

class Data {
	protected $config;
	protected $name;
	
	public function __construct($name, Array $config) {
		// Deal with config
		$this->config = $config;
		$this->name = $name;
	}
	
	public function generate_schema() {
		for($i = 0; $i < count($this->config); $i++) {
			
		}
		return 'test';
	}
}