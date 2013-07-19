<?php
namespace Mlib\Data\Set;

class Set {
	protected $details;
	
	public function __construct(SetConfigInterface $config) {
		$this->details = $config->details();
	}
	
	public function member_names() {
		return $this->details;
	}
}