
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<ul class="anchors block">
<?php foreach ($this->anchors as $id=>$title): ?>
  <li><a href="<?php echo $this->request; ?>#<?php echo $id; ?>"><?php echo $title; ?></a></li>
<?php endforeach; ?>
</ul>

</div>
<!-- indexer::continue -->
