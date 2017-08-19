Robo Utils from and for iMi
===========================

Included Tasks
--------------

The robo-pack includes

- Magerun Wrappers
- MagerunTwo Wrappers
- Conrun Wrappers
- WPCli wrappers
- Typo3(console) wrappers


Utility Function: askSetup
--------------------------

    $this->askSetup()
    
    
Asks basic setup questions that apply to most if not all of our projects.
This is the database configuration and the base URL. Smart guesses are made based on our heuristics.

- The live URL is supposed to be the directory name
- The database name shall be the directory name, special characters replaced by _
- The base URL is live URL and the host name appended

Returns an array with the keys

    dbName
    dbHost
    dbUser
    dbPassword
    baseUrl
   
If you use place holders like `#dbName#`, `#dbHost#` and so on in the config file how can use the following to fill a config file:


    $settings = $this->askSetup();
    $this->taskFilesystemStack()->copy('app/etc/env.template.php', 'app/etc/env.php')->run();
    foreach ( $settings as $key => $value ) {
        if (strpos($key,'db') === 0) {
            $this->taskReplaceInFile( 'app/etc/env.php' )->from( '#' . $key . '#' )->to( $value )->run();
        }
    }

In the future, such a code should also be included in roboPack
