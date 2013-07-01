<form action="<?php echo $this->link; ?>" method="get" id="testform">
    <input type="hidden" name="site" value="runtest">
    <input type="hidden" name="view" value="<?php echo $this->view; ?>">

    <label for="test">Bitte wählen Sie einen Test:</label>
    <?php echo $this->selectTest; ?>

    <div id="buttoncontainer">
        <a class="mybigbutton" onclick="spin('#buttoncontainer');">
            <div>
                <img src="/files/unittests/html/img/play.png" title="<?php echo ($this->title != '') ? $this->title : $this->text; ?>" alt="<?php echo ($this->alt != '') ? $this->alt : $this->text; ?>" />
                <span>
                    <?php echo $this->text; ?>
                </span>
            </div>
        </a>
    </div>
</form>