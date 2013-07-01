<?php


include_once(TOOLFOLDER . '/esTemplate.php');


/**
 * urlRouter-Class
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2013
 * @license     LGPL
 * @package
 * @filesource  urlRouter.php
 * @version     1.0.0
 * @since       01.06.13 - 16:19
 */

class urlRouter {

    private $strContent = '';


    public function __construct(){

        if(is_array($_GET) && array_key_exists('site', $_GET)){
            switch($_GET['site']){
                case 'runtest':
                    $this->runTest();
                    break;

                case 'main':
                    $this->main();
                    break;

                default:
                    $this->home();
                    break;
            }
        } else {
            $this->home();
        }

        echo $this->output();
    }


    private function home(){
        // Display the login form - later
        $this->main();
    }


    private function main(){
        $templateM                  = new esTemplate(HTMLFOLDER . '/templates/main_text.php');
        $templateM->button          = static::makeTestForm();
        $templateM->safeModeWarning = $this->makeSafeModeWarning();
        $this->strContent          .= $templateM->parse();
    }


    public static function makeTestForm(){
        $templateL              = new esTemplate(HTMLFOLDER . '/templates/testform.php');
        $templateL->link        = UNITTESTDIR . '/index.php';
        $templateL->view        = (is_array($_GET) && array_key_exists('view', $_GET)) ? $_GET['view'] : 'normal';
        $templateL->selectTest  = static::getTestSelect();
        $templateL->text        = 'Start';
        return $templateL->parse();
    }


    private static function getTestSelect(){
        $strConent = "<select name='test' class='chzn-select'>\n<option value='all'>ALLE</option>\n";

        foreach(glob(TESTFOLDER . '/*', GLOB_ONLYDIR) as $folder){
            $strTmpConent   = '';
            $strConent     .= "<optgroup value='$folder' label='" . str_replace(TESTFOLDER . '/', '', $folder) . "'>\n";
            $i              = 0;

            foreach(glob($folder . '/*_test*.php') as $file){
                $strTest        = str_replace($folder . '/', '', str_replace('.php', '', $file));
                $strSelected    = (is_array($_GET) && array_key_exists('test', $_GET) && $_GET['test'] == $strTest) ? 'selected' : '';
                $strTmpConent  .= "<option value='" . $strTest . "'"  . $strSelected . ">" . $strTest . "</option>\n";
                $i++;
            }

            if($i > 1){
                // Option fuer die Testgruppe
                $strTest        = str_replace(TESTFOLDER . '/', '', $folder);
                $strSelected    = (is_array($_GET) && array_key_exists('test', $_GET) && $_GET['test'] == $strTest) ? 'selected' : '';
                $strConent     .= "<option value='" . $strTest . "'"  . $strSelected . ">ALLE " . strtoupper($strTest) . "-TESTS</option>\n";
            }

            $strConent .= $strTmpConent . '</optgroup>';
        }

        return $strConent . '</select>';
    }


    public static function makeSafeModeWarning(){
        $strConent = '';

        if(strtolower(ini_get('safe_mode')) == 'on'){
            $templateS = new esTemplate(HTMLFOLDER . '/templates/safemodewarning.php');
            $strConent.= $templateS->parse();
        }

        return $strConent;
    }


    private function runTest(){
        if($this->checkTests()){
            require_once(TOOLFOLDER . '/runAllTests.php');
        } else {
            $this->noTests();
        }
    }


    private function noTests(){
        $strSafeMode = $this->makeSafeModeWarning();
        $this->strContent .= "<h1><a href=\"/files/unittests/index.php\">" . TESTSUITENAME . "</a></h1>\n$strSafeMode\n<h2>Testergebnisse</h2>\n<div class=\"text-info\">Keine Tests gefunden!</div>";
    }


    /**
     * Prüft, ob Test vorhanden sind.
     * @return bool
     */
    private function checkTests(){
        foreach(glob(TESTFOLDER . '/*', GLOB_ONLYDIR) as $folder){
            if(is_array($_GET) && array_key_exists('test', $_GET) && ($_GET['test'] != 'all' && $_GET['test'] != basename($folder))){
                $arrTests = array($folder . '/' . $_GET['test'] . '.php');
            } else {
                $arrTests = glob($folder . '/*_test*.php');
            }

            if(is_array($arrTests) && count($arrTests)){

                foreach($arrTests as $file){
                    clearstatcache();

                    if(is_file($file)){
                        return true;
                    }
                }
            }
        }

        return false;
    }


    private function output(){
        if($this->strContent != ''){
            $strContent         = '';
            $templateH          = new esTemplate(HTMLFOLDER . '/templates/main_header.php');
            $strContent        .= $templateH->parse();

            $templateC          = new esTemplate(HTMLFOLDER . '/templates/main_content.php');
            $templateC->content = $this->strContent;
            $strContent        .= $templateC->parse();

            $templateF          = new esTemplate(HTMLFOLDER . '/templates/main_footer.php');
            $strContent        .= $templateF->parse();
            return $strContent;
        } else {
            return '';
        }
    }
}