<?php

include_once('adjustContao.php');


/**
 * Description:
 *
 *
 * @author      pfroch (e@sy Solutions IT) <info@easySolutionsIT.de>
 * @copyright   Copyright 2012 by e@sy Solutions IT
 * @version     1.0.0
 * @since       12.11.12 - 16:38
 * @package     esHtmlReporter.php
 */
class esHtmlReporter extends HtmlReporter{


    private $intPasses          = 0;
    private $intFails           = 0;
    private $intExeptions       = 0;
    private $intErrors          = 0;
    private $intSkips           = 0;
    private $intNotImplemented  = 0;
    private $countAfterError    = 0;
    private $strContent         = '';
    private $strResults         = '';
    private $strSummary         = '';


    public function __construct($character_set = 'UTF-8'){
        parent::__construct($character_set);
        $this->characterset = $character_set;
        $this->adjustContao = new \adjustContao();
    }

    /**
     *    Send the headers necessary to ensure the page is
     *    reloaded on every request. Otherwise you could be
     *    scratching your head over out of date test data.
     *    @access public
     */
    static function sendNoCacheHeaders() {
        if (! headers_sent()) {
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        }
    }

    /**
     *    Paints formatted text such as dumped privateiables.
     *    @param string $message        Text to show.
     *    @access public
     */
    function paintFormattedMessage($message) {
        print '<pre>' . $this->htmlEntities($message) . '</pre>';
    }

    /**
     *    Character set adjusted entity conversion.
     *    @param string $message    Plain text or Unicode message.
     *    @return string            Browser readable message.
     *    @access protected
     */
    protected function htmlEntities($message) {
        return htmlentities($message, ENT_COMPAT, $this->characterset);
    }


    function paintStart($test_name, $size) {
        parent::paintStart($test_name, $size);
    }


    function paintEnd($test_name, $size) {
        parent::paintEnd($test_name, $size);
    }


    private function makeMainContent($blnShowText = false){
        if($blnShowText){
            $templateM                  = new esTemplate(HTMLFOLDER . '/templates/main_text.php');
            $templateM->button          = urlRouter::makeTestForm();
            $templateM->safeModeWarning = urlRouter::makeSafeModeWarning();
            return $templateM->parse();
        } else {
            return '<h1 class="borderbottom"><a href="/files/unittests/index.php">' . TESTSUITENAME . '</a></h1>' . "\n" . urlRouter::makeSafeModeWarning() . "\n" . urlRouter::makeTestForm();
        }
    }


    /**
     *    Paints the top of the web page setting the
     *    title to the name of the starting test.
     *    @param string $test_name      Name class of test.
     *    @access public
     */
    function paintHeader($test_name) {
        $strContent         = '';
        $templateH          = new esTemplate(HTMLFOLDER . '/templates/main_header.php');
        $strContent        .= $templateH->parse();
        $this->strContent   = $strContent;
    }


    /**
     *    Paints the end of the test with a summary of
     *    the passes and failures.
     *    @param string $test_name        Name class of test.
     *    @access public
     */
    function paintFooter($test_name) {
        $strTxtClass                    = ($this->intFails + $this->intExeptions + $this->intErrors > 0) ? 'text-error' : 'text-success';
        $strSummaryTxt                  = ($this->intFails + $this->intExeptions + $this->intErrors > 0) ? 'Tests failed' : 'Tests passed';

        // Summary
        $templateS                      = new esTemplate(HTMLFOLDER . '/templates/summary.php');
        $templateS->strTxtClass         = $strTxtClass;
        $templateS->strSummaryTxt       = $strSummaryTxt;
        $templateS->getTestCaseProgress = $this->getTestCaseProgress();
        $templateS->getTestCaseCount    = ($this->getTestCaseProgress() > 0) ? $this->getTestCaseCount() : 0;
        $templateS->intPasses           = $this->intPasses;
        $templateS->intFails            = $this->intFails;
        $templateS->intNotImplemented   = $this->intNotImplemented;
        $templateS->intExeptions        = $this->intExeptions;
        $templateS->intErrors           = $this->intErrors;
        //$templateS->diagramm            = $this->makeDiagramm();  // raus, zu viel Aufwand!!!
        $this->strSummary              .= $templateS->parse();

        // Site-Footer
        $templateF                      = new esTemplate(HTMLFOLDER . '/templates/main_footer.php');
        $strFooter                      = $templateF->parse();

        // Seite zusammenfuegen
        $templateC                      = new esTemplate(HTMLFOLDER . '/templates/main_content.php');
        $templateC->content             = $this->makeMainContent();
        $templateC->content            .= "<h2>Zusammenfassung</h2>\n";
        $templateC->content            .= $this->strSummary;
        $templateC->content            .= "<h2>Testergebnisse</h2>\n<div id='content'>\n<div class='testresults'>\n";
        $templateC->content            .= $this->strResults;
        $templateC->content            .= "</div> <!-- END: .testresults -->\n";
        $this->strContent              .= $templateC->parse();
        $this->strContent              .= $strFooter;
        echo $this->strContent;
    }

