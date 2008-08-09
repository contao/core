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
<script type="text/javascript" src="plugins/mootools/mootools.js"></script>
<script type="text/javascript" src="plugins/calendar/calendar.js"></script>
<script type="text/javascript" src="typolight/typolight.js"></script>
<script type="text/javascript" src="system/themes/<?php echo $this->theme; ?>/hover.js"></script>
<?php echo $this->javascripts; ?>
<?php echo $this->rteConfig; ?>
</head>
<body>

<div style="display:inline;">
<a name="top" accesskey="t"></a>
</div>

<div id="header">

<h1>TYPOlight webCMS <?php echo VERSION . '.' . BUILD; ?></h1>

<div>
<span class="header_user_container"><a href="<?php echo $this->base; ?>typolight/main.php?do=login" class="header_user" title="<?php echo $this->account; ?>"><?php echo $this->username; ?></a> &nbsp; :: &nbsp; </span>
<span class="header_preview_container"><a href="typolight/preview.php?site=<?php echo $this->frontendFile; ?>" onclick="this.blur(); window.open(this.href); return false;" class="header_preview" title="<?php echo $this->preview; ?>" accesskey="f"><?php echo $this->preview; ?></a> &nbsp; :: &nbsp; </span>
<span class="header_logout_container"><a href="<?php echo $this->base; ?>typolight/index.php" class="header_logout" title="<?php echo $this->logout; ?>" accesskey="x"><?php echo $this->logout; ?></a></span>
</div>

</div>

<div id="container">

<div id="left">

<div id="tl_navigation">

<h1><?php echo $this->backendModules; ?></h1>

<a href="<?php echo $this->request; ?>#skipNavigation" class="invisible" title="<?php echo specialchars($this->skipNavigation); ?>"></a>
<ul class="tl_level_1"><?php foreach ($this->modules as $strGroup=>$arrModules): ?> 
<li class="tl_level_1_group"><a href="<?php echo $arrModules['href']; ?>" onclick="return AjaxRequest.toggleNavigation(this, '<?php echo $strGroup; ?>');"><img src="system/themes/<?php echo $this->theme; ?>/images/<?php echo $arrModules['icon']; ?>" alt="" /></a><?php echo $arrModules['label']; ?></li><?php if ($arrModules['modules']): ?> 
<li class="tl_parent" id="<?php echo $strGroup; ?>">
<ul class="tl_level_2"><?php foreach ($arrModules['modules'] as $strModule=>$arrConfig): ?> 
  <li><a href="typolight/main.php?do=<?php echo $strModule; ?>" class="<?php echo $arrConfig['class']; ?>" title="<?php echo $arrConfig['title']; ?>"<?php echo $arrConfig['icon']; ?> onclick="this.blur();"><?php echo $arrConfig['label']; ?></a></li><?php endforeach; ?> 
</ul>
</li><?php endif; endforeach; ?> 
</ul>
<a id="skipNavigation" class="invisible" title="Skip navigation"></a>

</div>

</div>

<div id="main">

<h1 class="main_headline"><?php echo $this->headline; ?></h1>
<?php if ($this->error): ?>

<p class="tl_gerror"><?php echo $this->error; ?></p>
<?php endif; echo $this->main; ?>

</div>

<div class="clear"></div>

</div>

<div id="footer">

<div>
<span class="footer_project_container"><a href="http://www.typolight.org" class="footer_project" onclick="this.blur(); window.open(this.href); return false;">TYPOlight webCMS <?php echo VERSION; ?></a> &nbsp; :: &nbsp; </span>
<span class="footer_top_container"><a href="<?php echo $this->request; ?>#top" class="footer_top"><?php echo $this->top; ?></a> &nbsp; :: &nbsp; </span>
<span class="footer_preview_container"><a href="<?php echo $this->base . $this->frontendFile; ?>" onclick="this.blur(); window.open(this.href); return false;" class="footer_preview" title="<?php echo $this->preview; ?>"><?php echo $this->preview; ?></a></span>
</div>

</div><?php if ($this->pageOffset): ?>  

<script type="text/javascript">
<!--//--><![CDATA[//><!--
Backend.vScrollTo(<?php echo $this->pageOffset; ?>);
//--><!]]>
</script><?php setcookie('BE_PAGE_OFFSET', 0, 0, '/'); endif; ?> 

</body>
</html>