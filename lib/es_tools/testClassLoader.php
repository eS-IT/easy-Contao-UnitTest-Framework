<?php
/**
 * Description:
 * Laedt die Klassen, die getestet werden sollen.
 *
 * @author      pfroch (e@sy Solutions IT) <info@easySolutionsIT.de>
 * @copyright   Copyright 2012 by e@sy Solutions IT
 * @version     1.0.0
 * @since       11.11.12 - 16:39
 * @package     testClassLoader.php
 */


class testClassLoader{


    /**
     * Array mit den Klassen aus dem Contao-Autoload-System.
     * @var array
     */
    private $arrClasses = array();


    /**
     * Laedt die Klassen, die getestet werden sollen.
     * @param $strFile
     */
    public function load($strTestClass, $strNamespace){
        $strClass = $strNamespace . $this->getObjectname($strTestClass);
        $this->loadContaoAutoload();
        clearstatcache();

        if(is_array($this->arrClasses) && array_key_exists($strClass, $this->arrClasses)){
            // Klasse aus dem Contao-Autoloader suchen
            if(is_file($this->arrClasses[$strClass])){
                require_once($this->arrClasses[$strClass]);
            } else {
                throw new ErrorException("ContaoAutoload: Testclass [" . $strClass . "] not found in " . $this->arrClasses[$strClass]);
            }
        } else {
            throw new ErrorException("Testloader: Testclass [" . $strClass . "] not found in " . $this->arrClasses[$strClass]);
        }
    }


    /**
     * Gibt den Namen der zu testenden Klasse zurueck.
     * @param $strTestClass
     */
    public function getObjectname($strTestClass){
        $arrClass = explode('_test', $strTestClass);
        return $arrClass[0];
    }


    /**
     * Laedt die Contao-Autoload-Dateien.
     */
    private function loadContaoAutoload(){
        $arrFolder = glob(CLASSFOLDER . '/*', GLOB_ONLYDIR);

        if(is_array($arrFolder) && count($arrFolder)){
            foreach($arrFolder as $strFolder){

                clearstatcache();

                if(is_file($strFolder . '/config/autoload.php')){
                    $this->parseFile($strFolder . '/config/autoload.php');
                }
            }
        }
    }


    /**
     * Verarbeitet die Contao-Autoload-Dateien.
     * @param $strFile
     */
    private function parseFile($strFile){
        $arrContent     = file($strFile);
        $bolParseLine   = false;

        if(is_array($arrContent) && count($arrContent)){
            foreach($arrContent as $strRow){
                if(substr_count($strRow, 'addClasses')){
                    $bolParseLine = true;
                }

                if(substr_count($strRow, ');')){
                    $bolParseLine = false;
                }

                if($bolParseLine && substr_count($strRow, '=>')){
                    $arrRow                     = explode('=>', $strRow);
                    $strClass                   = trim(str_replace("'", '', str_replace('"', '', $arrRow[0])));
                    $strPath                    = trim(str_replace("'", '', str_replace('"', '', $arrRow[1])));
                    $strPath                    = trim(str_replace(",", '', str_replace('"', '', $strPath)));
                    $this->arrClasses[$strClass]= TL_ROOTFOLDER . '/' . $strPath;
                }
            }

        }

    }
}
