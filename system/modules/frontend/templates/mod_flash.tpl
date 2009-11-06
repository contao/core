
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<div id="<?php echo $this->flashId; ?>">
<?php echo $this->alt; ?> 
</div>

<!-- indexer::stop -->
<?php if ($this->interactive): ?>
<!--[if gte IE 5]><script type="text/javascript" event="FSCommand(command,args)" for="<?php echo $this->flashId; ?>"><?php echo $this->flashId; ?>_DoFSCommand(command, args);</script><![endif]-->
<?php endif; ?>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
<?php if ($this->interactive): ?>
function <?php echo $this->flashId; ?>_DoFSCommand(command, args) {
<?php echo $this->fsCommand; ?> 
}
<?php endif; ?>
new Swiff("<?php echo $this->href; ?>", {
  id: "<?php echo $this->flashId; ?>",
  width: <?php echo $this->width; ?>,
  height: <?php echo $this->height; ?>,
<?php if ($this->interactive): ?>
  properties : {
    name: "<?php echo $this->flashId; ?>"
  },
<?php endif; ?>
  params : {
<?php if (!$this->transparent): ?>
    wMode: "window",
<?php endif; ?>
    flashvars: "<?php echo $this->flashvars; ?>"
  }
}).replaces($('<?php echo $this->flashId; ?>'));
//--><!]]>
</script>
<!-- indexer::continue -->

</div>
