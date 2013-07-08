<?php
namespace Mlib\Model;

use Mlib\Model\Base;
use Mlib\Model\Session;

use Mlib\Data;

class User extends Base {
	private $lazyload = array('session');
	
	protected $table = 'user';
	protected $_data = null;
	
	public function login() {
		
	}
	
	public function logout() {
		
	}
	
	public function create($request) {
		$response = array();
		
		$valid = $this->_valid_create($request);
		
		if($valid === true) {
			
		} else {
			$response = $valid;
		}
		
		return $response;
	}
	
	public function authenticate($session_token) {
		
	}
	
	protected function _valid_create($request) {
		
	}
	
	public function __get($name) {
		if(in_array($name, $this->lazyload)){
			return $this->{'_init_'.$name}();
		} else {
			return parent::__get($name);
		}
	}
	
	public function __set($name, $value) {
		if(in_array($name, $this->lazyload)){
			$this->$name = $value;
		} else {
			parent::__set($name, $value);
		}
	}
	
	protected function _init_session() {
		$this->session = new Session($this->adapter);
		return $this->session;
	}
	
	protected function data() {
		if(!$this->_data) {
			$this->_data = Data\Factory::init($this->get_data_config());
		}
		return $this->_data;
	}
	
	protected function valid_field($name, $value) {
		return Data\Validate::check($this->data(), $name, $value);
	}
	
	public function basic_data_config() {
		return array(
			array(
				'name' => 'id',
				'type' => 'integer',
				'options' => array(
					'autoincrement'
				)
			),
			array(
				'name' => 'ts',
				'type' => 'timestamp',
				'options' => array(
					'default' => 'current_timestamp'
				)
			),
		);
	}
	
	public function get_data_config() {
		return array(
			array(
				'name' => 'email',
				'type' => 'email'
			),
			array(
				'name' => 'password',
				'type' => 'string',
				'validators' => array(
					array(
						'name' => 'string_length',
						'options' => array(
							'min' => 6,
							'max' => 30
						)
					),
					array(
						'name' => 'regex',
						'value' => "/[\w\d\!\@\#\$\%\^\&\*\(\)\_\-\+\?]+/"
					)
				)
			),
		);
	}
	
	public function get_form_config() {
		return array(
			array(
				'name' => 'login',
				'fields' => array(
					'email',
					'password'
				)
			),
			array(
				'name' => 'create',
				'fields' => array(
					'email',
					'password',
					array(
						'name' => 'passwordVerify',
						'verifies' => 'password'
					)
				)
			)
		);
	}
}