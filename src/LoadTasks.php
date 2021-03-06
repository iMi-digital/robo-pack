<?php

namespace iMi\RoboPack;

use NordCode\RoboParameters\Reader\DotenvReader;
use ReflectionClass;

trait LoadTasks {
	// 3rd party tasks
	use \NordCode\RoboParameters\loadTasks;
	use \iMi\RoboRun\Task\Magerun\loadTasks;
	use \iMi\RoboRun\Task\Magerun\loadShortcuts;
	use \iMi\RoboRun\Task\MagerunTwo\loadTasks;
	use \iMi\RoboRun\Task\MagerunTwo\loadShortcuts;
	use \iMi\RoboRun\Task\Conrun\loadTasks;
	use \iMi\RoboRun\Task\Conrun\loadShortcuts;
	use \iMi\RoboWpcli\Task\Wpcli\loadTasks;
	use \iMi\RoboWpcli\Task\Wpcli\loadShortcuts;
	use \iMi\RoboTypo3\Task\Typo3\loadTasks;
	use \iMi\RoboTypo3\Task\Typo3\loadShortcuts;
	use \iMi\RoboLaravel\Task\Artisan\loadTasks;
	use \iMi\RoboLaravel\Task\Artisan\loadShortcuts;


	/**
	 * Heuristic: Project is in a folder named after the project's live domain
	 *
	 * Detect the RoboFiles dir.
	 *
	 * @return string
	 */
	protected function suggestProjectName()
	{
		$reflection = new ReflectionClass('RoboFile');
		$classFileName = $reflection->getFileName();
		return basename(dirname($classFileName));
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
	protected function suggestBaseUrl()
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
	protected function suggestMySqlConnection()
	{
		$myCnf = getenv('HOME') . DIRECTORY_SEPARATOR . '.my.cnf';
		if (file_exists($myCnf)) {
			$contents = @file_get_contents($myCnf);
		} else {
			$contents = '';
		}
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
		return preg_replace('/[^A-Za-z0-9]/', '_', $this->suggestProjectName());
	}

	/**
	 * Ask for base URL
	 *
	 * @return string
	 */
	protected function askBaseUrl()
	{
		return $this->askDefault('BASE URL', $this->suggestBaseUrl());
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
		$baseUrl = $this->askBaseUrl();

		return compact('dbName', 'dbHost', 'dbUser', 'dbPassword', 'baseUrl');
	}


    /**
     * @param string $path
     * @return array
     */
    protected function readDotenv($path = './.env'){
        $dotEnvReader = new DotenvReader();
        return $dotEnvReader->readFromFile($path);
    }
}