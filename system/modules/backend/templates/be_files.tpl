<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
<base href="<?php echo $this->base; ?>"></base>
<title><?php echo $this->title; ?> :: Contao Open Source CMS <?php echo VERSION; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/basic.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/main.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="plugins/calendar/css/calendar.css?<?php echo CALENDAR; ?>" media="screen" />
<?php if ($this->be27): ?>
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/be27.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" />
<?php endif; ?>
<?php if ($this->isMac): ?>
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/macfixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" />
<?php endif; ?>
<!--[if lte IE 7]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/iefixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
<!--[if IE 8]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/ie8fixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
<?php echo $this->stylesheets; ?>
<style type="text/css" media="screen">
<!--/*--><![CDATA[/*><!--*/
#container {
  margin:0 auto;
  padding:12px 0;
  width:750px;
}
#tl_helpBox {
  margin-left:-353px;
}
/*]]>*/-->
</style>
<script type="text/javascript" src="plugins/mootools/mootools-core.js?<?php echo MOOTOOLS_CORE; ?>"></script>
<script type="text/javascript" src="plugins/mootools/mootools-more.js?<?php echo MOOTOOLS_MORE; ?>"></script>
<script type="text/javascript" src="plugins/calendar/js/calendar.js?<?php echo CALENDAR; ?>"></script>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
var CONTAO_THEME = '<?php echo $this->theme; ?>';
var CONTAO_COLLAPSE = '<?php echo $this->collapseNode; ?>';
var CONTAO_EXPAND = '<?php echo $this->expandNode; ?>';
//--><!]]>
</script>
<script type="text/javascript" src="contao/contao.js?<?php echo VERSION .'.'. BUILD; ?>"></script>
<script type="text/javascript" src="system/themes/<?php echo $this->theme; ?>/hover.js?<?php echo VERSION .'.'. BUILD; ?>"></script>
<?php echo $this->javascripts; ?>
<?php echo $this->rteConfig; ?>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
function unloadHandler(e) {
  self.opener.Backend.checkPopup();
}
//--><!]]>
</script>
</head>
<body onunload="unloadHandler(this)">

<div id="container">
<div id="main">

<h1 class="main_headline"><?php echo $this->headline; ?></h1>
<?php if ($this->error): ?>

<p class="tl_gerror"><?php echo $this->error; ?></p>
<?php endif; echo $this->main; ?>

</div>

<div class="clear"></div>

</div>
<?php if ($this->pageOffset): ?>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
Backend.vScrollTo(<?php echo $this->pageOffset; ?>);
//--><!]]>
</script>
<?php setcookie('BE_PAGE_OFFSET', 0, 0, '/'); endif; ?>

</body>
</html>