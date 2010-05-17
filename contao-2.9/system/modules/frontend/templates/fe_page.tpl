<?php echo $this->doctype; ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
<base href="<?php echo $this->base; ?>"></base>
<title><?php echo $this->pageTitle; ?> - <?php echo $this->mainTitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="description" content="<?php echo $this->description; ?>" />
<meta name="keywords" content="<?php echo $this->keywords; ?>" />
<?php echo $this->robots; ?>
<?php echo $this->framework; ?>
<?php echo $this->stylesheets; ?>
<script type="text/javascript" src="plugins/mootools/mootools-core.js"></script>
<script type="text/javascript" src="plugins/mootools/mootools-more.js"></script>
<?php echo $this->head; ?>
</head>

<body id="top"<?php if ($this->class): ?> class="<?php echo $this->class; ?>"<?php endif; if ($this->onload): ?> onload="<?php echo $this->onload; ?>"<?php endif; ?>>
<div id="wrapper">
<?php if ($this->header): ?>

<div id="header">
<div class="inside">
<?php echo $this->header; ?> 
</div>
</div>
<?php endif; ?>
<?php echo $this->getCustomSections('before'); ?>

<div id="container">
<?php if ($this->left): ?>

<div id="left">
<div class="inside">
<?php echo $this->left; ?> 
</div>
</div>
<?php endif; ?>
<?php if ($this->right): ?>

<div id="right">
<div class="inside">
<?php echo $this->right; ?> 
</div>
</div>
<?php endif; ?>

<div id="main">
<div class="inside">
<?php echo $this->main; ?> 
</div>
<?php echo $this->getCustomSections('main'); ?> 
<div id="clear"></div>
</div>

</div>
<?php echo $this->getCustomSections('after'); ?>
<?php if ($this->footer): ?>

<div id="footer">
<div class="inside">
<?php echo $this->footer; ?> 
</div>
</div>
<?php endif; ?>

<!-- indexer::stop -->
<img src="<?php echo $this->base; ?>cron.php" alt="" class="invisible" />
<!-- indexer::continue -->
<?php echo $this->mootools; ?>

</div>
<?php if ($this->urchinId): ?>

<script type="text/javascript" src="<?php echo $this->urchinUrl; ?>"></script>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
try {
var pageTracker = _gat._getTracker("<?php echo $this->urchinId; ?>");
pageTracker._trackPageview();
} catch(err) {}
//--><!]]>
</script>

<?php endif; ?>
</body>
</html>