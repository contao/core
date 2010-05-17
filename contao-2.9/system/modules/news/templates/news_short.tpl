
<div class="layout_short block<?php echo $this->class; ?>">
<?php if ($this->hasMetaFields): ?>
<p class="info"><?php echo $this->date; ?> <?php echo $this->author; ?> <?php echo $this->commentCount; ?></p>
<?php endif; ?>
<h2><?php echo $this->linkHeadline; ?></h2>
<p class="teaser"><?php echo $this->teaser; ?></p>
<?php if ($this->text): ?>
<p class="more"><?php echo $this->more; ?></p>
<?php endif; ?>
</div>
