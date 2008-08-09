
<?php if (!$this->searchable): ?>
<!-- indexer::stop -->
<?php endif; ?>
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<div class="rss_default_header">
<h1><a href="<?php echo $this->link; ?>" onclick="window.open(this.href); return false;"><?php echo $this->title; ?></a></h1>
<?php if ($this->image): ?>
<a href="<?php echo $this->href; ?>" title="<?php echo $this->alt; ?>"><img src="<?php echo $this->src; ?>" alt="<?php echo $this->alt; ?>" /></a>
<?php endif; ?>
<?php if ($this->description): ?>
<p class="description"><?php echo $this->description; ?></p>
<?php endif; ?>
</div>
<?php foreach ($this->items as $item): ?>

<div class="rss_default<?php echo $item['class']; ?>">
<h2><a href="<?php echo $item['link']; ?>" onclick="window.open(this.href); return false;"><?php echo $item['title']; ?></a></h2>
<p class="description"><?php echo $item['description']; ?></p>
</div>
<?php endforeach; ?>
<?php echo $this->pagination; ?>

</div>
<?php if (!$this->searchable): ?>
<!-- indexer::continue -->
<?php endif; ?>
