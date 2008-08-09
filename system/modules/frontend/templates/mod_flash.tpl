
<?php if (!$this->searchable): ?>
<!-- indexer::stop -->
<?php endif; ?>
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<div id="<?php echo $this->flashId; ?>">
<?php echo $this->alt; ?> 
</div>
<?php if ($this->interactive): ?>

<!--[if gte IE 5]>
<script type="text/javascript" event="FSCommand(command,args)" for="<?php echo $this->flashId; ?>Com">
<?php echo $this->flashId; ?>Com_DoFSCommand(command, args);
</script>
<![endif]-->
<?php endif; ?>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
<?php if ($this->interactive): ?>
function <?php echo $this->flashId; ?>Com_DoFSCommand(command, args)
{
<?php echo $this->fsCommand; ?> 
}
<?php endif; ?>
UFO.launch("<?php echo $this->flashId; ?>", {
<?php if ($this->interactive): ?>
  id: "<?php echo $this->flashId; ?>Com",
  name: "<?php echo $this->flashId; ?>Com",
  swliveconnect: "true",
<?php endif; ?>
  movie: "<?php echo $this->href; ?>",
  width: "<?php echo $this->width; ?>",
  height: "<?php echo $this->height; ?>",
<?php if ($this->transparent): ?>
  wmode: "transparent",
<?php endif; ?>
  allowfullscreen: "true",
  flashvars: "<?php echo $this->flashvars; ?>",
  majorversion: "<?php echo $this->version; ?>",
  build: "<?php echo $this->build; ?>"
});
//--><!]]>
</script>

</div>
<?php if (!$this->searchable): ?>
<!-- indexer::continue -->
<?php endif; ?>
