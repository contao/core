
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="post">
<div class="formbody">
<?php if ($this->message): ?>
<p class="<?php echo $this->mclass; ?>"><?php echo $this->message; ?></p>
<?php endif; ?>
<input type="hidden" name="FORM_SUBMIT" value="<?php echo $this->formId; ?>" />
<?php if (!$this->showChannels): ?>
<?php foreach ($this->channels as $id=>$title): ?>
<input type="hidden" name="channels[]" value="<?php echo $id; ?>" />
<?php endforeach; ?>
<?php endif; ?>
<label for="ctrl_email" class="invisible"><?php echo $this->emailLabel; ?></label>
<input type="text" name="email" id="ctrl_email" class="text" value="<?php echo $this->email; ?>" />
<?php if ($this->showChannels): ?>
<label for="ctrl_channels" class="invisible"><?php echo $this->channelsLabel; ?></label>
<div id="ctrl_channels" class="checkbox_container">
<?php foreach ($this->channels as $id=>$title): ?>
<span><input type="checkbox" name="channels[]" id="opt_<?php echo $this->id; ?>_<?php echo $id; ?>" value="<?php echo $id; ?>" class="checkbox" /> <label for="opt_<?php echo $this->id; ?>_<?php echo $id; ?>"><?php echo $title; ?></label></span>
<?php endforeach; ?>
</div>
<?php endif; ?>
<input type="submit" name="submit" class="submit" value="<?php echo $this->submit; ?>" />
</div>
</form>

</div>
<!-- indexer::continue -->
