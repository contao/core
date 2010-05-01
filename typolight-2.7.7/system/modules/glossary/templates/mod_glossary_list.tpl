
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>
<?php foreach ($this->terms as $key=>$terms): ?>

<p class="toplink"><a href="<?php echo $this->request; ?>#top"><?php echo $this->topLink; ?></a></p>
<h2 id="<?php echo $terms[0]['anchor']; ?>"><?php echo $key; ?></h2>

<dl>
<?php foreach ($terms as $term): ?>
<dt><?php echo $term['term']; ?></dt>
<dd>
<div class="ce_text block">
<?php if ($term['addImage']): ?>
<div class="image_container"<?php if ($term['margin'] || $term['float']): ?> style="<?php echo $term['margin'] . $term['float']; ?>"<?php endif; ?>>
<?php if ($term['fullsize']): ?><a href="<?php echo $term['href']; ?>" title="<?php echo $term['alt']; ?>" rel="lightbox"><?php endif; ?>
<img src="<?php echo $term['src']; ?>"<?php echo $term['imgSize']; ?> alt="<?php echo $term['alt']; ?>" /><?php if ($term['fullsize']): ?></a><?php endif; ?> 
<?php if ($term['caption']): ?>
<div class="caption"><?php echo $term['caption']; ?></div>
<?php endif; ?>
</div>
<?php endif; ?>
<?php echo $term['definition']; ?>
</div>
<?php if ($term['enclosures']): ?>
<div class="enclosure">
<?php foreach ($term['enclosures'] as $enclosure): ?>
<p><img src="<?php echo $enclosure['icon']; ?>"<?php echo $enclosure['size']; ?> alt="<?php echo $enclosure['title']; ?>" class="mime_icon" /> <a href="<?php echo $enclosure['href']; ?>" title="<?php echo $enclosure['title']; ?>"><?php echo $enclosure['link']; ?></a></p>
<?php endforeach; ?>
</div>
<?php endif; ?>
</dd>
<?php endforeach; ?>
</dl>
<?php endforeach; ?>

</div>
