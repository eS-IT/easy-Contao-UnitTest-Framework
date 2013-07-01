<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pfroch
 * Date: 25.11.12
 * Time: 12:41
 */
class dbhelper{


    private $arrConfig  = array();
    private $strPath    = '';
    private $strClass   = '';
    private $strDb      = '';


    /**
     * Erstellt eine Instanz der Klasse.
     * @param $strClass
     */
    public function __construct($strClass, $strPath){
        $this->strClass = $strClass;
        $this->strPath  = $strPath;

        // Config-Array laden
        clearstatcache();
        if(is_file(CONFIGFOLDER . '/config.php')){
            $this->arrConfig = include(CONFIGFOLDER . '/config.php');

            // Einstellungen nur einfuegen, wenn der DB-Name von dem Namen der Produktiv-DB abweicht!
            if(is_array($this->arrConfig) && array_key_exists('dbname', $this->arrConfig) && $this->arrConfig['dbname'] != '' && $this->arrConfig['dbname'] != $GLOBALS['TL_CONFIG']['dbDatabase']){
                $this->strDb                        = $this->arrConfig['dbname'];
                R::setup('mysql:host=' . $this->arrConfig['dbhost'] . ';dbname=' . $this->strDb, $this->arrConfig['dbuser'], $this->arrConfig['dbpass']);
                $this->setupContao();
            }
        }
    }


    /**
     * Setzt die DB-Zugangsdaten fuer Contao.
     */
    public function setupContao(){
        $GLOBALS['TL_CONFIG']['dbDatabase'] = $this->strDb;
        $GLOBALS['TL_CONFIG']['dbHost']     = $this->arrConfig['dbhost'];
        $GLOBALS['TL_CONFIG']['dbUser']     = $this->arrConfig['dbuser'];
        $GLOBALS['TL_CONFIG']['dbPass']     = $this->arrConfig['dbpass'];
    }


    /**
     * Laedt eine SQL-Datei und fuehrt den Query aus.
     * @param $strName
     * @return bool
     */
    public function run($strName){
        // Query laden
        $strFile = $this->strPath . '/' . $strName;

        // Query einfügen
        clearstatcache();
        if(substr_count($strFile, '.sql') && is_file($strFile)){
            $strSql = file_get_contents($strFile);
            R::exec($strSql);
            return true;
        }

        return false;

    }


    /**
     * Fuehrt eine Datenbankabfrage aus.
     * @param $strSql
     */
    public function runSql($strSql){
        R::exec($strSql);
    }


    /**
     * Laedt eine Tabellenzeile.
     * Beispiel:
     * $strQuery    = 'lastname = ? AND firstname = ?';
     * $arrData     = array('Froch', 'Patrick');
     *
     * @param $strQuery
     * @param $arrData
     * @return array
     */
    public function getRow($strQuery, $arrData){
        return R::getRow($strQuery, $arrData);
    }


    /**
     * Laedt mehrere Tabellenzeilen.
     * Beispiel:
     * $strQuery = 'SELECT * FROM tl_member';
     *
     * @param $strQuery
     * @param $arrData
     * @return array
     */
    public function getAll($strQuery){
        return R::getAll($strQuery);
    }


    /**
     * Laedt eine Tabellenzeile anhand der Id und gibt ein Bean zurueck.
     * Beispiel:
     * $strTable    = 'tl_member'
     * $intId       = 1;
     *
     * @param $strTable
     * @param $intId
     * @return RedBean_OODBBean
     */
    public function loadRow($strTable, $intId){
        return R::load($strTable, $intId);
    }


    /**
     * Gibt true zurueck, wenn ein Datensatz mit dem uebergebenen Wert in dem uebergebenen Feld existiert.
     * @param $strTable
     * @param $strField
     * @param $varValue
     * @return bool
     */
    public function recordExists($strTable, $strField, $varValue){
        $arrBeans = R::find($strTable, $strField . ' = ?', $varValue);

        if(is_array($arrBeans) && count($arrBeans)){
            return true;
        } else {
            return false;
        }
    }


    /**
     * Erstellt aus den DB-Eintraegen ein Array mit vergleichsdaten.
     * @param $strClassname
     * @param $strName
     * @param $arrContent
     * @return bool
     */
    public function dbToFile($strClassname, $strName, $arrContent){
        $fn = $this->strPath . '/array_' . $strClassname . '.php';

        clearstatcache();

        if(is_file($fn)){
            $strContent = file_get_contents($fn);
            $strContent = str_replace("\nreturn \$arrData;", '', $strContent) . "\n";
        } else {
            $strContent = "<?php\n";
        }

        $strNewArray = "\$arrData['$strName'] = unserialize('" . serialize($arrContent) . "');\n";

        if(!substr_count($strContent, $strNewArray)){
            $strContent .= $strNewArray;
        }

        file_put_contents($fn, $strContent . "\nreturn \$arrData;");
    }
}
