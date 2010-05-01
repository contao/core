
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<div class="image_container"<?php if ($this->margin): ?> style="<?php echo $this->margin; ?>"<?php endif; ?>>
<a href="<?php echo $this->href; ?>" rel="lightbox" title="<?php echo $this->alt; ?>"><img src="<?php echo $this->src; ?>"<?php echo $this->imgSize; ?> alt="<?php echo $this->alt; ?>" /></a>
<?php if ($this->caption): ?>
<div class="caption"><?php echo $this->caption; ?></div>
<?php endif; ?>
</div>

</div>
