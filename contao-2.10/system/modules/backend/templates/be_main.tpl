<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
<base href="<?php echo $this->base; ?>"></base>
<title><?php echo $this->title; ?> - Contao Open Source CMS <?php echo VERSION; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<link type="text/css" rel="stylesheet" href="<?php
  $objCombiner = new Combiner();
  $objCombiner->add('plugins/mediabox/css/mediabox_white.css', MEDIABOX);
  $objCombiner->add('system/themes/'. $this->theme .'/basic.css');
  $objCombiner->add('system/themes/'. $this->theme .'/main.css');
  $objCombiner->add('plugins/calendar/css/calendar.css', CALENDAR);
  if ($this->be27) {
    $objCombiner->add('system/themes/'. $this->theme .'/be27.css');
  }
  if ($this->isMac) {
    $objCombiner->add('system/themes/'. $this->theme .'/macfixes.css');
  }
  echo $objCombiner->getCombinedFile();
?>" media="all" />
<!--[if lte IE 7]><link type="text/css" rel="stylesheet" href="<?php echo TL_SCRIPT_URL; ?>system/themes/<?php echo $this->theme; ?>/iefixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
<!--[if gte IE 8]><link type="text/css" rel="stylesheet" href="<?php echo TL_SCRIPT_URL; ?>system/themes/<?php echo $this->theme; ?>/ie8fixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
<?php echo $this->stylesheets; ?>
<script type="text/javascript">
/*<![CDATA[*/
var CONTAO_THEME = '<?php echo $this->theme; ?>';
var CONTAO_COLLAPSE = '<?php echo $this->collapseNode; ?>';
var CONTAO_EXPAND = '<?php echo $this->expandNode; ?>';
var CONTAO_SCRIPT_URL = '<?php echo TL_SCRIPT_URL; ?>';
/*]]>*/
</script>
<script type="text/javascript" src="<?php
  $objCombiner = new Combiner();
  $objCombiner->add('plugins/mootools/mootools-core.js', MOOTOOLS_CORE);
  $objCombiner->add('plugins/mootools/mootools-more.js', MOOTOOLS_MORE);
  $objCombiner->add('plugins/calendar/js/calendar.js', CALENDAR);
  $objCombiner->add('contao/contao.js');
  $objCombiner->add('system/themes/'. $this->theme .'/hover.js');
  $objCombiner->add('plugins/mediabox/js/mediabox.js', MEDIABOX);
  echo $objCombiner->getCombinedFile();
?>"></script>
<?php echo $this->javascripts; ?>
<?php echo $this->rteConfig; ?>
</head>
<body id="top">

<div id="header">

<h1>Contao Open Source CMS <?php echo VERSION . '.' . BUILD; ?></h1>

<div>
<span class="header_user_container"><a href="<?php echo $this->base; ?>contao/main.php?do=login" class="header_user" title="<?php echo $this->account; ?>"><?php echo $this->username; ?></a> &nbsp; :: &nbsp; </span>
<span class="header_preview_container"><a href="contao/preview.php?site=<?php echo $this->frontendFile; ?>" onclick="window.open(this.href); return false;" class="header_preview" title="<?php echo $this->preview; ?>" accesskey="f"><?php echo $this->preview; ?></a> &nbsp; :: &nbsp; </span>
<span class="header_home_container"><a href="<?php echo $this->base; ?>contao/main.php" class="header_home" title="<?php echo $this->home; ?>" accesskey="h"><?php echo $this->home; ?></a> &nbsp; :: &nbsp; </span>
<span class="header_logout_container"><a href="<?php echo $this->base; ?>contao/index.php" class="header_logout" title="<?php echo $this->logout; ?>" accesskey="q"><?php echo $this->logout; ?></a></span>
</div>

</div>

<div id="container">
<div id="left">

<div id="tl_navigation">

<h1><?php echo $this->backendModules; ?></h1>

<a href="<?php echo $this->request; ?>#skipNavigation" class="invisible" title="<?php echo specialchars($this->skipNavigation); ?>"></a>
<ul class="tl_level_1">
<?php foreach ($this->modules as $strGroup=>$arrModules): ?>
<li class="tl_level_1_group"><a href="<?php echo $arrModules['href']; ?>" title="<?php echo $arrModules['title']; ?>" onclick="return AjaxRequest.toggleNavigation(this, '<?php echo $strGroup; ?>');"><img src="<?php echo TL_FILES_URL; ?>system/themes/<?php echo $this->theme; ?>/images/<?php echo $arrModules['icon']; ?>" width="16" height="16" alt="" /><?php echo $arrModules['label']; ?></a></li>
<?php if ($arrModules['modules']): ?>
<li class="tl_parent" id="<?php echo $strGroup; ?>">
<ul class="tl_level_2">
<?php foreach ($arrModules['modules'] as $strModule=>$arrConfig): ?>
  <li><a href="contao/main.php?do=<?php echo $strModule; ?>" class="<?php echo $arrConfig['class']; ?>" title="<?php echo $arrConfig['title']; ?>"<?php echo $arrConfig['icon']; ?>><?php echo $arrConfig['label']; ?></a></li>
<?php endforeach; ?>
</ul>
</li>
<?php endif; endforeach; ?>
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
<span class="footer_project_container"><a href="http://www.contao.org" class="footer_project" onclick="window.open(this.href); return false;">Contao Open Source CMS <?php echo VERSION; ?></a> &nbsp; :: &nbsp; </span>
<span class="footer_top_container"><a href="<?php echo $this->request; ?>#top" class="footer_top" title="<?php echo $this->backToTop; ?>" accesskey="t"><?php echo $this->top; ?></a> &nbsp; :: &nbsp; </span>
<span class="footer_preview_container"><a href="contao/preview.php?site=<?php echo $this->frontendFile; ?>" onclick="window.open(this.href); return false;" class="footer_preview" title="<?php echo $this->preview; ?>"><?php echo $this->preview; ?></a></span>
</div>

</div>
<?php if ($this->pageOffset): ?>

<script type="text/javascript">
/*<![CDATA[*/
Backend.vScrollTo(<?php echo $this->pageOffset; ?>);
/*]]>*/
</script>
<?php setcookie('BE_PAGE_OFFSET', 0, 0, '/'); endif; ?>

<script type="text/javascript">
/*<![CDATA[*/
Mediabox.scanPage = function() {
  var links = $$('a').filter(function(el) {
    return el.rel && el.rel.test(/^lightbox/i);
  });
  var options = {
    'showCaption': false,
    'showCounter': false
  };
  $$(links).mediabox(options, null, function(el) {
    var rel0 = this.rel.replace(/[[]|]/gi,' ');
    var relsize = rel0.split(' ');
    return (this == el) || ((this.rel.length > 8) && el.rel.match(relsize[1]));
  });
};
Mediabox.enableFiletreeReload = function() {
  window.addEvent('mb_close', function() {
    AjaxRequest.reloadFiletrees();
    window.removeEvents('mb_close');
  });
};
window.addEvent('domready', Mediabox.scanPage);
window.addEvent('ajax_change', Mediabox.scanPage);
/*]]>*/
</script>
<?php echo $this->mootools; ?>

</body>
</html>