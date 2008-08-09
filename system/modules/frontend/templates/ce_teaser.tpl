
<div class="<?php echo $this->class; ?> ce_text block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<h1><?php echo $this->headline; ?></h1>
<?php endif; ?>

<?php echo $this->text; ?> <a href="<?php echo $this->href; ?>" class="more"><?php echo $this->more; ?></a>

</div>
