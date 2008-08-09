
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>
<?php echo $this->event; ?>

<p class="back"><a href="<?php echo $this->referer; ?>" title="<?php echo $this->back; ?>"><?php echo $this->back; ?></a></p>

</div>
