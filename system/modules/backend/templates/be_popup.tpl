<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
<base href="<?php echo $this->base; ?>"></base>
<title><?php echo $this->title; ?> :: TYPOlight Open Source CMS <?php echo VERSION; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/basic.css" media="screen" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/popup.css" media="screen" />
<!--[if lte IE 7]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/iefixes.css" media="screen" /><![endif]-->
<!--[if IE 8]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/ie8fixes.css" media="screen" /><![endif]-->
</head>
<body>

<div id="container">
<div id="main">

<h1>Image preview</h1>

<h2><?php echo $this->headline; ?></h2>

<table cellspacing="3" cellpadding="0" class="tl_help_table" summary="File preview">
  <tr>
    <td class="tl_label"><?php echo $this->label_ctime; ?>:</td>
    <td><?php echo $this->ctime; ?></td>
  </tr>
  <tr>
    <td class="tl_label"><?php echo $this->label_mtime; ?>:</td>
    <td><?php echo $this->mtime; ?></td>
  </tr>
  <tr>
    <td class="tl_label"><?php echo $this->label_atime; ?>:</td>
    <td><?php echo $this->atime; ?></td>
  </tr>
  <tr>
    <td class="tl_label"><?php echo $this->label_filesize; ?>:</td>
    <td><?php echo $this->filesize; ?></td>
  </tr>
  <tr>
    <td class="tl_label"><?php echo $this->label_path; ?>:</td>
    <td><?php echo $this->path; ?></td>
  </tr>
<?php if ($this->src): ?>
  <tr>
    <td class="tl_label"><?php echo $this->label_imagesize; ?>:</td>
    <td><?php echo $this->width; ?> x <?php echo $this->height; ?></td>
  </tr>
<?php endif; ?>
</table>

<div id="download">
<img src="system/themes/<?php echo $this->theme; ?>/images/<?php echo $this->icon; ?>" width="18" height="18" alt="" class="mime_icon" /> <a href="<?php echo $this->href; ?>"><?php echo $this->download; ?></a>
</div>
<?php if ($this->isImage): ?>

<div id="preview">
<img src="<?php echo $this->src; ?>" width="<?php echo $this->width; ?>" height="<?php echo $this->height; ?>" alt="" />
</div>
<?php endif; ?>

</div>
</div>

</body>
</html>