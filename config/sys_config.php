<?php

$view = array_key_exists('view', $_GET) ? $_GET['view'] : 'normal';

switch($view){
    case 'old':
        $INCLUDEBOOOTSTRAP  = false;
        $SHOWEXTENDED       = false;
        break;

    case 'extended':
        $INCLUDEBOOOTSTRAP  = true;
        $SHOWEXTENDED       = true;
        break;

    case 'normal':
    default:
        $INCLUDEBOOOTSTRAP  = true;
        $SHOWEXTENDED       = false;
        break;
}

define('INCLUDEBOOOTSTRAP', $INCLUDEBOOOTSTRAP);    // Fuer den Output Bootstrap verwenden.
define('SHOWEXTENDED', $SHOWEXTENDED);              // Erweiterte Ansicht fuer erfolgreiche Tests einschalten (nur moeglich, wenn die ausfuehrliche Ansicht ausgeschaltet ist!).

// Define Pathes
define('UNITTESTDIR', '/files/unittests');
define('TESTDIR', '/files/unittests/testclasses');
define('CONFIGDIR', '/files/unittests/config');
define('SYSTEMDIR', '/system');
define('CLASSDIR', '/system/modules');
define('TOOLDIR', '/files/unittests/lib/es_tools');
define('HTMLDIR', '/files/unittests/html');

define('TL_ROOTFOLDER', str_replace(CONFIGDIR, '', dirname(__FILE__)));
define('UNITTESTFOLDER', TL_ROOTFOLDER . UNITTESTDIR);
define('TESTFOLDER', TL_ROOTFOLDER . TESTDIR);
define('CONFIGFOLDER', TL_ROOTFOLDER . CONFIGDIR);
define('SYSTEMFOLDER', TL_ROOTFOLDER . SYSTEMDIR);
define('CLASSFOLDER', TL_ROOTFOLDER . CLASSDIR);
define('TOOLFOLDER', TL_ROOTFOLDER . TOOLDIR);
define('HTMLFOLDER', TL_ROOTFOLDER . HTMLDIR);


// Define Options
$arrOptions['adjustContao']['addOptionsForFind']['fields'] = array('group', 'order', 'return');