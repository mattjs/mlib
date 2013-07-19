<?php
namespace Mlib\Data\Set;

class Set {
	public $member_names;
	protected $data_configs;
	
	public function __construct(SetConfigInterface $config) {
		$details = $config->details();
		for($i = 0; $i < count($details); $i++) {
			$this->data_configs[$details[$i]['name']] = $details[$i]['name'];
			$this->member_names[] = $details[$i]['name'];
		}
	}
	
	public function get_data_config($name) {
		return new $this->data_configs[$name];
	}
}