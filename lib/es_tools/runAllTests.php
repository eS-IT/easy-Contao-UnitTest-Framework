<?php

/**
 * Load Files
 */

// Load the framework
require_once(UNITTESTFOLDER . '/lib/simpletest/autorun.php');


// Load redean orm
require_once(UNITTESTFOLDER . '/lib/redbean/R.php');


if(INCLUDEBOOOTSTRAP){
    // Load the extended html reporter
    require_once(TOOLFOLDER . '/esHtmlReporter.php');

    // Set up simple test for using the extended html reporter
    SimpleTest::prefer(new esHtmlReporter());
}


/**
 * runAllTests-Class
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2013
 * @license     LGPL
 * @package
 * @filesource  runAllTests.php
 * @version     1.0.0
 * @since       28.05.13 - 17:22
 */

/**
 * TestSuite, loads the test classes
 */
class runAllTests extends TestSuite {


    public function __construct() {
        // Tools laden
        $this->loadTools();

        // Tests laden
        $this->loadTests();
    }

    private function loadTests(){
        // Unterordner von TESTFOLDER laden
        foreach(glob(TESTFOLDER . '/*', GLOB_ONLYDIR) as $folder){
            $arrTests = array();

            if(is_array($_GET) && array_key_exists('test', $_GET)){
                if($_GET['test'] == 'all' || $_GET['test'] == basename($folder)){
                    $arrTests = glob($folder . '/*_test*.php');
                } else {
                    $arrTests = array($folder . '/' . $_GET['test'] . '.php');
                }
            }

            if(count($arrTests)){
                foreach($arrTests as $file){
                    clearstatcache();
                    if(is_file($file)){
                        $this->addFile($file);
                    }
                }
            }
        }
    }


    /**
     * Laedt die Tools
     */
    private function loadTools(){
        $arrTools = glob(TOOLFOLDER . '/*');
        if(is_array($arrTools) && count($arrTools)){
            foreach($arrTools as $strTool){
                clearstatcache();

                if(is_file($strTool)){
                    include_once($strTool);
                }

            }
        }
    }
}