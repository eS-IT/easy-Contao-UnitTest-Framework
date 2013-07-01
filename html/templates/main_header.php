<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->testsutiename ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Cache-control" content="no-cache">
        <meta http-equiv="Cache-control" content="max-age=0">
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />

        <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
        <link rel="stylesheet" href="/files/unittests/html/js/chosen/chosen.css" />
        <?php foreach($this->arrCssFiles as $strCssFile): ?>
            <link rel="stylesheet" href="<?php echo $strCssFile; ?>" />
        <?php endforeach; ?>


    </head>
    <body>
        <div id='wrapper'>