
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="post" enctype="<?php echo $this->enctype; ?>">
<div class="formbody">
<input type="hidden" name="FORM_SUBMIT" value="<?php echo $this->formId; ?>" />
<?php if ($this->personal): ?>
<fieldset><legend><?php echo $this->personalData; ?></legend>
<table cellspacing="0" cellpadding="0" summary="<?php echo $this->personalData; ?>">
<?php foreach ($this->personal as $field) echo $field; ?>
</table>
</fieldset>
<?php endif; ?>
<?php if ($this->address): ?>
<fieldset><legend><?php echo $this->addressDetails; ?></legend>
<table cellspacing="0" cellpadding="0" summary="<?php echo $this->addressDetails; ?>">
<?php foreach ($this->address as $field) echo $field; ?>
</table>
</fieldset>
<?php endif; ?>
<?php if ($this->contact): ?>
<fieldset><legend><?php echo $this->contactDetails; ?></legend>
<table cellspacing="0" cellpadding="0" summary="<?php echo $this->contactDetails; ?>">
<?php foreach ($this->contact as $field) echo $field; ?>
</table>
</fieldset>
<?php endif; ?>
<?php if ($this->login): ?>
<fieldset><legend><?php echo $this->loginDetails; ?></legend>
<table cellspacing="0" cellpadding="0" summary="<?php echo $this->loginDetails; ?>">
<?php foreach ($this->login as $field) echo $field; ?>
</table>
</fieldset>
<?php endif; ?>
<?php if ($this->newsletter): ?>
<fieldset><legend><?php echo $this->newsletterDetails; ?></legend>
<table cellspacing="0" cellpadding="0" summary="<?php echo $this->newsletterDetails; ?>">
<?php foreach ($this->newsletter as $field) echo $field; ?>
</table>
</fieldset>
<?php endif; ?>
<?php if ($this->helpdesk): ?>
<fieldset><legend><?php echo $this->helpdeskDetails; ?></legend>
<table cellspacing="0" cellpadding="0" summary="<?php echo $this->helpdeskDetails; ?>">
<?php foreach ($this->helpdesk as $field) echo $field; ?>
</table>
</fieldset>
<?php endif; ?>
<?php if ($this->captcha): ?>
<fieldset><legend><?php echo $this->captchaDetails; ?></legend>
<table cellspacing="0" cellpadding="0" class="captcha_container" summary="Captcha">
<?php echo $this->captcha; ?> 
</table>
</fieldset>
<?php endif; ?>
<div class="submit_container">
<input type="submit" class="submit" value="<?php echo $this->slabel; ?>" />
</div>
</div>
</form>

</div>
<!-- indexer::continue -->
