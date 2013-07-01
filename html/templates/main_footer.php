        </div>

        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
        <script src="/files/unittests/html/js/chosen/chosen.jquery.min.js"></script>
        <?php foreach($this->arrJsFiles as $strJsFile): ?>
            <script src="<?php echo $strJsFile; ?>"></script>
        <?php endforeach; ?>
    </body>
</html>