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
		$schema = 'CREATE TABLE `'.$this->name.'`';
		
		for($i = 0; $i < count($this->details); $i++) {
			
		}
		
		$schema .= $this->engine();
		
		return $schema;
	}
	
	public function engine() {
		return 'ENGINE=InnoDB';
	}
}