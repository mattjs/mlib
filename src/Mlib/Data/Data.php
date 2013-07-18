<?php
namespace Mlib\Data;

class Data {
	protected $details;
	protected $name;
	
	public function __construct(DataConfigInterface $config) {
		$this->name = $config->name();
		$this->details = $config->details();
	}
	
	public function generate_schema() {
		for($i = 0; $i < count($this->config); $i++) {
			
		}
		return 'test';
	}
}