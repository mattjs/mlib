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
	protected $_logged_in = false;
	
	public function login(Array $request) {
		$result = $this->valid_request('login', $request);
		
		if($result == true) {
			$response = $this->_login($request);
		} else {
			$response = $result;
		}

		return $response;
	}
	
	public function logout() {
		if($this->logged_in()) {
			$this->session()->destroy($this->_access_token);
			$this->_access_token = null;
		}
	}
	
	public function create(Array $request) {
		$result = $this->valid_request('create', $request);
		
		if($result == true) {
			$response = $this->_create($request);
		} else {
			$response = $result;
		}
		
		return $response;
	}
	
	protected function valid_request($form_name, $request) {
		$result = $this->form()->match($form_name, $request);
		if($result === true) {
			$result = $this->valid_data($request);
		}
		return $result;
	}
	
	public function logged_in() {
		return $this->_logged_in;
	}
	
	public function set_access_token($token) {
		$this->_access_token = $token;
	}
	
	protected function _create(Array $user) {
		$this->hash_password($user);
		if($this->insert($user)) {
			$user['id'] = $this->getLastInsertValue();
			$this->_details = $user;
			$session = $this->session()->start($user['id']);
			$this->_access_token = $session['token'];
			return true;
		} else {
			// Error out
			return false;
		}
	}
	
	private function hash_password(Array &$user) {
		$user['salt'] = $this->generate_salt();
		$user['password'] = $this->_hash_password($user['password'], $user['salt']);
	}
	
	private function _hash_password($password, $salt) {
		return hash('sha256', $password.$salt);
	}
	
	private function generate_salt() {
		$size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
    	$salt = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
		return substr($salt, 0, 10);
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