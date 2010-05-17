
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> two_column tableform login block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" id="tl_login" method="post">
<div class="formbody">
<?php if ($this->message): ?>
<p class="error"><?php echo $this->message; ?></p>
<?php endif; ?>
<input type="hidden" name="FORM_SUBMIT" value="tl_login" />
<table cellspacing="0" cellpadding="0" summary="">
  <tr class="row_0 row_first">
    <td class="col_0 col_first"><label for="username"><?php echo $this->username; ?></label></td>
    <td class="col_1 col_last"><input type="text" name="username" id="username" class="text" maxlength="64" value="<?php echo $this->value; ?>" /></td>
  </tr>
  <tr class="row_1">
    <td class="col_0 col_first"><label for="password"><?php echo $this->password; ?></label></td>
    <td class="col_1 col_last"><input type="password" name="password" id="password" class="text" maxlength="64" value="" /></td>
  </tr>
  <tr class="row_2 row_last">
    <td class="col_0 col_first">&nbsp;</td>
    <td class="col_1 col_last"><div class="submit_container"><input type="submit" class="submit" value="<?php echo $this->slabel; ?>" /></div></td>
  </tr>
</table>
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
