
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> one_column logout block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="post">
<div class="formbody">
<?php if ($this->message): ?>
<p class="error"><?php echo $this->message; ?></p>
<?php endif; ?>
<input type="hidden" name="FORM_SUBMIT" value="tl_logout" />
<p class="login_info"><?php echo $this->loggedInAs; ?></p>
<div class="submit_container">
<input type="submit" class="submit" value="<?php echo $this->slabel; ?>" />
</div>
</div>
</form>

</div>
<!-- indexer::continue -->
