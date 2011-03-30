<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
<base href="<?php echo $this->base; ?>"></base>
<title><?php echo $this->title; ?> - Contao Open Source CMS <?php echo VERSION; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<link type="text/css" rel="stylesheet" href="<?php
  $objCombiner = new Combiner();
  $objCombiner->add('system/themes/'. $this->theme .'/basic.css');
  $objCombiner->add('system/themes/'. $this->theme .'/page.css');
  if ($this->isMac) {
    $objCombiner->add('system/themes/'. $this->theme .'/macfixes.css');
  }
  echo $objCombiner->getCombinedFile();
?>" media="all" />
<!--[if lte IE 7]><link type="text/css" rel="stylesheet" href="<?php echo TL_SCRIPT_URL; ?>system/themes/<?php echo $this->theme; ?>/iefixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
<!--[if gte IE 8]><link type="text/css" rel="stylesheet" href="<?php echo TL_SCRIPT_URL; ?>system/themes/<?php echo $this->theme; ?>/ie8fixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
<script type="text/javascript" src="<?php
  $objCombiner = new Combiner();
  $objCombiner->add('plugins/mootools/mootools-core.js', MOOTOOLS_CORE);
  $objCombiner->add('plugins/mootools/mootools-more.js', MOOTOOLS_MORE);
  $objCombiner->add('contao/contao.js');
  $objCombiner->add('system/themes/'. $this->theme .'/hover.js');
  echo $objCombiner->getCombinedFile();
?>"></script>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
function setPage(p) {
  self.opener.$(self.opener.Backend.currentId).value = p;
  self.close();
}
document.onLoad = self.focus();
//--><!]]>
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