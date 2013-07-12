<?php
namespace Mlib\Model;

use Mlib\Model\Base;
use Mlib\Model\Session;

use Mlib\Data;
use Mlib\Valid;
use Mlib\Form;

class User extends Base {	
	protected $table = 'user';
	protected $_data;
	protected $_session;
	protected $_validator;
	protected $_form;
	
	public function login() {
		
	}
	
	public function logout() {
		
	}
	
	public function create(Array $request) {
		$response = array();
		
		$valid = $this->form()->match('create', $request);
		
		$valid = $this->valid_data($request);
		
		if($valid === true) {
			
		} else {
			$response = $valid;
		}
		
		return $response;
	}
	
	public function authenticate($session_token) {
		
	}
	
	protected function valid_data(Array $request) {
		$errors = array();
		
		foreach($request as $field => $value) {
			if(!$this->validator()->test($field, $value)) {
				$errors[]= $this->validator()->error();
			}
		}
		
		return empty($errors) || $errors;
	}

	protected function session() {
		if(!$this->_session) {
			$this->_session = new Session($this->adapter);
		}
		return $this->_session;
	}
	
	protected function data() {
		if(!$this->_data) {
			$this->_data = Data::init($this->data_config());
		}
		return $this->_data;
	}
	
	protected function validator() {
		if(!$this->_validator) {
			$this->_validator = Valid($this->data());
		}
		return $this->_validator;
	}
	
	protected function form() {
		if(!$this->_form) {
			$this->_form = new Form($this->form_config(), $this->form_fields());
		}
		return $this->_form;
	}
	
	protected function data_config() {
		return Mlib\Data\User::config();
	}
	
	protected function form_config() {
		return Mlib\Form\User::config();
	}
	
	protected function form_fields() {
		return Mlib\Form\User::fields();
	}
}