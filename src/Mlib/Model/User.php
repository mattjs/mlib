<?php
namespace Mlib\Model;

use Mlib\Validator\Validator;
use Mlib\Form\Form;

class User extends Base {
	public $access_token_name = 'access_token';
		
	protected $table = 'user';
	protected $_details;
	protected $_data;
	protected $_session;
	protected $_validator;
	protected $_form;
	protected $_access_token;
	
	public function login() {
		
	}
	
	public function logout() {
		
	}
	
	public function create(Array $request) {
		$response = null;
		
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
	
	public function set_access_token($token) {
		$this->_access_token = $token;
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
	
	public function authenticate() {
		
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
	
	protected function validator() {
		if(!$this->_validator) {
			$config = ValidatorFactory::run($this->validator_config());
			$this->_validator = Validator($config);
		}
		return $this->_validator;
	}
	
	public function form() {
		if(!$this->_form) {
			$this->_form = new Form(new \Mlib\Form\UserFormConfig);
		}
		return $this->_form;
	}
	
	protected function validator_config() {
		return \Mlib\Validator\UserValidatorConfig::config();
	}
}