<?php

namespace iMi\RoboPack;

trait LoadTasks {
	// 3rd party tasks
	use \NordCode\RoboParameters\loadTasks;
	use \iMi\RoboRun\Task\Magerun\loadTasks;
	use \iMi\RoboRun\Task\Magerun\loadShortcuts;
	use \iMi\RoboRun\Task\Conrun\loadTasks;
	use \iMi\RoboRun\Task\Conrun\loadShortcuts;
	use \iMi\RoboWpcli\Task\Wpcli\loadTasks;
	use \iMi\RoboWpcli\Task\Wpcli\loadShortcuts;

	protected function askSetup()
	{
		$suggester = new Suggester();
		$this->say('Welcome to your friendly project setup!');
		$this->say('Project configuration - if possible you should use the defaults.');
		$this->say('Tip: You can create .my.cnf in your home directory if you do not want to enter DB settings again and again.');
		$this->say('Press enter to accept defaults.');

		$dbName = $this->askDefault('DB Name', $suggester->suggestDbName());
		$dbConfig = $suggester->suggestMySqlConnection();
		$dbHost = $this->askDefault('DB Host', 'localhost');
		$dbUser = $this->askDefault('DB User', $dbConfig['user']);
		$dbPassword = $this->askDefault('DB Password',  $dbConfig['password']);
		$baseUrl = $this->askDefault('BASE URL', $suggester->suggestBaseUrl());

		return compact('dbName', 'dbHost', 'dbUser', 'dbPassword', 'baseUrl');
	}
}