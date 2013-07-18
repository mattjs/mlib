<?php
namespace Mlib\Data\Sets;

class Set {
	protected $details;
	
	public function __construct(SetConfigInterface $config) {
		$this->details = $config->details();
	}
	
	public function data_names() {
		
	}
}