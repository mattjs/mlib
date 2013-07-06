<?php
namespace Mlib\Model;

use Mlib\Model\Base;

class Session extends Base {
	public $token_name;
	
	public function test() {
		return 'hello world';
	}
	
	public function start(Array $session) {
		
	}
	
	public function destroy() {
		
	}
	
	public function valid() {
		
	}
}