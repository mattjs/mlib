<?php
namespace Mlib\Model;

use Mlib\Model\Base;
use Mlib\Model\Session;

class User extends Base {
	private $lazyload = array('session');
	
	public function test() {
		return $this->session->test();
	}
	
	public function login() {
		
	}
	
	public function logout() {
		
	}
	
	public function __get($name) {
		if(property_exists($this, $name)) {
			return $this->$name;
		} else if(in_array($name, $this->lazyload)){
			return $this->{'_init_'.$name}();
		} else {
			return $this->__get_error();
		}
	}
	
	public function __set($name, $value) {
		if(in_array($name, $this->lazyload)){
			$this->$name = $value;
		}
	}
	
	protected function _init_session() {
		$this->session = new Session($this->adapter);
		return $this->session;
	}
}