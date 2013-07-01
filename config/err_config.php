<?php
/**
 * Contao specific errors
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2013
 * @license     LGPL
 * @package
 * @filesource  err_config.php
 * @version     1.0.0
 * @since       31.05.13 - 14:54
 */


/*
 * Name:
 * =====
 * Undefined offset: 0 in Statement.php line 247
 *
 * Error-Description:
 * ==================
 * In der save-Methode der Contao-Model-Klasse (/CTO_ROOT/system/Modules/core/library/Model.php)
 * wird in Zeile 283 fuer die Inserts Method chaining benutzt. Hierbei wird von der set-Methode
 * der Statmentklasse (/CTO_ROOT/system/modules/core/library/Contao/Database/Statement.php)zwar
 * in Zeile 209 $this zurueckgegeben, in execute() kann aber nicht mittels func_get_args() darauf
 * Zugegriffen werden. In Statement::execute() kommte es beim Zugriff auf das Array mit den übergebenen
 * Argumenten deshalb in Zeile 247 zu einem PHP error [Undefined offset: 0]. Da das Objekt uebergeben
 * werden sollte, es sich aber im $this handelt funktionieren die Abfragen trotzdem und der Fehler kann
 * ignoriert werden.
 */
$arrError['modelSave'] = array(
    'PHP error [Undefined offset: 0]',
    'core/library/Contao/Database/Statement.php line 247'
);


/*
 * Name:
 * =====
 * Undefined index: group in QueryBuilder.php line 81
 *
 * Error-Description:
 * ==================
 * Beim Aufruf der find-Methoden fehlt im Option-Array das Feld 'group'.
 * Dies Feld wird in Zeile 81 der Datei /CTO_ROOT/system/modules/core/library/Contao/Model/QueryBuilder.php
 * auf ungleich (!==) NULL getestet.
 */
$arrError['modelFindGroup'] = array(
    'PHP error [Undefined index: group]',
    'core/library/Contao/Model/QueryBuilder.php line 81'
);


/*
 * Name:
 * =====
 * Undefined index: order in QueryBuilder.php line 87
 *
 * Error-Description:
 * ==================
 * Beim Aufruf der find-Methoden fehlt im Option-Array das Feld 'order'.
 * Dies Feld wird in Zeile 87 der Datei /CTO_ROOT/system/modules/core/library/Contao/Model/QueryBuilder.php
 * auf ungleich (!==) NULL getestet.
 */
$arrError['modelFindOrder'] = array(
    'PHP error [Undefined index: order]',
    'core/library/Contao/Model/QueryBuilder.php line 87'
);



return $arrError;