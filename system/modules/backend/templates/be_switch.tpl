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
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/switch.css" media="screen" />
<!--[if IE]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/iefixes.css" media="screen" /><![endif]-->
</head>
<body>

<div id="container">

<div id="left">
<p><?php echo $this->fePreview; ?></p>
</div>

<div id="right">
<form action="<?php echo $this->action; ?>" method="post">
<div class="formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_switch" />
<?php if ($this->isAdmin): ?>
<label for="ctrl_user"><?php echo $this->feUser; ?>:</label>
<select name="user" id="ctrl_user" class="tl_select">
<?php foreach ($this->users as $id=>$name): ?>
  <option value="<?php echo $id; ?>"<?php if ($id == $this->user): ?> selected="selected"<?php endif; ?>><?php echo $name; ?></option>
<?php endforeach; ?>
</select>
<?php endif; ?>
<label for="ctrl_unpublished"><?php echo $this->hiddenElements; ?>:</label>
<select name="unpublished" id="ctrl_unpublished" class="tl_select">
  <option value="hide"><?php echo $this->lblHide; ?></option>
  <option value="show"<?php if ($this->show) echo ' selected="selected"'; ?>><?php echo $this->lblShow; ?></option>
</select>
<input type="submit" class="tl_submit" value="<?php echo $this->apply; ?>" />
<input type="button" class="tl_submit" value="<?php echo $this->reload; ?>" onclick="parent.frames[1].location.reload();" />
</div>
</form>
</div>

</div>
<?php if ($this->update): ?>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
parent.frames[1].location.reload();
//--><!]]>
</script>
<?php endif; ?>

</body>
</html>