<!DOCTYPE html>
<html>
<head>
<meta charset="<?php echo $this->charset; ?>" />
<title><?php echo $this->title; ?> - Contao Open Source CMS <?php echo VERSION; ?></title>
<base href="<?php echo $this->base; ?>" />
<link rel="stylesheet" href="<?php
  $objCombiner = new Combiner();
  $objCombiner->add('system/themes/'. $this->theme .'/basic.css');
  $objCombiner->add('system/themes/'. $this->theme .'/page.css');
  if ($this->isMac) {
    $objCombiner->add('system/themes/'. $this->theme .'/macfixes.css');
  }
  echo $objCombiner->getCombinedFile();
?>" media="all" />
<!--[if lte IE 7]><link rel="stylesheet" href="<?php echo TL_SCRIPT_URL; ?>system/themes/<?php echo $this->theme; ?>/iefixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
<!--[if gte IE 8]><link rel="stylesheet" href="<?php echo TL_SCRIPT_URL; ?>system/themes/<?php echo $this->theme; ?>/ie8fixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
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
</head>
<body>

<div id="container">
<div id="main">

<h1><?php echo $this->headline; ?></h1>
<select onchange="setPage(this.value);"><option value="">-</option><?php echo $this->options; ?></select>

</div>
</div>

</body>
</html>