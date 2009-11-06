
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>

<h1><?php echo $this->headline; ?></h1>

<div class="teaser">
<?php echo $this->teaser; ?> 
<p class="more"><a href="<?php echo $this->href; ?>" title="<?php echo $this->readMore; ?>"><?php echo $this->more; ?> <span class="invisible"><?php echo $this->headline; ?></span></a></p>
</div>

</div>
