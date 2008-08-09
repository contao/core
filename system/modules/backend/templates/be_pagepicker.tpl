<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->language; ?>">
<!--

	This website was built with TYPOlight :: open source web content management system
	TYPOlight was developed by Leo Feyer (leo@typolight.org) :: released under GNU/GPL
	Visit project page http://www.typolight.org for more information

//-->
<head>
<base href="<?php echo $this->base; ?>" />
<title><?php echo $this->title; ?> :: TYPOlight webCMS <?php echo VERSION; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/basic.css" media="screen" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/page.css" media="screen" />
<?php if ($this->isMac): ?>
<link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/macfixes.css" media="screen" />
<?php endif; ?>
<!--[if IE]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/iefixes.css" media="screen" /><![endif]-->
<script type="text/javascript" src="plugins/mootools/mootools.js"></script>
<script type="text/javascript" src="typolight/typolight.js"></script>
<script type="text/javascript" src="system/themes/<?php echo $this->theme; ?>/hover.js"></script>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
document.onLoad = self.focus();

function setPage(p)
{
    self.opener.$(self.opener.Backend.currentId).value = p;
    self.close();
}
//--><!]]>
</script>
</head>

<body>
<div id="container">

<div id="main">

<h1><?php echo $this->headline; ?></h1>
<select onchange="setPage(this.value);"><?php echo $this->options; ?></select>

</div>

</div>
</body>
</html>