
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<div id="<?php echo $this->flashId; ?>">
<?php echo $this->alt; ?> 
</div>

<!-- indexer::stop -->
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
function <?php echo $this->flashId; ?>Com_DoFSCommand(command, args) {
<?php echo $this->fsCommand; ?> 
}
<?php endif; ?>
swfobject.embedSWF("<?php echo $this->href; ?>", "<?php echo $this->flashId; ?>", "<?php echo $this->width; ?>", "<?php echo $this->height; ?>", "<?php echo $this->version; ?>", false, false, { <?php if ($this->transparent): ?>wmode: "transparent", <?php endif; ?>allowfullscreen: "true", flashvars: "<?php echo $this->flashvars; ?>"<?php if ($this->interactive): ?>, swliveconnect: "true"<?php endif; ?> }<?php if ($this->interactive): ?>, { id: "<?php echo $this->flashId; ?>Com", name: "<?php echo $this->flashId; ?>Com" }<?php endif; ?>);
//--><!]]>
</script>
<!-- indexer::continue -->

</div>