    /**
     * Print passes
     * @param string $message
     */
    function paintPass($message) {
        $this->intPasses++;

        if(SHOWEXTENDED) {

            /**
             * Erweiterte Ansicht fuer die erfolgriechen Tests
             */
            $arrOptions['msg']  = (is_object($message)) ? $message->getMessage() : $message; #'<i id="button_Passes_' . $this->intPasses .'" class="icon-ok-sign icon-white modallink" title="' . $breadcrumb[3] . '::' . $breadcrumb[4] .'()"></i>';
            $arrOptions['class']= 'success text-small';
            $this->strResults  .=  $this->makeExtendedInfo('passes', $this->intPasses, $message, $arrOptions);
            $this->strResults  .=  $this->makeWhiteSpace();
            $this->countAfterError++;

        } else {

            /**
             * Kompakte Ansicht fuer die erfolgreichen Tests
             */
            $this->strResults.= '<span class="text-success">.</span>';
            $this->strResults.= $this->makeWhiteSpace();
            $this->countAfterError++;

        }
    }


    /**
     *    Prints the message for skipping tests.
     *    @param string $message    Text of skip condition.
     *    @access public
     */
    function paintSkip($message) {
        $this->intSkips++;

        if(SHOWEXTENDED){
            $arrOptions['msg']  =  (is_object($message)) ? $message->getMessage() : $message;
            $arrOptions['class']= 'success text-small';
            $this->strResults  .= $this->makeExtendedInfo('skiped', $this->intSkips, $message, $arrOptions);
        } else {
            $this->countAfterError++;
            $this->strResults  .= '<span class="text-success">-</span>';
            $this->strResults  .= $this->makeWhiteSpace();
        }
    }


    /**
     *    Paints the test failure with a breadcrumbs
     *    trail of the nesting test suites below the
     *    top level test.
     *    @param string $message    Failure message displayed in
     *                              the context of the other tests.
     */
    function paintFail($message) {
        $this->intFails++;
        $tmpMessage             =  (is_object($message)) ? $message->getMessage() : $message;
        $arrOptions['msg']      =  str_replace('with', "with<br />\n", str_replace('and', "<br />\nand<br />\n", $tmpMessage));
        $arrOptions['class']    = 'error';
        $this->strResults      .= $this->makeExtendedInfo('failed', $this->intFails, $message, $arrOptions);
        $this->countAfterError  = 0;
    }


    /**
     *    Paints a PHP error.
     *    @param string $message        Message is ignored.
     *    @access public
     */
    function paintError($message) {
        if(!$this->adjustContao->testContaoError($message)){    // Contao-spezifische Fehler (wie undef. Offset) ausfiltern!
            $this->intErrors++;
            $arrOptions['msg']      =  (is_object($message)) ? $message->getMessage() : $message;
            $arrOptions['class']    = 'warning';
            $this->strResults      .= $this->makeExtendedInfo('error', $this->intErrors, $message, $arrOptions);
            $this->countAfterError  = 0;
        } else {
            #$this->countAfterError; // Damit die Aufteilung der erfolgreichen Tests nicht durch einander geraet.
        }
    }


