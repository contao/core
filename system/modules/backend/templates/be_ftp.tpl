<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->language; ?>">
<!--

	This website was built with TYPOlight :: open source web content management system
	TYPOlight was developed by Leo Feyer (leo@typolight.org) :: released under GNU/GPL
	Visit project page http://www.typolight.org for more information

//-->
<head>
<base href="<?php echo $this->base; ?>" />
<title>TYPOlight webCMS <?php echo VERSION; ?> :: Install Tool</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/basic.css" media="screen" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/install.css" media="screen" />
<?php if ($this->isMac): ?><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/macfixes.css" media="screen" /><?php endif; ?> 
<!--[if IE]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/iefixes.css" media="screen" /><![endif]-->
<script type="text/javascript" src="plugins/mootools/mootools.js"></script>
<script type="text/javascript" src="typolight/typolight.js"></script>
<script type="text/javascript" src="system/themes/<?php echo $this->theme; ?>/hover.js"></script>
</head>
<body>

<div id="header">
<h1>TYPOlight webCMS <?php echo VERSION; ?></h1>
</div>

<div id="container">

<div id="main">

<h2>TYPOlight FTP check</h2>
<?php if ($this->locked): ?>

<h3 class="no_border">FTP check login</h3>

<p class="tl_error">The FTP check tool has been locked</p>

<p>For security reasons the FTP check tool has been locked. This happens if
you enter a wrong password more than three times in a row. Please open the
local configuration file and set <em>installCount</em> to <em>0</em>.</p>
<?php elseif ($this->login): ?>

<h3 class="no_border">FTP check login</h3>

<p>Please enter the install tool password. The install tool password is not the 
same as the TYPOlight back end password.</p>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_login" />

<h4>Password</h4>
<?php if ($this->passwordError): ?>
<p class="tl_error"><?php echo $this->passwordError; ?></p>
<?php endif; ?>
<input type="password" name="password" id="password" class="tl_text" value="" />

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="submit" alt="submit button" value="Login" />
</div>

</div>
</form>
<?php elseif ($this->ftpDisabled): ?>

<h3 class="no_border">Enable FTP</h3>

<p class="tl_error">FTP usage disabled!</p>

<p>Please enable FTP usage in the local configuration file <strong>system/config/localconfig.php</strong>:</p>

<pre style="margin:18px 18px 0px 18px; padding:12px; background-color:#f6f6f6; border:1px solid #cccccc;">
$GLOBALS['TL_CONFIG']['useFTP'] = true;
$GLOBALS['TL_CONFIG']['ftpHost'] = '';  // FTP host
$GLOBALS['TL_CONFIG']['ftpPath'] = '';  // FTP path (e.g. html/)
$GLOBALS['TL_CONFIG']['ftpUser'] = '';  // FTP username
$GLOBALS['TL_CONFIG']['ftpPass'] = '';  // FTP password
</pre>
<?php else: ?>

<h3 class="no_border">Connect to FTP server</h3>
<?php if ($this->ftpHostError): ?>

<p class="tl_error"><?php if (!$this->ftpHost): ?>Please specify the FTP host!<?php else: ?>Could not connect to "<?php echo $this->ftpHost; ?>"!<?php endif; ?></p>
<?php else: ?>

<p class="tl_confirm">Successfully connected to "<?php echo $this->ftpHost; ?>".</p>
<?php endif; ?>

<p>Trying to establish a connection to the FTP host provided.</p>

<h3 class="no_border">Log into the FTP server</h3>
<?php if ($this->ftpUserError): ?>

<p class="tl_error"><?php if (!$this->ftpUser): ?>Please specify username and password!<?php else: ?>Could not login as "<?php echo $this->ftpUser; ?>"!<?php endif; ?></p>
<?php else: ?>

<p class="tl_confirm">Successfully logged in as "<?php echo $this->ftpUser; ?>".</p>
<?php endif; ?>

<p>Trying to log in to the FTP server with the username and password provided.</p>

<h3 class="no_border">Change to the TYPOlight directory</h3>
<?php if ($this->ftpPathError): ?>

<p class="tl_error">Could not change to directory "<?php echo $this->ftpPath; ?>"!</p>
<?php else: ?>

<p class="tl_confirm">Successfully changed to directory "<?php echo $this->ftpPath; ?>".</p>
<?php endif; ?>

<p>Trying to change to the directory provided as "ftpPath".</p>

<?php endif; ?>

<p id="go_to_login"><a href="typolight/install.php" title="TYPOlight install tool">TYPOlight install tool</a></p>

</div>

</div>
<?php if ($this->login): ?>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
window.addEvent('domready', function()
{
	$('password').focus();
});
//--><!]]>
</script>
<?php endif; ?>
<?php if ($this->pageOffset): ?>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
Backend.vScrollTo(<?php echo $this->pageOffset; ?>);
//--><!]]>
</script>
<?php setcookie('BE_PAGE_OFFSET', 0, 0, '/'); endif; ?>

</body>
</html>