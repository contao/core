
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<<?php echo $this->tag; ?>>
<?php foreach ($this->items as $item): ?>
  <li<?php if ($item['class']): ?> class="<?php echo $item['class']; ?>"<?php endif; ?>><?php echo $item['content']; ?></li>
<?php endforeach; ?>
</<?php echo $this->tag; ?>>

</div>
