<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
<base href="<?php echo $this->base; ?>"></base>
<title><?php echo $this->title; ?> - Contao Open Source CMS <?php echo VERSION; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<link type="text/css" rel="stylesheet" href="<?php
  $objCombiner = new CssCombiner();
  $objCombiner->add('system/themes/'. $this->theme .'/basic.css');
  $objCombiner->add('system/themes/'. $this->theme .'/main.css');
  $objCombiner->add('plugins/calendar/css/calendar.css', CALENDAR);
  if ($this->be27) {
    $objCombiner->add('system/themes/'. $this->theme .'/be27.css');
  }
  if ($this->isMac) {
    $objCombiner->add('system/themes/'. $this->theme .'/macfixes.css');
  }
  echo $objCombiner->generate();
?>" media="all" />
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
<script type="text/javascript">
<!--//--><![CDATA[//><!--
var CONTAO_THEME = '<?php echo $this->theme; ?>';
var CONTAO_COLLAPSE = '<?php echo $this->collapseNode; ?>';
var CONTAO_EXPAND = '<?php echo $this->expandNode; ?>';
//--><!]]>
</script>
<script type="text/javascript" src="<?php
  $objCombiner = new JsCombiner();
  $objCombiner->add('plugins/mootools/mootools-core.js', MOOTOOLS_CORE);
  $objCombiner->add('plugins/mootools/mootools-more.js', MOOTOOLS_MORE);
  $objCombiner->add('plugins/calendar/js/calendar.js', CALENDAR);
  $objCombiner->add('contao/contao.js');
  $objCombiner->add('system/themes/'. $this->theme .'/hover.js');
  echo $objCombiner->generate();
?>"></script>
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