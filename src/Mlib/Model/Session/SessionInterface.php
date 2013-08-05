<?php
namespace Mlib\Model\Session;

interface SessionInterface {
	public function start();
	
	public function destroy();
	
	public function expires();
	
	public function valid();
	
	public function identifier();
	
	public function token();
}