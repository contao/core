<?php echo $this->doctype; ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->language; ?>">
<!--

	This website is powered by TYPOlight :: open source web content management system
	TYPOlight was developed by Leo Feyer (leo@typolight.org) :: released under GNU/LGPL
	Visit the project website http://www.typolight.org for more information

//-->
<head>
<base href="<?php echo $this->base; ?>" />
<title><?php echo $this->mainTitle; ?> - <?php echo $this->pageTitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<meta name="description" content="<?php echo $this->description; ?>" />
<meta name="keywords" content="<?php echo $this->keywords; ?>" />
<?php echo $this->robots; ?>
<?php echo $this->framework; ?>
<link rel="stylesheet" href="plugins/slimbox/css/slimbox.css" type="text/css" media="screen" />
<?php echo $this->stylesheets; ?>
<script type="text/javascript" src="plugins/mootools/mootools.js"></script>
<script type="text/javascript" src="plugins/slimbox/js/slimbox.js"></script>
<?php echo $this->head; ?>
</head>

<body<?php echo $this->onload; ?> id="top">
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
var pageTracker = _gat._getTracker("<?php echo $this->urchinId; ?>");
pageTracker._initData();
pageTracker._trackPageview();
//--><!]]>
</script>

<?php endif; ?>
</body>
</html>