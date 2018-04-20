<?php

// INSERT: IROBO_BOILERPLATE //

/**
 *
 * This is the generic iMi Robo file. Please pay close attention to the doc blocks.
 * All iMi robo files shall follow the same rules, defined here by the doc blocks.
 *
 */
class RoboFile extends \Robo\Tasks {
	use \iMi\RoboPack\LoadTasks;

    /**
     * It is important to stop execution if there was an error
     */
    public function __construct() {
        $this->stopOnFail();
    }

	/**
	 * Initial project setup
     * Ask for database credentials
     * Generate environment file
     * Set the base URL accordingly
	 */
	public function setup() {
		$config = $this->askSetup();
//		$this->_writeEnvFile( $config );
        $this->say('not implemented: writing environment file');
        $this->update();
	}

	/**
	 * Update the project from VCS and everything else
	 */
	public function update() {
	    $this->say('not implemented: update');
//		$this->taskGitStack()->pull()->run();
		$this->updateDependencies();
        $this->updateDatabase();
		$this->updateAssets();
		$this->cacheFlush();
	}

	/**
	 * Update dependencies only
	 */
	public function updateDependencies() {
        $this->say('not implemented: update:dependencies');
//	    $this->taskComposerInstall()->run();
//		$this->_exec('yarn');
	}

    /**
     * Update assets
     */
    public function updateAssets()
    {
        $this->say('not implemented: update:assets');
//        $this->taskGulpRun()->run();
    }

    /**
     * Update Database only
     */
    public function updateDatabase() {
        $this->say('not implemented: update:database');
    }

	/**
	 * Backup database and replace the database with a clean one
     *
     * @param $fileName
	 */
	public function dbReplace($fileName = 'master.sql') {
	    $this->dbDump('backup_before_replace.sql', 'full');
        $this->say('not implemented: db:replace');
	}

    /**
     * Dump database, standard stripped: you shall excluded data like logs and cache
     *
     * @param $fileName standard is master.sql
     * @param $mode full or stripped
     */
	public function dbDump($fileName = 'master.sql', $mode = 'stripped') {
        $this->say('not implemented: db:dump');

    }

	/**
	 * Flush all caches we know about
	 */
	public function cacheFlush() {
        $this->say('not implemented: cache:flush');
	}

    /**
     * Shows all relevant logs
     */
    public function logTail()
    {
        $this->say('not implemented: log tail');
//        $this->_exec('tail -fn0 logs/errors.log');
    }

    /**
     * Setup the database with test data, create test users dev_$rolename, password: dev, dev123456, or dev123465#
     */
    public function setupDev()
    {
        $this->say('not implemented: setup:dev');

    }
}
