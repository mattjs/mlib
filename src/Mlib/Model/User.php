<?php
namespace Mlib\Model;

class User extends Base implements User\BasicUserInterface {
	public $access_token_name = 'access_token';
		
	protected $table = 'users';
	protected $_details;
	protected $_data;
	protected $_validator;
	protected $_form;
	protected $_session;
	protected $_logged_in = false;
	
	public function login(Array $request, $use_cookie=false) {
		$response = $this->valid_request('login', $request);
		
		if($response === true) {
			$response = $this->_login($request, $use_cookie);
		} elseif($response['error']['type'] == 'InvalidData') { // Generalize error a bit
			foreach($response['error']['details'] as $field => $errors) {
				if($field == 'email') {
					$response = $this->invalid_email_error();
					break; // Just return this and not any password validator error
				} else if($field == 'password') {
					$response = $this->invalid_password_error();
				}
			}
		} // else bad form

		return $response;
	}
	
	protected function _login(Array $request, $use_cookie) {
		$user = $this->select(array('email' => $request['email']))->current();
		
		if($user) {
			if($user['password'] == $this->_hash_password($request['password'], $user['salt'])) {
				$this->_details = $user;
				$this->start_session($use_cookie);
				$response = true;
			} else {
				$response = $this->invalid_password_error();
			}
		} else {
			$response = $this->invalid_email_error();
		}
		
		return $response;
	}
	
	protected function invalid_password_error() {
		return array(
			'error' => array(
				'type' => 'InvalidPassword',
				'message' => 'The password provided was invalid'
			)
		);
	}
	
	protected function invalid_email_error() {
		return array(
			'error' => array(
				'type' => 'InvalidEmail',
				'message' => 'The email address provided was invalid'
			)
		);
	}
	
	public function logout() {
		if($this->logged_in()) {
			$this->session()->destroy($this->_access_token);
			$this->_session = null;
		}
	}
	
	public function create(Array $request, $use_cookie=false) {
		$response = $this->valid_request('create', $request);
		
		if($response == true) {
			if($this->is_unique('email', $request['email'])) {
				$response = $this->_create($request, $use_cookie);
			} else {
				$response = $this->duplicate_entry_error('email');
				$response['error']['message'] = 'An account exists for '.$request['email'].'. Please sign in if this is your email address.';
			}
		}
		
		return $response;
	}
	
	protected function _create(Array $user, $use_cookie) {
		$this->hash_password($user);
		if($this->insert($user)) {
			$user['id'] = $this->getLastInsertValue();
			$this->_details = $user;
			$this->start_session($use_cookie);
			return true;
		} else {
			// Error out
			return false;
		}
	}
	
	private function start_session($use_cookie) {
		$this->session()->start($this->_details['id']);
		$this->_logged_in = true;
		
		if($use_cookie) {
			$this->set_access_token_as_cookie();
		}
	}
	
	private function set_access_token_as_cookie() {
		setcookie($this->access_token_name, $this->session()->token(), strtotime($this->session()->expires()));
	}
	
	protected function valid_request($form_name, &$request) {
		$response = $this->form()->match($form_name, $request);
		if($response === true) {
			$response = $this->valid_data($request);
		}
		return $response;
	}
	
	public function logged_in() {
		return $this->_logged_in;
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
    	$iv = mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
		return substr(md5($iv), 0, 10);
	}
	
	public function authenticate($access_token) {
		if($this->session()->valid($access_token)) {
			$this->_logged_in = true;
		}
	}
	
	public function getEmail() {
		return $this->_details['email'];
	}
	
	protected function valid_data(Array $request) {
		$errors = array();
		
		foreach($request as $field => $value) {
			if(!$this->validator()->test($field, $value)) {
				$errors[$field] = $this->validator()->errors();
			}
		}
		
		if(count($errors)) {
			$response = array();
			$response['error'] = array();
			$response['error']['type'] = 'InvalidData';
			$response['error']['details'] = $errors;
			return $response;
		} else {
			return true;
		}
	}

	protected function session() {
		if(!$this->_session) {
			$this->_session = new Session($this->adapter);
		}
		return $this->_session;
	}
	
	protected function validator() {
		if(!$this->_validator) {
			$factory = new \Mlib\Validator\ValidatorFactory();
			$config = $factory->configure($this->validator_config());
			$this->_validator = new \Mlib\Validator\Validator($config);
		}
		return $this->_validator;
	}
	
	public function form() {
		if(!$this->_form) {
			$this->_form = new \Mlib\Form\Form(new \Mlib\Form\UserFormConfig);
		}
		return $this->_form;
	}
	
	protected function validator_config() {
		return \Mlib\Validator\UserValidatorConfig::config();
	}
	
	public function __call($method, $args) {
		die('Function '.$method.' does not exist');
	}
}