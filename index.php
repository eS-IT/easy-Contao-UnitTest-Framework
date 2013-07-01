<?php

// Define constants
require_once('config/config.php');

if (!defined('TESTSUITENAME')){
    define('TESTSUITENAME', $config['testsuitename']);
}

require_once('config/sys_config.php');
require_once(TOOLFOLDER . '/urlRouter.php');

$router = new urlRouter();