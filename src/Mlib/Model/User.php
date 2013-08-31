<?php
namespace Mlib\Model;

use Zend\Db\Adapter\Adapter;
use Mlib\Model\Session\SessionInterface;

class User extends Base {
	public $access_token_name = 'access_token';
		
	protected $table = 'users';
	
	protected $_details;
	protected $_logged_in = false;
	protected $_public_details = array('email', 'ts');
	
	protected $session;
	
	public function __construct(Adapter $adapter, SessionInterface $session) {
        $this->adapter = $adapter;
		$this->session = $session;
    }	
	
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
				$this->_details = (array) $user;
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
			$this->session->destroy();
			$this->session = null;
			setcookie($this->access_token_name, '', strtotime(time() - 3600), '/');
			$this->_logged_in = false;
		}
	}
	
	public function create(Array $request, $use_cookie=false) {
		$response = $this->valid_request('create', $request);
		
		if($response === true) {
			if($this->is_unique('email', $request['email'])) {
				$this->hash_password($request);
				$response = $this->_create($request, $use_cookie);
			} else {
				$response = $this->duplicate_entry_error('email');
				$response['error']['message'] = 'An account exists for '.$request['email'].'. Please sign in if this is your email address.';
			}
		}
		
		return $response;
	}
	
	protected function _create(Array $user, $use_cookie) {
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
	
	protected function start_session($use_cookie) {
		$this->session->start($this->_details['id']);
		$this->_logged_in = true;
		
		if($use_cookie) {
			$this->set_access_token_as_cookie();
		}
	}
	
	protected function set_access_token_as_cookie() {
		setcookie($this->access_token_name, $this->session->token(), strtotime($this->session->expires()), '/');
	}
	
	public function logged_in() {
		return $this->_logged_in;
	}
	
	protected function hash_password(Array &$user) {
		$user['salt'] = $this->generate_salt();
		$user['password'] = $this->_hash_password($user['password'], $user['salt']);
	}
	
	protected function _hash_password($password, $salt) {
		return hash('sha256', $password.$salt);
	}
	
	protected function generate_salt() {
		$size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
    	$iv = mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
		return substr(md5($iv), 0, 10);
	}
	
	public function valid_password($password) {
		if($this->_logged_in) {
			return $this->_details['password'] == $this->_hash_password($password, $this->_details['salt']);
		}
	}
	
	public function authenticate($access_token) {
		if($this->session->valid($access_token)) {
			$this->_logged_in = true;
			$this->_details = (array) $this->select(array('id' => $this->session->identifier()))->current();
		}
	}
	
	public function getEmail() {
		return $this->_details['email'];
	}
	
	public function flat() {
		return new \Mlib\Model\User\FlatUser($this->_logged_in, $this->details(), $this->session_details());
	}
	
	/**
	 * Return public user details
	 */
	public function details() {
		return array_intersect_key(count($this->_details)?$this->_details:array(), array_flip($this->_public_details));
	}
	
	protected function session_details() {
		if($this->_logged_in) {
			return array(
				$this->access_token_name => $this->session->token(),
				'expires' => $this->session->expires()
			);
		}
	}
	
	protected function form_config() {
		return new \Mlib\Form\UserFormConfig;
	}
	
	protected function validator_config() {
		return new \Mlib\Validator\UserValidatorConfig;
	}
	
	public function __call($method, $args) {
		die('Function '.$method.' does not exist');
	}
}