<?php
namespace Mlib\Model\User;

class LoggedOutUser implements BasicUserInterface {
	public function logged_in() {
		return false;
	}
}