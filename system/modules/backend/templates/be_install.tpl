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

<h2>TYPOlight install tool</h2>
<?php if ($this->locked): ?>

<h3 class="no_border">Install tool login</h3>

<p class="tl_error">The install tool has been locked</p>

<p>For security reasons the install tool has been locked. This happens if you 
enter a wrong password more than three times in a row. Please open the local 
configuration file and set <em>installCount</em> to <em>0</em>.</p>
<?php elseif ($this->login): ?>

<h3 class="no_border">Install tool login</h3>

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
<?php else: ?>

<h3 class="no_border">Check local configuration file</h3>
<?php if (!$this->lcfWriteable): ?>

<p class="tl_error">The local configuration file is not writeable!</p>

<p>TYPOlight is not allowed to edit the local configuration file. Please activate 
FTP to modify files (<em>Safe Mode Hack</em>) by adding the following lines to your 
local configuration file <strong>system/config/localconfig.php</strong>:</p>

<pre style="margin:18px 18px 0px 18px; padding:12px; background-color:#f6f6f6; border:1px solid #cccccc;">
$GLOBALS['TL_CONFIG']['useFTP'] = true;
$GLOBALS['TL_CONFIG']['ftpHost'] = '';  // FTP host
$GLOBALS['TL_CONFIG']['ftpPath'] = '';  // FTP path (e.g. html/)
$GLOBALS['TL_CONFIG']['ftpUser'] = '';  // FTP username
$GLOBALS['TL_CONFIG']['ftpPass'] = '';  // FTP password
</pre>

<form action="typolight/ftp.php" method="get">
<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" alt="check ftp connection" value="Check FTP connection" onclick="Backend.getScrollOffset();" />
</div>

</div>
</form>
<?php else: ?>

<p class="tl_confirm">The local configuration file is writeable.</p>

<p>The local configuration file <strong>system/config/localconfig.php</strong> 
is writeable.</p>

<h3>Install script password</h3>
<?php if ($this->setPassword): ?>

<p class="tl_error">Please change the default password to prevent unauthorized access!</p>
<?php else: ?>

<p class="tl_confirm">The default password has been changed.</p>
<?php endif; ?>

<p>If you additionaly want to secure this script, you can either insert an 
<strong>exit;</strong> statement into <strong>/typolight/install.php</strong>
or you can remove the file from your server. In this case will you have to edit
system settings directly in the local configuration file.</p>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_install" />

<h4><label for="password">Password</label></h4>
<?php if ($this->passwordError): ?>
<p class="tl_error"><?php echo $this->passwordError; ?></p>
<?php endif; ?>
<input type="password" name="password" id="password" class="tl_text" value="" />

<h4><label for="confirm_password">Password confirmation</label></h4>
<input type="password" name="confirm_password" id="confirm_password" class="tl_text" value="" />

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="submit" alt="submit button" value="Save password" />
</div>

</div>
</form>
<?php if (!$this->setPassword): ?>

<h3>Generate an encryption key</h3>
<?php if ($this->encryption): ?>

<p class="tl_error">Please create an encryption key!</p>
<?php elseif ($this->encryptionLength): ?>

<p class="tl_error">An encryption key has to be at least 8 characters long!</p>
<?php else: ?>

<p class="tl_confirm">An encryption key has been created.</p>
<?php endif; ?>

<p>This key is used to store encrypted data. Please note, that encrypted data
can only be decrypted with this key! Therefore note it down and do not change 
it if there is encrypted data already. Leave empty to generate a random key.</p>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_encryption" />

<h4>Generate encryption key</h4>
<input type="text" name="key" id="key" class="tl_text" value="<?php echo $this->encryptionKey; ?>" />

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="submit" alt="submit button" value="Generate or save key" onclick="Backend.getScrollOffset();" />
</div>

</div>
</form>
<?php if (!$this->encryption && !$this->encryptionLength): ?>

<h3>Check database connection</h3>
<?php if ($this->dbConnection): ?>

<p class="tl_confirm">Database connection ok.</p>
<?php else: ?>

<p class="tl_error">Could not connect to database!</p>
<?php endif; ?>

<p>Please enter your database connection parameters.</p>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_database_login" />

<h4>Driver</h4>
<select name="dbDriver" id="dbDriver" class="tl_select">
<?php echo $this->drivers; ?>
</select>

<h4>Host</h4>
<input type="text" name="dbHost" id="dbHost" class="tl_text" value="<?php echo $this->host; ?>" />

<h4>Username</h4>
<input type="text" name="dbUser" id="dbUser" class="tl_text" value="<?php echo $this->user; ?>" />

<h4>Password</h4>
<input type="text" name="dbPass" id="dbPass" class="tl_text" value="<?php echo $this->pass; ?>" />

<h4>Database</h4>
<input type="text" name="dbDatabase" id="dbDatabase" class="tl_text" value="<?php echo $this->database; ?>" />

