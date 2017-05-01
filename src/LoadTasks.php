<?php

namespace iMi\RoboPack;

trait LoadTasks {
	// 3rd party tasks
	use \NordCode\RoboParameters\loadTasks;
	use \iMi\RoboRun\Task\Magerun\loadTasks;
	use \iMi\RoboRun\Task\Magerun\loadShortcuts;
	use \iMi\RoboRun\Task\Conrun\loadTasks;
	use \iMi\RoboRun\Task\Conrun\loadShortcuts;
	use \iMi\RoboWpCli\Task\Wpcli\loadTasks;
	use \iMi\RoboWpCli\Task\Wpcli\loadShortcuts;

	/**
	 * Heuristic to suggest a base URL
	 *
	 * - for development.imi.local prepend user name
	 * - otherwise use FQDN
	 * - if the FQDN is not detected, add .imi.local
	 *
	 * @return string
	 */
	protected function suggestBaseUrl()
	{
		$hostname = strtolower(gethostbyaddr('127.0.1.1'));

		if ($hostname == 'development.imi.local') {
			$hostname = getenv('USER') .'.dev.imi.lan';
		}
		if (strpos($hostname, '.') === false) {
			$hostname .= '.imi.local';
		}
		return 'http://' . basename(__DIR__) . '.' . $hostname . '/';
	}

	/**
	 * Heuristic to suggest mysql connection sugggestion
	 *
	 * user and password are tried to read from my.cnf
	 * otherwise USER = current user and password = the same as user is suggested
	 *
	 * @return array
	 */
	protected function suggestMySqlConnection()
	{
		$contents = file_get_contents(getenv('HOME') . DIRECTORY_SEPARATOR . '.my.cnf');
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
	protected function suggestDbName()
	{
		return preg_replace('/[^A-Za-z0-9]/', '_', basename(__DIR__));
	}

	protected function askSetup()
	{
		$this->say('Welcome to your friendly project setup!');
		$this->say('Project configuration - if possible you should use the defaults.');
		$this->say('Tip: You can create .my.cnf in your home directory if you do not want to enter DB settings again and again.');
		$this->say('Press enter to accept defaults.');

		$dbName = $this->askDefault('DB Name', $this->suggestDbName());
		$dbConfig = $this->suggestMySqlConnection();
		$dbHost = $this->askDefault('DB Host', 'localhost');
		$dbUser = $this->askDefault('DB User', $dbConfig['user']);
		$dbPassword = $this->askDefault('DB Password',  $dbConfig['password']);
		$baseUrl = $this->askDefault('BASE URL', $this->suggestBaseUrl());

		return compact('dbName', 'dbHost', 'dbUser', 'dbPassword', 'baseUrl');
	}

}