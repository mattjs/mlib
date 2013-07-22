<?php
namespace Mlib\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

use Mlib\Model\Base;

class Session extends Base {
	protected $table = 'sessions';
	protected $_identifier_name = 'user_id';
	protected $_identifier_type = 'integer';
	
	protected $token_lifetime = 259200; // 3 days in seconds
	protected $token_length = 32;
	
	protected $token;
	protected $expires;
	
	public function start($identifier) {
		$response = array();
		
		if($this->_valid_id($identifier)) {
			$session = array();
			$session[$this->_identifier_name] = $identifier;
			
			$session['token'] = $this->generate_token();
			
			// Generate unique token
			$session_exists = $this->select(array('token' => $session['token']))->current();
			while($session_exists) {
				$session['token'] = $this->generate_token();
				$session_exists = $this->select(array('token' => $session['token']))->current();
			}
			
			$session['expires'] = new \Zend\Db\Sql\Expression("DATE_ADD(NOW(),".$this->mysql_interval().")");;
			
			$inserted = $this->insert($session);
			
			if($inserted) {
				$response = $this->select(array('token' => $session['token']))->current();
			} else {
				// Error out
			}
		} else {
			// Error out
		}
		
		return $response;
	}
	
	protected function generate_token() {
		$size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
    	$iv = mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
		return substr(hash('sha256', $iv), 0, min(64, $this->token_length));
	}
	
	protected function mysql_interval() {
		return 'INTERVAL 3 DAY';
	}
	
	public function destroy($token) {
		
	}
	
	public function valid($access_token) {
		$valid = false;
		
		$session = $this->select(array('token' => $access_token))->current();
		if($session) {
			$valid = true;
			$this->token = $session['token'];
			$this->expires = $session['expires'];
		}
		return $valid;
	}
	
	public function token() {
		return $this->token;
	}
	
	public function expires() {
		return $this->expires;
	}
	
	/* PROTECTED METHODS */
	protected function _valid_id($id) {
		$valid = false;
		switch($this->_identifier_type) {
			case 'integer':
				$valid = is_int($id) || (string)(int)$id == $id;
				break;
		}
		return $valid;
	}
}