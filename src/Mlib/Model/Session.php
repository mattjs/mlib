<?php
namespace Mlib\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

use Mlib\Model\Base;

class Session extends AbstractTableGateway implements Session\SessionInterface {
	protected $table = 'sessions';
	protected $_identifier_name = 'user_id';
	protected $_identifier_type = 'integer';
	
	protected $token_lifetime = 259200; // 3 days in seconds
	protected $token_length = 32;
	
	protected $token;
	protected $expires;
	protected $identifier;
	
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
	
	public function start($identifier) {
		$response = false;
		
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
			
			$session['expires'] = new \Zend\Db\Sql\Expression("DATE_ADD(NOW(),".$this->mysql_interval().")");
			
			$inserted = $this->insert($session);
			
			if($inserted) {
				$response = true;				
				$session = $this->select(array('token' => $session['token']))->current();
				$this->token = $session['token'];
				$this->expires = $session['expires'];
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
	
	public function destroy() {
		$result = null;
		if($this->token) {
			$result = $this->delete(array('token' => $this->token));
			$this->token = null;
			$this->expires = null;
			$this->identifier = null;
		}
		return $result;
	}
	
	public function valid($token) {
		$valid = false;
		
		$session = $this->select(array('token' => $token))->current();
		if($session) {
			$valid = true;
			$this->token = $session['token'];
			$this->expires = $session['expires'];
			$this->identifier = $session[$this->_identifier_name];
		}
		return $valid;
	}
	
	public function token() {
		return $this->token;
	}
	
	public function expires() {
		return $this->expires;
	}
	
	public function identifier() {
		return $this->identifier;
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