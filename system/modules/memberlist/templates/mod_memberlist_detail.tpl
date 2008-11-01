
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<h2><?php echo $this->publicProfile; ?></h2>
<?php if (count($this->record)): ?>

<table cellpadding="0" cellspacing="0" class="single_record" summary="">
<tbody>
<?php foreach ($this->record as $col): ?>
  <tr class="<?php echo $col['class']; ?>">
    <td class="label"><?php echo $col['label']; ?></td>
    <td class="value"><?php echo $col['content']; ?></td>
  </tr>
<?php endforeach; ?>
</tbody>
</table>
<?php elseif ($this->invalid): ?>

<p class="error"><?php echo $this->invalid; ?></p>
<?php else: ?>

<p><?php echo $this->noPublicInfo; ?></p>
<?php endif; ?>

<h2><?php echo $this->sendEmail; ?></h2>
<?php if ($this->confirm): ?>

<p class="confirm"><?php echo $this->confirm; ?></p>
<?php elseif ($this->allowEmail == 3): ?>

<form action="<?php echo $this->action; ?>" method="post">
<div class="formbody email_form">
<input type="hidden" name="FORM_SUBMIT" value="tl_send_email" />
<?php foreach ($this->fields as $objWidget): ?>
<div class="widget">
  <?php echo $objWidget->generateLabel(); ?><?php if ($objWidget->required): ?><span class="mandatory">*</span><?php endif; ?><br />
  <?php echo $objWidget->generateWithError(); ?> 
</div>
<?php endforeach; ?>
<div class="submit_container">
  <input type="submit" value="<?php echo $this->submit; ?>" class="submit" />
</div>
</div>
</form>
<?php elseif ($this->allowEmail == 2): ?>

<p><?php echo $this->loginToSend; ?></p>
<?php else: ?>

<p><?php echo $this->emailDisabled; ?></p>
<?php endif; ?>

<div class="go_back"><a href="<?php echo $this->referer; ?>" title="<?php echo $this->back; ?>"><?php echo $this->back; ?></a></div>

</div>
