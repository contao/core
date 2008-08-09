
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<h1><?php echo $this->subject; ?></h1>

<div class="newsletter">
<?php echo $this->content; ?>
</div>
<?php if ($this->enclosure): ?>

<div class="enclosure">
<?php foreach ($this->enclosure as $enclosure): ?>
<p><img src="<?php echo $enclosure['icon']; ?>"<?php echo $enclosure['size']; ?> alt="<?php echo $enclosure['title']; ?>" class="mime_icon" /> <a href="<?php echo $enclosure['href']; ?>" title="<?php echo $enclosure['title']; ?>"><?php echo $enclosure['link']; ?></a></p>
<?php endforeach; ?>
</div>
<?php endif; ?>

<p class="back"><a href="<?php echo $this->referer; ?>" title="<?php echo $this->back; ?>"><?php echo $this->back; ?></a></p>

</div>
