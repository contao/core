
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> one_column tableless login block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" id="tl_login" method="post">
<div class="formbody">
<?php if ($this->message): ?>
<p class="error"><?php echo $this->message; ?></p>
<?php endif; ?>
<input type="hidden" name="FORM_SUBMIT" value="tl_login" />
<label for="username"><?php echo $this->username; ?></label>
<input type="text" name="username" id="username" class="text" maxlength="64" value="<?php echo $this->value; ?>" /><br />
<label for="password"><?php echo $this->password; ?></label>
<input type="password" name="password" id="password" class="text password" maxlength="64" value="" /><br />
<div class="submit_container">
<input type="submit" class="submit" value="<?php echo $this->slabel; ?>" />
</div>
</div>
</form>
<?php if ($this->hasError): ?>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
window.scrollTo(null, ($('tl_login').getElement('p.error').getPosition().y - 20));
//--><!]]>
</script>
<?php endif; ?>

</div>
<!-- indexer::continue -->
