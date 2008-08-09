
<?php if (!$this->searchable): ?>
<!-- indexer::stop -->
<?php endif; ?>
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php foreach($this->articles as $article) echo $article; ?>
<?php echo $this->pagination; ?>

</div>
<?php if (!$this->searchable): ?>
<!-- indexer::continue -->
<?php endif; ?>
