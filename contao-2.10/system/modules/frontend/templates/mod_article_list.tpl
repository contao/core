
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<ul>
<?php foreach ($this->articles as $article): ?>
  <li><a href="<?php echo $this->request; ?>#<?php echo $article['id']; ?>" title="<?php echo $article['title']; ?>"><?php echo $article['link']; ?></a></li>
<?php endforeach; ?>
</ul>

</div>
