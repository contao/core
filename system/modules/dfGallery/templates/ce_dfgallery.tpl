
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<div id="<?php echo $this->flashId; ?>">
<?php echo $this->alt; ?> 
</div>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
UFO.launch("<?php echo $this->flashId; ?>", {
  movie: "<?php echo $this->href; ?>",
  width: "<?php echo $this->width; ?>",
  height: "<?php echo $this->height; ?>",
  allowfullscreen: "true",
  flashvars: "<?php echo $this->flashvars; ?>",
  majorversion: "8",
  build: "0"
});
//--><!]]>
</script>

</div>
<!-- indexer::continue -->
