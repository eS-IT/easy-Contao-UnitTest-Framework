<h1><a href="/files/unittests/index.php"><?php echo TESTSUITENAME; ?></a></h1>
<div class="main-text">
    <p>
        Dies ist ein Tool zum Testen von Contao-Erweiterugnen, welches auf SimpleTest aufsetzt.
        So ist es möglich die Tests auf beliebigen Servern ohne Konfigurationsaufwand laufen zu lassen. Weitere Infos
        zum Testen mit SimpleTest gibt es unter <a href="http://www.simpletest.org/en/overview.html">SimpleTest</a>.
    </p><p>
        Das Framework ermöglicht es sehr einfach und schnell UnitTests für Contao-Erweiterungen zu schreiben und sowohl
        public als auch private und protected-Methoden zu testen. Da es dem "convention over configuration"-Paradigma
        folgt, werden alle benötigten Klassen und Dateien automatisch geladen. Wenn man sich an die Namens-Konvention
        hält, genügt es, eine Testklasse anzulegen und die Test zu starten. Benötigt man eine Testdatenbank, gibt man
        die Zugangsdaten ein und legt eine SQL-Datei mit der Datenbankstruktur an. Diese wird vor den Tests automatisch
        verarbeitet. So ist es möglich in weniger 2 Minuten die ersten Tests fertig zustellen.
    </p><p>
        Weitere Infos zu diesem Testframework finden Sie auf der Projektwebseite: <a href="https://github.com/eS-IT/easy-Contao-UnitTest-Framework">https://github.com/eS-IT/easy-Contao-UnitTest-Framework.</a>
    </p><p class="thistext">
        <small>Um diesen Text zu ändern, bearbeiten Sie die Datei: /unittests/html/templates/main_text.php.</small>
    </p><p>
        <strong>Um die Tests zu starten, drücken Sie bitte den Start-Button.</strong>
    </p>
</div>
<?php echo $this->safeModeWarning; ?>
<?php echo $this->button; ?>