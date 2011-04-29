<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>">
<head>
<meta charset="<?php echo $this->charset; ?>">
<title><?php echo $this->title; ?> - Contao Open Source CMS <?php echo VERSION; ?></title>
<base href="<?php echo $this->base; ?>">
<link rel="stylesheet" href="<?php
  $objCombiner = new Combiner();
  $objCombiner->add('system/themes/'. $this->theme .'/basic.css');
  $objCombiner->add('system/themes/'. $this->theme .'/main.css');
  $objCombiner->add('plugins/calendar/css/calendar.css', CALENDAR);
  if ($this->be27) {
    $objCombiner->add('system/themes/'. $this->theme .'/be27.css');
  }
  echo $objCombiner->getCombinedFile();
?>" media="all">
<!--[if IE]><link rel="stylesheet" href="<?php echo TL_SCRIPT_URL; ?>system/themes/<?php echo $this->theme; ?>/iefixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen"><![endif]-->
<?php echo $this->stylesheets; ?>
<style media="screen">
#container {
  margin:0 auto;
  padding:12px 0;
  width:750px;
}
#tl_helpBox {
  margin-left:-353px;
}
</style>
<script>
var CONTAO_THEME = '<?php echo $this->theme; ?>';
var CONTAO_COLLAPSE = '<?php echo $this->collapseNode; ?>';
var CONTAO_EXPAND = '<?php echo $this->expandNode; ?>';
var CONTAO_SCRIPT_URL = '<?php echo TL_SCRIPT_URL; ?>';
</script>
<script src="<?php
  $objCombiner = new Combiner();
  $objCombiner->add('plugins/mootools/mootools-core.js', MOOTOOLS_CORE);
  $objCombiner->add('plugins/mootools/mootools-more.js', MOOTOOLS_MORE);
  $objCombiner->add('plugins/calendar/js/calendar.js', CALENDAR);
  $objCombiner->add('contao/contao.js');
  $objCombiner->add('system/themes/'. $this->theme .'/hover.js');
  echo $objCombiner->getCombinedFile();
?>"></script>
<?php echo $this->javascripts; ?>
<!--[if lt IE 9]><script src="<?php echo TL_PLUGINS_URL; ?>plugins/html5shim/html5.js?<?php echo HTML5SHIM; ?>"></script><![endif]-->
<?php echo $this->rteConfig; ?>
<script>
parent.Mediabox.enableFiletreeReload();
</script>
</head>
<body class="{{ua::class}}">

<div id="container">
<div id="main">

<h1 class="main_headline"><?php echo $this->headline; ?></h1>
<?php if ($this->error): ?>

<p class="tl_gerror"><?php echo $this->error; ?></p>
<?php endif; ?>
<?php echo $this->main; ?>

</div>

<div class="clear"></div>

</div>
<?php if ($this->pageOffset): ?>

<script>
Backend.vScrollTo(<?php echo $this->pageOffset; ?>);
</script>
<?php setcookie('BE_PAGE_OFFSET', 0, 0, '/'); endif; ?>
<?php echo $this->mootools; ?>

</body>
</html>