    /**
     *    Paints a PHP exception.
     *    @param Exception $exception        Exception to display.
     *    @access public
     */
    function paintException($message) {
        if(preg_match('/Class .+ does not exist/i', $message->getMessage()) === 1){
            // Not implemented yet!
            $this->paintNotImplemented($message);
        } else {
            // Exeption
            $this->intExeptions++;
            $arrOptions['msg']      =  (is_object($message)) ? $message->getMessage() : $message;
            $arrOptions['class']    = 'warning';
            $this->strResults      .= $this->makeExtendedInfo('exception', $this->intExeptions, $message, $arrOptions);
            $this->countAfterError  = 0;
        }
    }


    /**
     * Wenn eine Klasse nicht gefunden wird, wird keine Exeption gezaehlt,
     * sondern ein "not implemented yet".
     * @param $exception
     */
    function paintNotImplemented($message){
        $this->intNotImplemented++;
        $tmpMessage         =  (is_object($message)) ? $message->getMessage() : $message;
        $arrOptions['msg']  = str_replace('does not exist', 'not implemented yet',$tmpMessage);
        $arrOptions['class']= 'info';
        $this->strResults  .= $this->makeExtendedInfo('notImplemented', $this->intNotImplemented, $message, $arrOptions);
    }


    /**
     * Erzeugt die Freiraeume fuer bessere Lesbarkeit.
     * @return string
     */
    private function makeWhiteSpace(){
        $strContent = '';
        $tmpCounter = $this->countAfterError + 1;

        if($tmpCounter > 0){
            // Alle 10 erfolgreichen Tests ein Leerzeichen einfuegen
            if($tmpCounter % 10 == 0){
                $strContent .= '&nbsp;';
            }

            // Alle 50 erfolgreichen Tests ein Pipe "|" einfuegen
            if($tmpCounter % 50 == 0 && $tmpCounter % 200 != 0){
                $strContent .= '<span class="text-success">| </span>';
            }

            // Alle 100 erfolgreichen Tests ein Pipe "|" einfuegen
            if($tmpCounter % 100 == 0 && $tmpCounter % 200 != 0){
                $strContent .= '<span class="text-success">| </span>';
            }

            // Alle 200 erfolgreichen Tests ein weiteren Zeilenumbruch einfuegen
            if($tmpCounter % 200 == 0){
                $strContent .= "<br />\n";
            }
        }

        return $strContent;
    }


    /**
     * Erzeugt eine erweiterte Ausgabe fuer einen Test.
     * @param $kind
     * @param $count
     * @param $objInfo
     * @param $arrOptions
     * @return string
     */
    private function makeExtendedInfo($kind, $count, $objInfo, $arrOptions){
        $breadcrumb         = $this->getTestList();
        $template           = new esTemplate(HTMLFOLDER . '/templates/extendedInfo.php');
        $template->methode  = $breadcrumb[count($breadcrumb)-1];
        $arrOptions['kind'] = (array_key_exists('kind', $arrOptions)) ? $arrOptions['kind'] : $kind;
        $arrOptions['count']= (array_key_exists('count', $arrOptions)) ? $arrOptions['count'] : $count;
        $arrOptions['class']= (array_key_exists('class', $arrOptions)) ? $arrOptions['class'] : $kind;
        $arrOptions['icon'] = (array_key_exists('icon', $arrOptions)) ? $arrOptions['icon'] : $kind;
        $arrOptions['msg']  = $this->convertInfoString($arrOptions);

        // Anfuehrungszeichen loeschen
        foreach($arrOptions as $k => $v){
            $arrOptions[$k] = str_replace('"', '', str_replace("'", '', $v));
        }

        foreach($arrOptions as $k => $v){
            $template->$k = $v;
        }

        $strModal   = $this->makeModal($kind . '_' . $count, $objInfo, $breadcrumb, $arrOptions['class']);
        $strExtInfo = $template->parse();
        return $strExtInfo . "\n" . $strModal;
    }


