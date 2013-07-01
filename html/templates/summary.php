<div id="summary" class="well <?php echo $this->strTxtClass; ?>">
    <h3 class="strSummaryTxt"><?php echo $this->strSummaryTxt; ?></h3> -
    <strong><?php echo $this->getTestCaseProgress . " von " . $this->getTestCaseCount; ?> tests cases complete:</strong><br />
    <ul class="unstyled">

        <li class="<?php if(!$this->intPasses) echo 'discreet'; ?>">
            <img src="/files/unittests/html/img/passes.png" class="summaray" title="Tests passed" alt="Test passed" />
            <span class="summary count"><?php echo $this->intPasses; ?></span> passes
        </li>

        <li class="<?php if(!$this->intNotImplemented) echo 'discreet'; ?>">
            <img src="/files/unittests/html/img/notImplemented.png" class="summaray" title="Tests not implemented" alt="Tests not implemented" />
            <span class="summary count"><?php echo $this->intNotImplemented; ?></span> not implemented
        </li>

        <li class="<?php if(!$this->intFails) echo 'discreet'; ?>">
            <img src="/files/unittests/html/img/failed.png" class="summaray" title="Tests failed" alt="Test failed" />
            <span class="summary count"><?php echo $this->intFails; ?></span> fails
        </li>

        <li class="<?php if(!$this->intExeptions) echo 'discreet'; ?>">
            <img src="/files/unittests/html/img/exeption.png" class="summaray" title="Exceptions" alt="Exceptions" />
            <span class="summary count"><?php echo $this->intExeptions; ?></span> exceptions
        </li>

        <li class="<?php if(!$this->intErrors) echo 'discreet'; ?>">
            <img src="/files/unittests/html/img/error.png" class="summaray" title="Errors" alt="Errors" />
            <span class="summary count"><?php echo $this->intErrors; ?></span> errors
        </li>

    </ul>

</div><!-- END #summary -->