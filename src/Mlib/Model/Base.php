<?php
namespace Mlib\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;

class Base extends AbstractTableGateway {
	
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
	
	protected function is_unique($field_name, $value) {
		return !$this->select(array($field_name => $value))->current();
	}
	
	protected function duplicate_entry_error($field_name) {
		$error = array();
		$error['name'] = 'DuplicateEntry';
		$error['field'] = $field_name;
		return $error;
	}
}