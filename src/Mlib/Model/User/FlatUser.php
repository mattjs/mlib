<?php
namespace Mlib\Model\User;

class FlatUser {
	public $logged_in;
	protected $details;
	protected $session;
	
	public function __construct($logged_in, $details, $session) {
		$this->logged_in = $logged_in;
		$this->details = $details;
		$this->session = $session;
	}
	
	public function __get($name) {
		if(array_key_exists($name, $details)) {
			return $this->details[$name];
		}
		return null;
	}
}