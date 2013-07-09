<?php
namespace Mlib\Data;

use Validator;

class Validate {
	public function build($config) {
		$validator = new Validator();
		for($i = 0; $i < count($config); $i++) {
			$validator->add($config[$i]['name'], $config[$i]['validators']);
		}
		return $validator;
	}
}