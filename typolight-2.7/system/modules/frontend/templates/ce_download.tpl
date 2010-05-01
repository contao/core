
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<img src="<?php echo $this->icon; ?>"<?php echo $this->imgSize; ?> alt="<?php echo $this->title; ?>" class="mime_icon" /> <a href="<?php echo $this->href; ?>" title="<?php echo $this->title; ?>"><?php echo $this->link; ?></a>

</div>
<!-- indexer::continue -->
