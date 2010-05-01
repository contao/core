
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<?php foreach ($this->files as $file): ?>
<p><img src="<?php echo $file['icon']; ?>"<?php echo $file['imgSize']; ?> alt="" class="mime_icon" /> <a href="<?php echo $file['href']; ?>" title="<?php echo $file['alt']; ?>"><?php echo $file['link']; ?></a></p>
<?php endforeach; ?>

</div>
<!-- indexer::continue -->
