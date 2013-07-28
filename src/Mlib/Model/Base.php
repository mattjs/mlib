<?php
namespace Mlib\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;

class Base extends AbstractTableGateway {
	protected $_data;
	protected $_validator;
	protected $_form;
	
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
	
	protected function valid_request($form_name, &$request) {
		$response = $this->form()->match($form_name, $request);
		if($response === true) {
			$response = $this->valid_data($request);
		}
		return $response;
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
	
		
	public function form() {
		if(!$this->_form) {
			$this->_form = new \Mlib\Form\Form($this->form_config());
		}
		return $this->_form;
	}
	
	
	protected function validator() {
		if(!$this->_validator) {
			$this->_validator = new \Mlib\Validator\Validator($this->validator_config());
		}
		return $this->_validator;
	}	
	
	protected function form_config() {
		return new \Mlib\Form\EmptyFormConfig;
	}

	protected function validator_config() {
		return new \Mlib\Validator\EmptyValidatorConfig;
	}
	
	protected function is_unique($field_name, $value) {
		return !$this->select(array($field_name => $value))->current();
	}
	
	protected function duplicate_entry_error($field_name) {
		return array(
			'error' => array (
				'type' => 'DuplicateEntry',
				'field' => $field_name
			)
		);
	}
}