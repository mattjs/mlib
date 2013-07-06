<?php
namespace Mlib\Model;

use Mlib\Model\Base;
use Mlib\Model\Session;

class User extends Base {
	private $session;
	private $properties = array('session');
	
	public function test() {
		return $this->session->test();
	}
	
	public function login() {
		
	}
	
	public function logout() {
		
	}
	
	public function __get($name) {
		if(isset($this->${$name}) && $this->${$name}) {
			return $this->${$name};
		} else if(in_array($this->properties, $name)){
			return $this->${'_init_'.$name}();
		} else {
			return $this->__get_error();
		}
	}
	
	protected function _init_session() {
		$this->session = new Session($this->adapter);
		return $this->session;
	}
}