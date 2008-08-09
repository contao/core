
<div class="layout_latest block<?php echo $this->class; ?>">
<?php if ($this->hasMetaFields): ?>
<p class="info"><?php echo $this->date; ?> <?php echo $this->author; ?> <?php echo $this->commentCount; ?></p>
<?php endif; ?>
<?php if ($this->addImage): ?>
<div class="image_container"<?php if ($this->margin || $this->float): ?> style="<?php echo $this->margin . $this->float; ?>"<?php endif; ?>>
<?php if ($this->fullsize): ?><a href="<?php echo $this->href; ?>" title="<?php echo $this->alt; ?>" rel="lightbox"><?php endif; ?>
<img src="<?php echo $this->src; ?>" alt="<?php echo $this->alt; ?>" /><?php if ($this->fullsize): ?></a><?php endif; ?> 
<?php if ($this->caption): ?>
<div class="caption"><?php echo $this->caption; ?></div>
<?php endif; ?>
</div>
<?php endif; ?>
<h2><?php echo $this->linkHeadline; ?></h2>
<p class="teaser"><?php echo $this->teaser; ?></p>
<?php if ($this->text): ?>
<p class="more"><?php echo $this->more; ?></p>
<?php endif; ?>
</div>
