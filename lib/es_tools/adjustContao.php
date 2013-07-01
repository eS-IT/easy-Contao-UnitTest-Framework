<?php
/**
 * adjustContao-Class
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2013
 * @license     LGPL
 * @package
 * @filesource  adjustContao.php
 * @version     1.0.0
 * @since       30.05.13 - 18:43
 */

class adjustContao {


    /**
     * Liste mit Kriterien fuer
     * Contao-spezifische Fehler.
     * @var array|mixed
     */
    private $arrErrors = array();


    /**
     * Erzeugt eine Instanz der Klasse.
     */
    public function __construct(){
        $this->arrErrors = include(CONFIGFOLDER . '/err_config.php');
    }


    /**
     * Ruft alle Methode auf, um Contao fuer die Tests vorzubereiten.
     */
    public function run(){
        $arrMethods = get_class_methods($this);

        foreach($arrMethods as $strMethod){
            if($strMethod != '__construct' && $strMethod != 'run' && substr_count($strMethod, 'setup_')){
                $this->$strMethod();
            }
        }
    }


    /**
     * Laedt die default-Language-Files, dies wird in
     * den dcas benoetigt.
     */
    private function setup_loadMscLanguageFiles(){
        System::loadLanguageFile('default');
    }


    /**
     * Setzt $_SERVER['ORIG_SCRIPT_NAME'], dies wird in
     * /system/modules/core/library/Contao/Environment.php line 106
     * benoetigt.
     */
    private function setup_setORIG_SCRIPT_NAME(){
        $_SERVER['ORIG_SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME'];
    }


    /**
     * Prueft, ob es sich bei einem Fehler um einen Contao-spezifischen Fehler handelt.
     * Es können z.B. Undefined offset in Database/Statement.php ignoriert werden.
     * @param $strMsg
     * @return bool
     */
    public function testContaoError($strMsg){
        foreach($this->arrErrors as $arrError){
            foreach($arrError as $strError){
                if(!substr_count($strMsg, $strError)){
                    continue 2;
                }
            }
            return true;    // wird nur erreicht, wenn alle true sind, sonst wird es über continue uebersprungen!
        }

        return false;
    }
}