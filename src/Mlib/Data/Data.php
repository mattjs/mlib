<?php
namespace Mlib\Data;

class Data {
	protected $config;
	
	public function __construct(Array $config) {
		// Deal with config
		$this->config = $config;
	}
	
	public function generate_schema() {
		for($i = 0; $i < count($this->config); $i++) {
			
		}
		return 'test';
	}
}