<?php
namespace Mlib\Model;

use Mlib\Model\Base;
use Mlib\Model\Session;

use Mlib\Data;
use Mlib\Validator;
use Mlib\Form;

class User extends Base {	
	protected $table = 'user';
	protected $_details;
	protected $_data;
	protected $_session;
	protected $_validator;
	protected $_form;
	
	public function login() {
		
	}
	
	public function logout() {
		
	}
	
	public function create(Array $request) {
		$response;
		
		$result = $this->form()->match('create', $request);
		
		if($result === true) {
			$result = $this->valid_data($request);
			if($result == true) {
				$response = $this->_create($request);
			} else {
				$response = $result;
			}
		} else {
			$response = $result;
		}
		
		return $response;
	}
	
	protected function _create(Array $user) {
		$this->hash_password($user);
		$this->insert($user);
		$user['id'] = $this->getLastInsertValue();
		$this->_details = $user;
		$this->session()->start($user['id']);
	}
	
	private function hash_password(Array &$user) {
		$user['salt'] = $this->generate_salt();
		$user['password'] = $this->_hash_password($user['password'], $user['salt']);
	}
	
	private function _hash_password($password, $salt) {
		
	}
	
	private function generate_salt() {
		
	}
	
	public function authenticate($session_token) {
		
	}
	
	public function getEmail() {
		return $this->_details['email'];
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
			$this->_data = new Data($this->data_config());
		}
		return $this->_data;
	}
	
	protected function validator() {
		if(!$this->_validator) {
			$config = ValidatorFactory::run($this->validator_config());
			$this->_validator = Validator($config);
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
		return Mlib\Form\User::forms();
	}
	
	protected function form_fields() {
		return Mlib\Form\User::fields();
	}
	
	protected function validator_config() {
		return Mlib\Validator\User::config();
	}
}