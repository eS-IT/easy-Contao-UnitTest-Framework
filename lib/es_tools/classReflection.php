<?php
/**
 * Description:
 * Leadt die Methode einer Klasse und aendert, falls noetig die Sichtbarkeit.
 *
 * @author      pfroch (e@sy Solutions IT) <info@easySolutionsIT.de>
 * @copyright   Copyright 2012 by e@sy Solutions IT
 * @version     1.0.0
 * @since       11.11.12 - 16:49
 * @package     classReflection.php
 */
class classReflection{


    private $strClass   = '';
    private $strMethod  = '';
    private $objClass   = null;
    private $objMethod  = null;


    /**
     * Erzeugt ein Obejekt der Klasse.
     * @param $strClass
     * @param $strMethod
     */
    public function __construct($strClass, $strMethod){
        $this->setMethod($strClass, $strMethod);
    }


    /**
     * Erstellt ein Objekt der zu testenden Klasse und setzt, falls noetig die Sichtbarkeit der Methode.
     * @param $strClass
     * @param $strMethod
     */
    public function setMethod($strClass, $strMethod){

        if(substr_count($strClass, '_test')){
            $arrClass = explode('_test', $strClass);
            $strClass = $arrClass[0];
        }

        $this->strClass     = $strClass;
        $this->strMethod    = $strMethod;

        // change accessible for private methode
        $reflection_class   = new ReflectionClass($strClass);
        $this->objMethod    = $reflection_class->getMethod($strMethod);
        $this->objMethod->setAccessible(true);

        // Config laden
#        $arrConfig          = include_once(CONFIGFOLDER . '/config.php');

        // make object form class
#        $this->objClass     = new $strClass($arrConfig);
        $this->objClass     = new $strClass();
    }


    /**
     * Ruft die zu testende Methode auf und gibt das Ergebis zurueck.
     * @return mixed
     */
    public function callMethod($arrParam = ''){
        if(!is_array($arrParam)){
            $arrParam = func_get_args();
        }

        return $this->objMethod->invokeArgs($this->objClass, $arrParam);
    }
}
