<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>">
<head>
<meta charset="<?php echo $this->charset; ?>">
<title><?php echo $this->title; ?> - Contao Open Source CMS <?php echo VERSION; ?></title>
<base href="<?php echo $this->base; ?>">
<link rel="stylesheet" href="<?php
  $objCombiner = new Combiner();
  $objCombiner->add('system/themes/'. $this->theme .'/basic.css');
  $objCombiner->add('system/themes/'. $this->theme .'/page.css');
  echo $objCombiner->getCombinedFile();
?>" media="all">
<!--[if lt IE 8]><link rel="stylesheet" href="<?php echo TL_SCRIPT_URL; ?>system/themes/<?php echo $this->theme; ?>/iefixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen"><![endif]-->
<!--[if gt IE 7]><link rel="stylesheet" href="<?php echo TL_SCRIPT_URL; ?>system/themes/<?php echo $this->theme; ?>/ie8fixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen"><![endif]-->
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
  $objCombiner->add('contao/contao.js');
  $objCombiner->add('system/themes/'. $this->theme .'/hover.js');
  echo $objCombiner->getCombinedFile();
?>"></script>
<script>
function setPage(p) {
  self.opener.$(self.opener.Backend.currentId).value = p;
  self.close();
}
document.onLoad = self.focus();
</script>
<!--[if lt IE 9]><script src="<?php echo TL_PLUGINS_URL; ?>plugins/html5shim/html5.js?<?php echo HTML5SHIM; ?>"></script><![endif]-->
<!--[if lt IE 9]><script src="<?php echo TL_PLUGINS_URL; ?>plugins/selectivizr/selectivizr.js?<?php echo SELECTIVIZR; ?>"></script><![endif]-->
</head>
<body class="{{ua::class}}">

<div id="container">
<div id="main">

<h1><?php echo $this->headline; ?></h1>
<select onchange="setPage(this.value);"><option value="">-</option><?php echo $this->options; ?></select>

</div>
</div>

</body>
</html>