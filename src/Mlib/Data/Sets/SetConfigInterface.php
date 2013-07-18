<?php
namespace Mlib\Data\Set;

interface SetConfigInterface {
	/**
	 * Example
	 * array (
	 *     array(
	 *         'name' => 'user',
	 *         'config' => '\Mlib\Data\UserDataConfig'
	 *     )
	 * )
	 */
	public function details();
}
