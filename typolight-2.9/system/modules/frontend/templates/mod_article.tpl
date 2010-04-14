
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->printable): ?>

<!-- indexer::stop -->
<div class="pdf_link">
<a href="<?php echo $this->href; ?>" rel="nofollow" title="<?php echo $this->title; ?>"><?php echo $this->label; ?></a>
</div>
<!-- indexer::continue -->
<?php endif; ?>
<?php echo $this->contentElements; ?>
<?php if ($this->backlink): ?>

<!-- indexer::stop -->
<p class="back"><a href="<?php echo $this->backlink; ?>" title="<?php echo $this->back; ?>"><?php echo $this->back; ?></a></p>
<!-- indexer::continue -->
<?php endif; ?>

</div>
