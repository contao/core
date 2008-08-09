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
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/main.css" media="screen" />
<?php if ($this->isMac): ?>
<link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/macfixes.css" media="screen" />
<?php endif; ?>
<!--[if IE]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/iefixes.css" media="screen" /><![endif]-->
<link rel="stylesheet" type="text/css" href="plugins/calendar/calendar.css" media="screen" />
<?php echo $this->stylesheets; ?>
<style type="text/css" media="screen">
<!--/*--><![CDATA[/*><!--*/
#container { margin:0 auto; padding:12px 0px; width:750px; }
/*]]>*/-->
</style>
<script type="text/javascript" src="plugins/mootools/mootools.js"></script>
<script type="text/javascript" src="plugins/calendar/calendar.js"></script>
<script type="text/javascript" src="typolight/typolight.js"></script>
<script type="text/javascript" src="system/themes/<?php echo $this->theme; ?>/hover.js"></script>
<?php echo $this->javascripts; ?>
<?php echo $this->rteConfig; ?>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
function unloadHandler(e)
{
	self.opener.Backend.checkPopup();
}
//--><!]]>
</script>
</head>
<body onunload="unloadHandler(this)">

<div style="display:inline;">
<a name="top" accesskey="t"></a>
</div>

<div id="container">

<div id="main">

<h1 class="main_headline"><?php echo $this->headline; ?></h1>
<?php if ($this->error): ?>

<p class="tl_gerror"><?php echo $this->error; ?></p>
<?php endif; echo $this->main; ?>

</div>

<div class="clear"></div>

</div><?php if ($this->pageOffset): ?>  

<script type="text/javascript">
<!--//--><![CDATA[//><!--
Backend.vScrollTo(<?php echo $this->pageOffset; ?>);
//--><!]]>
</script><?php setcookie('BE_PAGE_OFFSET', 0, 0, '/'); endif; ?> 

</body>
</html>