    /**
     * Verarbeitet den Infostring fuer den Link.
     * @param $arrOptions
     * @return mixed
     */
    private function convertInfoString($arrOptions){
        $strMsg     = (array_key_exists('msg', $arrOptions)) ? $arrOptions['msg'] : '';
        $arrMsg     = explode(' in ', $strMsg);

        if(count($arrMsg) == 1){
            $arrMsg = explode(' at [', $strMsg);
        }

        $strContent = str_replace('[', '<strong>[', $arrMsg[0]);
        $strContent = str_replace(']', ']</strong>', $strContent);
        $strContent = str_replace('<br />', '', $strContent);
        $strContent = str_replace('<br>', '', $strContent);
        $strContent = str_replace("\n", '', $strContent);
        return $strContent;
    }


    /**
     * Erzeugt ein Modal-Window mit zusaetzlichen Informationen.
     * @param $strId
     * @param $strMsg
     * @param $arrStack
     */
    private function makeModal($strId, $strMsg, $arrStack, $strClass = 'info'){
        $strModal   = "\n<div id='modal_%s' title='%s::%s()'>\n<p class='text-%s'>%s</p></div>";
        if(!substr_count($strId, 'failed')){
            $arrBody = explode(' at ', $strMsg);
        } else {
            $arrBody[0] = $strMsg;
        }

        $content = $this->convertModalString($arrBody[0]);

        if(is_array($arrBody) && count($arrBody) >= 2){
            $arrBody[1] = str_replace(TESTFOLDER, '', $arrBody[1]);
        } else {
            $arrBody[1] = '';
        }

        return sprintf($strModal, $strId, $arrStack[3], $arrStack[4], $strClass, $content, $arrBody[1], $strId, $strId, $strId);
    }


    /**
     * Verarbeitet die Meldung fuer das Modal-Window.
     * @param $strData
     * @param bool $bolAddStrong
     * @return mixed
     */
    private function convertModalString($strData, $bolAddStrong = true){
        $content        = $strData;
        $content        = str_replace(' in ', "\n<br />in ", $content);
        $content        = str_replace(' at [', "\n<br />at [", $content);
        $content        = str_replace(TL_ROOTFOLDER, '', $content);
        $content        = str_replace('#', "<br />\n#",$content);
        $content        = str_replace('Stack trace:', "<br/><br />\n<div class='stacktrace'><strong>Stack trace:</strong>",$content); # . '</div>'; //-> Flasch bei Error!
        return $content;
    }


/*    private function makeDiagramm(){
            $width              = 920;
            $height             = 20;
            $count              = $this->intPasses + $this->intErrors + $this->intExeptions + $this->intNotImplemented + $this->intFails;
            $widthPasses        = 920 / $count * $this->intPasses;
            $widthErrors        = 920 / $count * $this->intErrors;
            $widthExeptions     = 920 / $count * $this->intExeptions;
            $widthNotImplemented= 920 / $count * $this->intNotImplemented;
            $widthFails         = 920 / $count * $this->intFails;
            $strDia             = '<div id="summarydiagramm">';
            $strDia            .= '<span class="passes" style="width:' . $widthPasses . 'px;">' . $this->intPasses . '</span>';
            $strDia            .= '<span class="notImplemented" style="width:' . $widthNotImplemented . 'px;">' . $this->intNotImplemented . '</span>';
            $strDia            .= '<span class="errors" style="width:' . $widthErrors . 'px;">' . $this->intErrors . '</span>';
            $strDia            .= '<span class="exeptions" style="width:' . $widthExeptions . 'px;">' . $this->intExeptions . '</span>';
            $strDia            .= '<span class="fails" style="width:' . $widthFails . 'px;">' . $this->intFails . '</span>';
            $strDia            .= '</div>';
            return $strDia;
        }*/

}