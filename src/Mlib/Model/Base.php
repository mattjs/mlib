<?php
namespace Mlib\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;

class Base extends AbstractTableGateway {
	
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
	
	protected function __get_error() {
		$trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
	}
}