<div class="result <?php echo $this->kind; ?>">
    <span  onclick="openDialog('<?php echo $this->kind . '_' . $this->count; ?>')">
        <img src="/files/unittests/html/img/<?php echo $this->icon; ?>.png"id="button_<?php echo $this->kind; ?>_<?php echo $this->count; ?>" title="<?php echo $this->methode; ?>: <?php echo $this->msg; ?>" alt="<?php echo $this->methode; ?>: <?php echo $this->msg; ?>" />
        <span class="message text-<?php echo $this->class; ?>">
            <span style="font-family: courier; font-weight: bold;"><?php echo $this->methode; ?>:</span> <?php echo $this->msg; ?>
        </span>
    </span>
</div>