<?php

namespace iMi\RoboPack;

use Robo\Common\TaskIO;

/**
 * Suggest project name and other variables according to iMi dev conventions
 *
 * @package iMi\RoboPack
 */
class Suggester {
	use TaskIO;

	public function suggestProjectName()
	{
		$dir = getcwd();
		return basename($dir);
	}

	/**
	 * Heuristic to suggest a base URL
	 *
	 * - for development.imi.local prepend user name
	 * - otherwise use FQDN
	 * - if the FQDN is not detected, add .imi.local
	 *
	 * @return string
	 */
	public function suggestBaseUrl()
	{
		$hostname = strtolower(gethostbyaddr('127.0.1.1'));

		if ($hostname == 'development.imi.local') {
			$hostname = getenv('USER') .'.dev.imi.lan';
		}
		if (strpos($hostname, '.') === false) {
			$hostname .= '.imi.local';
		}
		return 'http://' . $this->suggestProjectName() . '.' . $hostname . '/';
	}

	/**
	 * Heuristic to suggest mysql connection sugggestion
	 *
	 * user and password are tried to read from my.cnf
	 * otherwise USER = current user and password = the same as user is suggested
	 *
	 * @return array
	 */
	public function suggestMySqlConnection()
	{
		$myCnf = getenv('HOME') . DIRECTORY_SEPARATOR . '.my.cnf';
		$contents = file_exists($myCnf) ? file_get_contents($myCnf) : '';
		if (preg_match('/user=(.*)/', $contents, $matches)) {
			$user = $matches[1];
		} else {
			$user = getenv('USER');
		}
		if (preg_match('/password=(.*)/', $contents, $matches)) {
			$password = $matches[1];
		} else {
			$password = getenv('USER');
		}
		return ['user' => $user, 'password' => $password];
	}


	/**
	 * Heuristic to generate DB Name
	 *
	 * Use the current folder name, replace special chars by _
	 *
	 * @return mixed
	 */
	public function suggestDbName()
	{
		return preg_replace('/[^A-Za-z0-9]/', '_', $this->suggestProjectName());
	}

}
