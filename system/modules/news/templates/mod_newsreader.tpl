
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>
<?php echo $this->articles; ?>

<!-- indexer::stop -->
<p class="back"><a href="<?php echo $this->referer; ?>" title="<?php echo $this->back; ?>"><?php echo $this->back; ?></a></p>
<!-- indexer::continue -->
<?php if ($this->allowComments && ($this->comments || !$this->protected)): ?>

<div class="ce_comments block">

<<?php echo $this->hl; ?>><?php echo $this->addComment; ?></<?php echo $this->hl; ?>>
<?php foreach ($this->comments as $comment) echo $comment; ?>
<?php echo $this->pagination; ?>
<?php if (!$this->protected): ?>

<!-- indexer::stop -->
<div class="form">
<?php if ($this->confirm): ?>

<p class="confirm"><?php echo $this->confirm; ?></p>
<?php else: ?>

<form action="<?php echo $this->action; ?>" method="post">
<div class="formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_comment" />
<div class="widget">
  <?php echo $this->fields['name']->generateWithError(); ?> <?php echo $this->fields['name']->generateLabel(); ?>
</div>
<div class="widget">
  <?php echo $this->fields['email']->generateWithError(); ?> <?php echo $this->fields['email']->generateLabel(); ?>
</div>
<div class="widget">
  <?php echo $this->fields['website']->generateWithError(); ?> <?php echo $this->fields['website']->generateLabel(); ?>
</div>
<?php if (isset($this->fields['captcha'])): ?>
<div class="widget">
  <?php echo $this->fields['captcha']->generateWithError(); ?> <?php echo $this->fields['captcha']->generateQuestion(); ?>
</div>
<?php endif; ?>
<div class="widget">
  <?php echo $this->fields['comment']->generateWithError(); ?> <label for="ctrl_<?php echo $this->fields['comment']->id; ?>" class="invisible"><?php echo $this->fields['comment']->label; ?></label>
</div>
<div class="submit_container">
  <input type="submit" class="submit" value="<?php echo $this->submit; ?>" />
</div>
</div>
</form>
<?php endif; ?>

</div>
<!-- indexer::continue -->
<?php endif; ?>

</div>
<?php endif; ?>

</div>
