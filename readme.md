# [DEPRECATED] e@sy-Contao-UntiTest-Framework

## About

e@sy-Contao-UntiTest-Framework ist ein Framework zum Testen von Contao-Erweiterugnen, welches auf [SimpleTest](http://www.simpletest.org/) aufsetzt. So ist es möglich die Tests auf beliebigen Servern ohne Konfigurationsaufwand laufen zu lassen. (Weitere Infos zum Testen mit SimpleTest gibt es [hier](http://www.simpletest.org/en/overview.html))

Das Framework ermöglicht es sehr einfach und schnell UnitTests für Contao-Erweiterungen zu schreiben und sowohl **public** als auch **private** und **protected** -Methoden zu testen. Da es dem ["convention over configuration"](http://de.wikipedia.org/wiki/Konvention_vor_Konfiguration)-Paradigma folgt, werden alle benötigten Klassen und Dateien automatisch geladen. Wenn man sich an die Namens-Konvention hält, genügt es, eine Testklasse anzulegen und die Test zu starten. Benötigt man eine Testdatenbank, gibt man die Zugangsdaten ein und legt eine SQL-Datei mit der Datenbankstruktur an. Diese wird vor den Tests automatisch verarbeitet. So ist es möglich in weniger 2 Minuten die ersten Tests fertig zustellen (s. Beispiel weiter unten).

Projektwebseite: https://github.com/eS-IT/easy-Contao-UntiTest-Framework.

Was ist e@sy-Contao-UntiTest-Framework nicht?

* Das Framework ist nicht dazu gedacht den Contao-Core zu testen.
* Es kann nicht alles rund um Contao testen, da Contao an einigen Stellen sehr speziell ist.
* SimpleTest ist kein PHPUnit, d.h. es ist viel leichter zu installieren und zu konfigurieren, aber es ist auch nicht so leistungsfähig.

Das Framework soll helfen möglichst einfach, einzelne Methoden einer Erweiterung zu testen, wer tiefer und detailierter Testen möchte, wird sich mit PHPUnit und den Eigenheiten von Contao auseinander setzen müssen.


## System requirements

 * Contao Open Souce CMS >= 3.1.0 stable


## Installation

 * Download des [Frameworks](https://github.com/eS-IT/easy-Contao-UntiTest-Framework/archive/master.zip)
 * Entpacken des Archives auf den Server unter TL_ROOT/files/unittests
 * Schreiben der Tests
 * Öffnen des Installationsverzeichnisses mit dem Browser (http;//YOURDOMAIN.TLD/files/unittests/)
 * Starten der Test


## Namens-Konvention

Alle Dateien und Ordner werden unter **TL_ROOT/files/unittests/testclasses** angelegt. Dies ist der zentrale Ordner die Testdaten. Es können Unterordner erstellt werden, um Testklassen und Testdaten zu gruppieren. 

Folgende Konvention müssen bei der Vergabe der Namen beachtet werden, damit das Testframework funktioniert:


### Testklassen:

* Im Ordner **TL_ROOT/files/unittests/testclasses** werden Unterordner für die zu testenden Klassen angelegt, die Namen spielen keine Rolle.
* In diesen Unterordnern befinden sich die Testklassen und (wenn gewünscht) ein Ordner **/data**.
* Die Testklassen müssen als erstes den Namen der zu testenden Klasse tragen, gefolgt von **_test** (z.B. Soll die Klasse **beispielMailer.php** getestet werden, muss die Testklasse **beispielMailer_test.php** heißen).
* Es können beliebige Namensbestandteile angehängt werden (z.B. der Name der zu testenden Methode) (z.B. **beispielMailer_test_sendMail.php**).
* Die Testmethoden müssen mit **test** beginnen!
* Die zu testenden Klassen werden automatisch geladen, die Methoden können in der Testklasse einfach über **$this->METHODENNAME** angesprochen werden.
* Es können sowohl **public** als auch **private** und **protected** -Methoden getestet werden!


### Vergleichsdaten:

* Da größere Datensätze für den Verglich mit Testresultaten die Testklassen schnell unübersichtlich machen, können diese in Arrays im Ordner **/data** ausgelegert werden. Die Daten werden automatisch geladen und stehen in der Testklasse über **$this->arrData** zur Verfügung.
* Die Namen der Array-Dateien müssen mit **array_** beginnen, gefolgt vom Namen der Testklasse (heißt die Testklasse **beispielMailer_test_sendMail.php**, so muss das Array **array_beispielMailer_test_sendMail.php** heißen).
* In den Array-Dateien darf nur ein Array  mit dem Namen **$arrData** sein.


### SQL-Dateien:

* Auch die SQL-Dateien werden im Verzeichnis **/data** gespeichert.
* Es gibt verschiedene SQL-Datein, die in verschiedenen Stadien der Tests verarbeitet werden.
* **setup.sql** wird in allen Testklassen in diesem Verzeichnis vor Beginn des ersten Tests verarbeitet (z.B. zum Erstellen der DB-Struktur).
* **teardown.sql** wird in allen Testklassen in diesem Verzeichnis nach Beendigung aller Tests verarbeitet (z.B. zum Löschen der Test-DB nach den Tests).
* Zusätzlich kann es für jede Testklasse noch individuelle SQL-Dateien geben, die direkt beim Aufruf der Testklasse, bzw. nach dem Abarbeiten der Tests einer Klasse verarbeitet werden.
* Die testspezifischen Dateien beginnen mit **setup_** oder **teardown_** gefolgt von dem Namen der Testklasse (z.B. **setup_beispielMailer_test_sendMail.sql**, bzw. **teardown_beispielMailer_test_sendMail.sql**)
* Es ist auch möglich die Dateien manuell vor jedem Test noch einmal zu verarbeiten.
* Weiterhin ist es möglich zusätzliche SQL-Dateien die nicht dem Namensschema folgen manuell zu verarbeiten.
* Außerdem können in der Testklasse direkt SQL-Strings verarbeitet werden.


## URL-Parameter

Wem die original Ausgabe von SimpleTest lieber ist, nutzt einfach den Parameter **view=old**, leider wird dann auf Contao-spezifische Eigenhaiten keine Rücksicht genommen und die meinsten Tests werden fehlschlagen!

Wer sich auch die erfolgreichen Tests anzeigen lassen will, beutzt **view=extended**.

Wenn man einmal Inofrmationen über PHP benötigt, kann man den Parameter **site=info** benutzen. Dies muss in der Konfigurationsdatei **TL_ROOT/files/unittests/config/config.php** erst eingeschaltet werden, damit nicht unbefugte diese Informationen einsehen können.


## Beispieltest

Die Klasse soll die Klasse output.php testen. Inhalt der zu testenden Klasse:

```php
class output extends System{

    public function parse($strMsg){
        return '<div class="output">' . $strMsg . '</div>';
    }
}
```

Die Klasse ist zu gegebenermassen sehr einfach, sie gibt den übergebenen Text in einem div zurück. Damit es übersichtlich bleibt, verzischte ich auf Plausibilitätsprüfungen usw. Um nun einen Test für die Klasse zu implementieren gehen wir wie folgt vor:

1. Anlegen eines Unterordners **example** in **TL_ROOT/files/unittests/testclasses**.
2. Anlegen einer Testklasse output_test.php in **TL_ROOT/files/unittests/testclasses/example**.
3. Schreiben der Tests z.B. so:

```php
class output_test extends esUnitTestCase{
    
    /**
     * Testumgebung vorbereiten
     */
    public function setUp() {
    }


    /**
     * Testumgebung aufraeumen
     */
    public function tearDown() {
    }
    

    /**
     * Test mit leerem Argument durchfuehren.
     */
    public function testParseEmpty() {
        $result = $this->parse('');
        $this->assertEqual($result, '<div class="output"></div>');
    }
    

    /**
     * Test mit Argument durchfuehren.
     */
    public function testParseEmpty() {
        $result = $this->parse('Mein Teststring!');
        $this->assertEqual($result, '<div class="output">Mein Teststring!</div>');
    }
}
```
Dieser Test übergibt ein leeres Argument und erwartet ein leeres Div als Antwort. 

Wir haben also in drei Schritten zweit einfache Tests für unsere Klasse angelegt. Der Fantasie sind bei der Komplexität natürlich keine Grenzen gesetzt.


## Statement

Ich benutze das Framkwork zum Testen meiner Erweiterungen. Wenn ich Zeit finde, werden ich es nach und nach erweitern und an die Eigenheiten von Contao anpassen. Da ich dies in meiner Freizeit tue, kann ich keine Zusagen machen wann es weiterentwicklet wird. Featurerequests nehme ich natürlich gerne entgegen, aber auch hier mache ich keine Versprechungen, ob oder wann etwas umgesetzt wird. Ich kann leider auch keinen Support für die Implementierung von UnitTests oder die Benutzung des Frameworks geben. Dieses Framework wird sicher nicht allen Anforderungen gerecht, aber so ist es auch nicht gedacht. Ich würde mich freuen, wenn es trotzdem dem einen oder anderen hilft.


## Weitere Informationen

* Weitere Informationen über das Testen mit [SimpleTest](http://www.simpletest.org/) sind auf der Projektseite zu finden ([LGPL](http://www.gnu.org/licenses/lgpl.html)). 
* Für die angepasste Ausgabe wird Twitter [Bootstrap](http://twitter.github.io/bootstrap/) verwendet ([Apache License v2.0](http://www.apache.org/licenses/LICENSE-2.0)).  
* Als Datenbankabstraktion diehnt [RedBeanPHP](http://redbeanphp.com/) ([New BSD License](http://redbeanphp.com/license)).
* Die Icons stammen von [Yusuke Kamiyamane](http://p.yusukekamiyamane.com/) ([CC BY 3.0](http://creativecommons.org/licenses/by/3.0/deed.de)).
* Das Exeption-Icon stammt von [David Vignoni](http://www.icon-king.com/) ([LGPL](http://www.gnu.org/licenses/lgpl.html)).
* Das Icon im Start-Button stammt von [Sergio Sánchez López](http://www.kde-look.org/usermanager/search.php?username=Sephiroth6779) ([GPL](http://www.gnu.org/copyleft/gpl.html))
* Das Hintergundbild "wood floors wallpaper" stammt von [osxdaily](http://osxdaily.com/2011/09/30/minimalist-distraction-free-writing-app-focuswriter/).
* Projekt auf github: https://github.com/eS-IT/easy-Contao-UntiTest-Framework
* Die sonstige geleistet Arbeit stammt von mir, weitere Infos über mich und mein Wirken gibt es auf http://easySolutionsIT.de.
* Wer die weitere Entwicklung unterstützen möchte, kann dazu meine [Wunschliste](http://www.amazon.de/registry/wishlist/2QF9QYP7FMOWZ) benutzen.