<h4>Persistent connection</h4>
<select name="dbPconnect" id="dbPconnect" class="tl_select">
  <option value="false"<?php if (!$this->pconnect) echo ' selected="selected"'; ?>>No</option>
  <option value="true"<?php if ($this->pconnect) echo ' selected="selected"'; ?>>Yes</option>
</select>

<h4>Character set</h4>
<input type="text" name="dbCharset" id="dbCharset" class="tl_text" value="<?php echo $this->dbcharset; ?>" />

<h4>Port number</h4>
<input type="text" name="dbPort" id="dbPort" class="tl_text" value="<?php echo $this->port; ?>" />

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="submit" alt="submit button" value="Save login information" onclick="Backend.getScrollOffset();" />
</div>

</div>
</form>
<?php if ($this->dbConnection): ?>

<h3>Update database tables</h3>
<?php if ($this->dbUpToDate): ?>

<p class="tl_confirm">The database is up to date.</p>
<?php else: ?>

<p class="tl_error">The database is not up to date!</p>
<?php endif; ?>

<p>Please note that this update assistant has only been tested with MySQL and 
MySQLi databases. If you are using a different database (e.g. Oracle), you 
might have to install/update your database manually. In this case, please go to 
folder <strong>system/modules</strong> and search all its subfolders for files 
called <strong>dca/database.sql</strong>.</p>
<?php if (!$this->dbUpToDate): ?>

<form action="<?php echo $this->action; ?>" id="sql_form" class="tl_install_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_tables" />
<?php echo $this->dbUpdate; ?>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="submit" alt="update database" value="Update database" onclick="Backend.getScrollOffset();" />
</div>

</div>
</form>
<?php endif; ?>

<h3>Import a template</h3>
<?php if ($this->dateImported): ?>

<p class="tl_confirm">Template imported on <?php echo $this->dateImported; ?></p>
<?php else: ?>

<p class="tl_info">Any existing data will be deleted!</p>
<?php endif; ?>

<p>Please choose a template from the <em>templates</em> directory to be imported.</p>

<form action="<?php echo $this->action; ?>" id="tl_static" class="tl_install_form" method="post">
<div class="tl_formbody_submit">
<input type="hidden" name="FORM_SUBMIT" value="tl_tutorial" />

<h4>Templates</h4>
<select name="template" class="tl_select"><?php echo $this->templates; ?></select>

<div class="tl_checkbox_container" style="margin-top:3px;">
<input type="checkbox" name="preserve" id="ctrl_preserve" class="tl_checkbox" value="1" /> <label for="ctrl_preserve">Do not truncate tables</label>
</div>

<div class="tl_submit_container">
<input type="submit" name="submit" alt="import template" value="Import template" onclick="if (!confirm('Any existing data will be deleted! Do you really want to continue?')) return false; Backend.getScrollOffset();" />
</div>

</div>
</form>

<h3>Create an admin user</h3>
<?php if ($this->adminCreated): ?>

<p class="tl_confirm">An admin user has been created!</p>
<?php else: ?>

<p class="tl_error">Please fill in all fields to create an admin user!</p>
<?php endif; ?>

<p>If you have imported the example website, the admin's username will be 
<strong>k.jones</strong> and his password will be <strong>kevinjones</strong>.
See the example website (front end output) for more information.</p>
<?php if (!$this->adminCreated): ?>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_admin" />

<h4>Username</h4>
<input type="text" name="username" id="username" class="tl_text" value="<?php echo $this->adminUser; ?>" />

<h4>Name</h4>
<input type="text" name="name" id="name" class="tl_text" value="<?php echo $this->adminName; ?>" />

<h4>E-mail address</h4>
<input type="text" name="email" id="email" class="tl_text" value="<?php echo $this->adminEmail; ?>" />

<h4><label for="password">Password</label></h4>
<?php if ($this->adminError): ?>
<p class="tl_error"><?php echo $this->adminError; ?></p>
<?php endif; ?>
<input type="password" name="pass" id="pass" class="tl_text" value="" />

<h4><label for="confirm_password">Password confirmation</label></h4>
<input type="password" name="confirm_pass" id="confirm_pass" class="tl_text" value="" />

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="submit" alt="submit button" value="Create admin account" onclick="Backend.getScrollOffset();" />
</div>

</div>
</form>
<?php else: ?>
<h3>Congratulations!</h3>

<p class="tl_confirm">You have successfully installed TYPOlight!</p>

<p>Now please log into the TYPOlight back end and check all system settings. 
Then, visit your website to make sure that TYPOlight is working correctly.</p>

<?php endif; endif; endif; endif; endif; endif; ?>
<p id="go_to_login"><a href="typolight/index.php" title="TYPOlight back end login">TYPOlight back end login</a></p>

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