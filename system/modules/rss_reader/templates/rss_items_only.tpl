
<?php if (!$this->searchable): ?>
<!-- indexer::stop -->
<?php endif; ?>
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>
<?php foreach ($this->items as $item): ?>

<div class="rss_items_only<?php echo $item['class']; ?>">
<h2><a href="<?php echo $item['link']; ?>" onclick="window.open(this.href); return false;"><?php echo $item['title']; ?></a></h2>
<p class="description"><?php echo $item['description']; ?></p>
</div>
<?php endforeach; ?>
<?php echo $this->pagination; ?>

</div>
<?php if (!$this->searchable): ?>
<!-- indexer::continue -->
<?php endif; ?>
