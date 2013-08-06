<?php
namespace Mlib\Model\Session;

interface SessionInterface {
	public function start($identifier);
	
	public function valid($token);
	
	public function destroy();
	
	public function expires();
	
	public function identifier();
	
	public function token();
}