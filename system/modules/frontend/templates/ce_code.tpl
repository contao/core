
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<div>
<pre id="<?php echo $this->preId; ?>" class="<?php echo $this->preClass; ?>:nogutter">
<?php echo $this->code; ?>
</pre>
</div>
<?php if ($this->js): ?>

<!-- indexer::stop -->
<script type="text/javascript">
<!--//--><![CDATA[//><!--
dp.SyntaxHighlighter.ClipboardSwf = 'plugins/dpsyntax/clipboard.swf';
dp.SyntaxHighlighter.HighlightAll('<?php echo $this->preId; ?>');
//--><!]]>
</script>
<!-- indexer::continue -->
<?php endif; ?>

</div>
