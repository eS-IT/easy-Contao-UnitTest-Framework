<?php
/**
 * esTemplate-Class
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2013
 * @license     LGPL
 * @package
 * @filesource  esTemplate.php
 * @version     1.0.0
 * @since       28.05.13 - 18:22
 */

class esTemplate {


    /**
     * Pfad zum Template.
     * @var string
     */
    private $strTemplate = '';


    /**
     * Erstellt eine Instanz der Klasse.
     * @param string $strTemplate
     */
    public function __construct($strTemplate = '', $testsutiename = ''){
        $this->strTemplate      = $strTemplate;
        $this->testsutiename    = ($testsutiename != '') ? $testsutiename : TESTSUITENAME;
    }


    /**
     * Setzt das Template fuer die Ausgabe.
     * @param $strTemplate
     */
    public function loadTemplate($strTemplate){
        $this->strTemplate = $strTemplate;
    }


    /**
     * Setzt einen Wert fuer die Ausgabe im Template.
     * @param $strKey
     * @param $varValue
     */
    public function __set($strKey, $varValue){
        $this->$strKey = $varValue;
    }


    /**
     * Gibt einen gesetzten Wert zurueck.
     * @param $strKey
     * @return null
     */
    public function __get($strKey){
        if(isset($this->$strKey)){
            return $this->$strKey;
        } else {
            return null;
        }
    }


    /**
     * Gibt das Template aus.
     */
    public function output(){
        echo $this->parse();
    }


    /**
     * Gibt den Inhalt des verarbeiteten Templates zurueck.
     * @return bool|string
     */
    public function parse(){
        clearstatcache();
        if($this->strTemplate != '' && is_file($this->strTemplate)){
            $this->setCssFiles();
            $this->setJsFiles();
            ob_start();
            include($this->strTemplate);
            $strBuffer = ob_get_contents();
            ob_end_clean();
            return $strBuffer;
        } else {
            return false;
        }
    }


    /**
     * Setzt die JavaScript Files fuer das Template.
     */
    private function setCssFiles(){
        $this->arrCssFiles = $this->getFiles('css');
    }


    /**
     * Setzt die JavaScript Files fuer das Template.
     */
    private function setJsFiles(){
        $this->arrJsFiles = $this->getFiles('js');
    }


    /**
     * Load the Css-Files.
     * @param $strTyp
     * @return string
     */
    private function getFiles($strTyp){
        // include Bootstrap
        if(INCLUDEBOOOTSTRAP){
            foreach(glob(HTMLFOLDER . "/bootstrap/$strTyp/*.$strTyp") as $file){
                $arrFiles[] = str_replace(TL_ROOTFOLDER, '', $file);
            }
        }

        // include user files
        foreach(glob(HTMLFOLDER . "/$strTyp/*.$strTyp") as $file){
            $arrFiles[] = str_replace(TL_ROOTFOLDER, '', $file);
        }

        return $arrFiles;
    }
}