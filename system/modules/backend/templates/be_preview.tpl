<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->language; ?>">
<!--

	This website was built with TYPOlight :: open source web content management system
	TYPOlight was developed by Leo Feyer (leo@typolight.org) :: released under GNU/GPL
	Visit project page http://www.typolight.org for more information

//-->
<head>
<title><?php echo $this->title; ?> :: TYPOlight webCMS <?php echo VERSION; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
</head>
<frameset rows="31,*" frameborder="0" framespacing="0">
<frame src="switch.php" name="switch" frameborder="0" scrolling="no" noresize="noresize" />
<frame src="<?php echo $this->base . $this->site; ?>" name="website" frameborder="0" noresize="noresize" />
</frameset>
</html>