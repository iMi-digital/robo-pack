<?php

namespace iMi\RoboProjectSetup;

trait LoadTasks {
	// 3rd party tasks
	use \NordCode\RoboParameters\loadTasks;
	use \iMi\RoboMagerun\Task\Magerun\loadTasks;
	use \iMi\RoboMagerun\Task\Magerun\loadShortcuts;

	protected function getBaseUrlSuggestion()
	{
		$hostname = strtolower($this->getShellResult('hostname -f'));

		if ($hostname == 'development.imi.local') {
			$hostname = getenv('USER') . '.dev.imi.lan';
		}
		if (strpos($hostname, '.') === false) {
			$hostname .= '.imi.local';
		}
		return 'http://' . basename(__DIR__) . '.' . $hostname . '/';
	}

}
