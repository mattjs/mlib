<?php

namespace Mlib\Http;

class Auth {
	public static function test() {
		$users = array('intro' => 'entrepreneur');
		
		$username = null;
		$password = null;
		 
		// mod_php
		if (isset($_SERVER['PHP_AUTH_USER'])) {
		    $username = $_SERVER['PHP_AUTH_USER'];
		    $password = $_SERVER['PHP_AUTH_PW'];
		 
		// most other servers
		} elseif (isset($_SERVER['HTTP_AUTHENTICATION'])) {
			if (strpos(strtolower($_SERVER['HTTP_AUTHENTICATION']),'basic')===0) {
		          list($username,$password) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
			}
		}
		 
		if (is_null($username)) {
		    header('WWW-Authenticate: Basic realm="Access Denied"');
		    header('HTTP/1.0 401 Unauthorized');
			return false;
		} else {
			return array_key_exists($username, $users) && $users[$username] == $password;
		}		
	}
}