<?php
/**
 * Description:
 * Laedt die Datei system/modules/democlass.php und fuehrt die Tests durch.
 *
 * @author      pfroch (e@sy Solutions IT) <info@easySolutionsIT.de>
 * @copyright   Copyright 2012 by e@sy Solutions IT
 * @version     1.0.0
 * @since       11.11.12 - 13:50
 * @package     democlass_test.php
 */



/**
 * Fuehrt die Tests fuer die Klasse ebm_googleadressimporter durch.
 */
class esUnitTestCase extends UnitTestCase{


    /**
     * Testmethode
     * @var null
     */
    protected $tm = null;


    /**
     * Zwischenspeicher fuer die Vergleichsdaten.
     * @var array
     */
    protected $arrData = array();


    /**
     * Vergleichdaten
     * @var array
     */
    protected $arrComp = array();


    /**
     * DB-Helper-Klasse
     * @var dbhelper|null
     */
    protected $dbhelper = null;


    /**
     * Loder fuer die zu testenden Klassen.
     * @var null|testClassLoader
     */
    private $testClassLoader = null;


    /**
     * Name der zu testenden Klasse.
     * @var string
     */
    private $strClassname = '';


    /**
     * Name der Tabelle.
     * @var mixed|string
     */
    protected $strTablename = '';


    /**
     * Name der zu testenden Klasse.
     * @var string
     */
    protected $strObjectname = '';


    /**
     * Objekt das Contao fuer die Tests vorbereitet.
     * @var adjustContao|null
     */
    protected $adjustContao = null;


    /**
     * Namespace der zu testenden Klasse.
     * @var string
     */
    protected $namesapce = '';


    /**
     * Erstellt eine Instanz der Klasse.
     * @param string $label
     */
    public function __construct(){
        $this->strClassname     = get_class($this);
        $this->strTablename     = $this->getTableName();
        $this->initContao();
        $this->adjustContao     = new adjustContao();
        $this->adjustContao->run();
        $this->arrData          = $this->getComp($this->strClassname);
        $this->loadClass();
        $strDir                 = $this->getDir();
        $this->dbhelper         = new dbhelper($this->strClassname, $strDir . '/data');
        $this->processDb('setup', true);
        parent::__construct($this->strClassname);
        $this->resetRuntime();
    }


    public function __destruct(){
        $this->processDb('teardown', true);
    }


    protected function getTableName(){
        $arrFile    = explode('_test', $this->strClassname);
        $strDb      = str_replace('gov', 'tl_gov_', strtolower($arrFile[0]));
        return $strDb;
    }

    /**
     * Gibt den Pfad der Kindklasse zurueck.
     * @return string
     */
    protected function getDir() {
        $rc = new ReflectionClass($this->strClassname);
        return dirname($rc->getFileName());
    }


    /**
     * Laedt die zu testende Klasse.
     */
    private function loadClass(){
        $this->testClassLoader  = new testClassLoader();
        $this->strObjectname    = $this->testClassLoader->getObjectname($this->strClassname);
        $this->setNameSpace($this->strObjectname);
        $this->testClassLoader->load($this->strClassname, $this->namesapce);
    }


    /**
     * Setzt den Namesapce.
     */
    protected function setNameSpace($strClassname){
        $rc             = new \ReflectionClass($strClassname);
        $strNamespace   = $rc->getNamespaceName();

        if($strNamespace != ''){
            $this->namesapce = $rc->getNamespaceName() . '\\';
        }
    }


    /**
     * Verarbeitet die SQL-Dateien.
     * @param $label
     * @param string $strKind
     */
    protected function processDb($strKind = 'setup', $blnAll = false){
        $strKind = ($strKind != 'setup') ? 'teardown' : $strKind; // Nur setup oder teardown moeglich!

        // Globale setp- und teardown-Dateien verarbeiten
        if($blnAll){
            $this->runSqlFile($strKind . '.sql');
        }

        // Klassenspezifische Dateien verarbeiten
        $this->runSqlFile($strKind . '_' . $this->strClassname . '.sql');
    }


    /**
     * Verarbeitet ein Sql-File.
     * @param $strFilename
     */
    protected function runSqlFile($strFilename){
        $strDir = $this->getDir();

        clearstatcache();
        if(is_file($strDir . '/data/' . $strFilename)){
            $this->dbhelper->run($strFilename);
        }
    }


    /**
     * Fuehrt eine DB-Abfrage aus.
     * @param $strSql
     */
    protected function runSql($strSql){
        $this->dbhelper->runSql($strSql);
    }


    /**
     * Initialisiert Contao
     */
    private function initContao(){
        // Contao initialisieren
        if (!defined('TL_ROOT')){
            define('TL_MODE', 'BE');
            require(SYSTEMFOLDER . '/initialize.php');

            // Session-Variable setzen
            session_start();
            $_SESSION['BE_DATA'] = '';
        }
    }


    /**
     * Laedt die Testmethode
     * @param $strMethod
     */
    public function loadTestMethod($strMethod){
        $this->tm           = new classReflection($this->strClassname, $strMethod);
    }


    /**
     * Magic Methode, Testmethoden koennen nun mit $this->testMethode($arg1, $arg2, ...) aufgerufen werden!
     * @param $strMethod
     * @param $arguments
     */
    public function __call($strMethod, $arguments){
        $this->loadTestMethod($strMethod);
        return $this->tm->callMethod($arguments);
    }


    /**
     * Erstellt die Datei mit den Vergleichsdaten
     */
    protected function createCompromise(){
        for($i = 0; $i < count($this->arrData); $i++){
            $result         = $this->tm->callMethod($this->arrData[$i]);
            $arrContent[]   = serialize($result);
        }

        $this->dbhelper->createArrayFile($this->strClassname, $arrContent);
    }


    /**
     * Gibt die Vergleichsdaten zurueck.
     * @param $strClassname
     * @return bool|mixed
     */
    private function getComp(){
        $fn = $this->getDir() . '/data/array_' . $this->strClassname . '.php';

        clearstatcache();

        if(is_file($fn)){
            $tmpArray = include($fn);
            return $tmpArray;
        } else {
            return array();
        }
    }


    protected function resetRuntime(){
        set_time_limit(120);    // Ausfuehrungszeit auf 2 Minuten setzen.
    }
}
