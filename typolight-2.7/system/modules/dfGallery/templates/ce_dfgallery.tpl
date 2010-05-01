
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<div id="<?php echo $this->flashId; ?>">
<?php echo $this->alt; ?> 
</div>

<!-- indexer::stop -->
<script type="text/javascript">
<!--//--><![CDATA[//><!--
swfobject.embedSWF("<?php echo $this->href; ?>", "<?php echo $this->flashId; ?>", "<?php echo $this->width; ?>", "<?php echo $this->height; ?>", "8.0.0", false, false, { allowfullscreen: "true", flashvars: "<?php echo $this->flashvars; ?>" });
//--><!]]>
</script>
<!-- indexer::continue -->

</div>
