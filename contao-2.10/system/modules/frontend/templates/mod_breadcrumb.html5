
<!-- indexer::stop -->
<nav class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<?php foreach ($this->items as $item): ?>
<?php if ($item['isActive']): ?>
<span class="active"><?php echo $item['title']; ?></span>
<?php else: ?>
<a href="<?php echo $item['href']; ?>" title="<?php echo $item['title']; ?>"><?php echo $item['link']; ?></a> &#62; 
<?php endif; ?>
<?php endforeach; ?>

</nav>
<!-- indexer::continue -->
