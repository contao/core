<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
<base href="<?php echo $this->base; ?>"></base>
<title><?php echo $this->title; ?> :: Contao Open Source CMS <?php echo VERSION; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/basic.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/page.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" />
<?php if ($this->isMac): ?>
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/macfixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" />
<?php endif; ?>
<!--[if lte IE 7]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/iefixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
<!--[if IE 8]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/ie8fixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
<script type="text/javascript" src="plugins/mootools/mootools-core.js?<?php echo MOOTOOLS_CORE; ?>"></script>
<script type="text/javascript" src="plugins/mootools/mootools-more.js?<?php echo MOOTOOLS_MORE; ?>"></script>
<script type="text/javascript" src="contao/contao.js?<?php echo VERSION .'.'. BUILD; ?>"></script>
<script type="text/javascript" src="system/themes/<?php echo $this->theme; ?>/hover.js?<?php echo VERSION .'.'. BUILD; ?>"></script>
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