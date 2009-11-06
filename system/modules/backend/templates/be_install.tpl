<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->language; ?>">
<head>
<base href="<?php echo $this->base; ?>"></base>
<title>TYPOlight Open Source CMS <?php echo VERSION; ?> :: Install Tool</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/basic.css" media="screen" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/install.css" media="screen" />
<?php if ($this->isMac): ?>
<link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/macfixes.css" media="screen" />
<?php endif; ?>
<!--[if lte IE 7]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/iefixes.css" media="screen" /><![endif]-->
<!--[if IE 8]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/ie8fixes.css" media="screen" /><![endif]-->
<script type="text/javascript" src="plugins/mootools/mootools-core.js"></script>
<script type="text/javascript" src="plugins/mootools/mootools-more.js"></script>
<script type="text/javascript" src="typolight/typolight.js"></script>
<script type="text/javascript" src="system/themes/<?php echo $this->theme; ?>/hover.js"></script>
</head>
<body>

<div id="header">
<h1>TYPOlight Open Source CMS <?php echo VERSION; ?></h1>
</div>

<div id="container">
<div id="main">

<h2><?php echo $GLOBALS['TL_LANG']['tl_install']['installTool'][0]; ?></h2>
<?php if ($this->locked): ?>

<h3 class="no_border"><?php echo $GLOBALS['TL_LANG']['tl_install']['installTool'][1]; ?></h3>

<p class="tl_error"><?php echo $GLOBALS['TL_LANG']['tl_install']['locked'][0]; ?></p>
<p><?php echo $GLOBALS['TL_LANG']['tl_install']['locked'][1]; ?></p>
<?php elseif (!$this->lcfWriteable): ?>

<h3 class="no_border"><?php echo $GLOBALS['TL_LANG']['tl_install']['ftp'][0]; ?></h3>

<?php if ($this->ftpHostError): ?>
<p class="tl_error"><?php printf($GLOBALS['TL_LANG']['tl_install']['ftpHostError'], $GLOBALS['TL_CONFIG']['ftpHost']); ?></p>
<?php elseif ($this->ftpUserError): ?>
<p class="tl_error"><?php printf($GLOBALS['TL_LANG']['tl_install']['ftpUserError'], $GLOBALS['TL_CONFIG']['ftpUser']); ?></p>
<?php elseif ($this->ftpPathError): ?>
<p class="tl_error"><?php printf($GLOBALS['TL_LANG']['tl_install']['ftpPathError'], $GLOBALS['TL_CONFIG']['ftpPath']); ?></p>
<?php endif; ?>
<p><?php echo $GLOBALS['TL_LANG']['tl_install']['ftp'][1]; ?></p>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody">
  <input type="hidden" name="FORM_SUBMIT" value="tl_ftp" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['ftpHost']; ?></h4>
  <input type="text" name="host" id="host" class="tl_text" value="<?php echo $GLOBALS['TL_CONFIG']['ftpHost']; ?>" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['ftpPath']; ?></h4>
  <input type="text" name="path" id="path" class="tl_text" value="<?php echo $GLOBALS['TL_CONFIG']['ftpPath']; ?>" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['ftpUser']; ?></h4>
  <input type="text" name="username" id="username" class="tl_text" value="<?php echo $GLOBALS['TL_CONFIG']['ftpUser']; ?>" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['ftpPass']; ?></h4>
  <input type="password" name="password" id="password" class="tl_text" value="<?php echo $GLOBALS['TL_CONFIG']['ftpPass']; ?>" />
</div>
<div class="tl_formbody_submit">
  <div class="tl_submit_container">
    <input type="submit" name="submit" alt="Save FTP settings" value="<?php echo $GLOBALS['TL_LANG']['tl_install']['ftpSave']; ?>" />
  </div>
</div>
</form>
<?php elseif ($this->license): ?>

<h3 class="no_border">GNU Lesser General Public License</h3>

<div id="license">
<pre>
<?php echo $this->license; ?>
</pre>
</div>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody_submit">
  <input type="hidden" name="FORM_SUBMIT" value="tl_license" />
  <div class="tl_submit_container">
    <input type="submit" alt="Accept the license agreement" value="<?php echo $GLOBALS['TL_LANG']['tl_install']['accept']; ?>" onclick="Backend.getScrollOffset();" />
  </div>
</div>
</form>
<?php elseif ($this->login): ?>

<h3 class="no_border"><?php echo $GLOBALS['TL_LANG']['tl_install']['installTool'][1]; ?></h3>

<p><?php echo $GLOBALS['TL_LANG']['tl_install']['password'][1]; ?></p>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody">
  <input type="hidden" name="FORM_SUBMIT" value="tl_login" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['password'][0]; ?></h4>
<?php if ($this->passwordError): ?>
  <p class="tl_error"><?php echo $this->passwordError; ?></p>
<?php endif; ?>
  <input type="password" name="password" id="password" class="tl_text" value="" />
</div>
<div class="tl_formbody_submit">
  <div class="tl_submit_container">
    <input type="submit" name="submit" alt="Login" value="<?php echo $GLOBALS['TL_LANG']['MSC']['login']; ?>" />
  </div>
</div>
</form>
<?php else: ?>

<h3 class="no_border"><?php echo $GLOBALS['TL_LANG']['tl_install']['changePass'][0]; ?></h3>

<?php if ($this->setPassword): ?>
<p class="tl_error"><?php echo $GLOBALS['TL_LANG']['tl_install']['passError']; ?></p>
<?php else: ?>
<p class="tl_confirm"><?php echo $GLOBALS['TL_LANG']['tl_install']['passConfirm']; ?></p>
<?php endif; ?>
<p><?php echo $GLOBALS['TL_LANG']['tl_install']['changePass'][1]; ?></p>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody">
  <input type="hidden" name="FORM_SUBMIT" value="tl_install" />
  <h4><label for="password"><?php echo $GLOBALS['TL_LANG']['MSC']['password'][0]; ?></label></h4>
<?php if ($this->passwordError): ?>
  <p class="tl_error"><?php echo $this->passwordError; ?></p>
<?php endif; ?>
  <input type="password" name="password" id="password" class="tl_text" value="" />
  <h4><label for="confirm_password"><?php echo $GLOBALS['TL_LANG']['MSC']['confirm'][0]; ?></label></h4>
  <input type="password" name="confirm_password" id="confirm_password" class="tl_text" value="" />
</div>
<div class="tl_formbody_submit">
  <div class="tl_submit_container">
    <input type="submit" name="submit" alt="Save password" value="<?php echo $GLOBALS['TL_LANG']['tl_install']['passSave']; ?>" />
  </div>
</div>
</form>
<?php if (!$this->setPassword): ?>

<h3><?php echo $GLOBALS['TL_LANG']['tl_install']['encryption'][0]; ?></h3>

<?php if ($this->encryption): ?>
<p class="tl_error"><?php echo $GLOBALS['TL_LANG']['tl_install']['keyError']; ?></p>
<?php elseif ($this->encryptionLength): ?>
<p class="tl_error"><?php echo $GLOBALS['TL_LANG']['tl_install']['keyLength']; ?></p>
<?php else: ?>
<p class="tl_confirm"><?php echo $GLOBALS['TL_LANG']['tl_install']['keyConfirm']; ?></p>
<?php endif; ?>
<p><?php echo $GLOBALS['TL_LANG']['tl_install']['encryption'][1]; ?></p>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody">
  <input type="hidden" name="FORM_SUBMIT" value="tl_encryption" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['keyCreate']; ?></h4>
  <input type="text" name="key" id="key" class="tl_text" value="<?php echo $this->encryptionKey; ?>" />
</div>
<div class="tl_formbody_submit">
  <div class="tl_submit_container">
    <input type="submit" name="submit" alt="Generate or save key" value="<?php echo $GLOBALS['TL_LANG']['tl_install']['keySave']; ?>" onclick="Backend.getScrollOffset();" />
  </div>
</div>
</form>
<?php if (!$this->encryption && !$this->encryptionLength): ?>

<h3><?php echo $GLOBALS['TL_LANG']['tl_install']['database'][0]; ?></h3>

<?php if ($this->dbConnection): ?>
<p class="tl_confirm"><?php echo $GLOBALS['TL_LANG']['tl_install']['dbConfirm']; ?></p>
<?php else: ?>
<p class="tl_error"><?php echo $GLOBALS['TL_LANG']['tl_install']['dbError']; ?></p>
<?php endif; ?>
<p><?php echo $GLOBALS['TL_LANG']['tl_install']['database'][1]; ?></p>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody">
  <input type="hidden" name="FORM_SUBMIT" value="tl_database_login" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['dbDriver']; ?></h4>
  <select name="dbDriver" id="dbDriver" class="tl_select"><?php echo $this->drivers; ?></select>
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['dbHost']; ?></h4>
  <input type="text" name="dbHost" id="dbHost" class="tl_text" value="<?php echo $this->host; ?>" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['dbUsername']; ?></h4>
  <input type="text" name="dbUser" id="dbUser" class="tl_text" value="<?php echo $this->user; ?>" />
  <h4><?php echo $GLOBALS['TL_LANG']['MSC']['password'][0]; ?></h4>
  <input type="text" name="dbPass" id="dbPass" class="tl_text" value="<?php echo $this->pass; ?>" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['dbDatabase']; ?></h4>
  <input type="text" name="dbDatabase" id="dbDatabase" class="tl_text" value="<?php echo $this->database; ?>" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['dbPersistent']; ?></h4>
  <select name="dbPconnect" id="dbPconnect" class="tl_select"><option value="false"<?php if (!$this->pconnect) echo ' selected="selected"'; ?>><?php echo $GLOBALS['TL_LANG']['MSC']['no']; ?></option><option value="true"<?php if ($this->pconnect) echo ' selected="selected"'; ?>><?php echo $GLOBALS['TL_LANG']['MSC']['yes']; ?></option></select>
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['dbCharset']; ?></h4>
  <input type="text" name="dbCharset" id="dbCharset" class="tl_text" value="<?php echo $this->dbcharset; ?>" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['dbPort']; ?></h4>
  <input type="text" name="dbPort" id="dbPort" class="tl_text" value="<?php echo $this->port; ?>" />
</div>
<div class="tl_formbody_submit">
  <div class="tl_submit_container">
    <input type="submit" name="submit" alt="Save database settings" value="<?php echo $GLOBALS['TL_LANG']['tl_install']['dbSave']; ?>" onclick="Backend.getScrollOffset();" />
  </div>
</div>
</form>
<?php if ($this->dbConnection): ?>

<h3><?php echo $GLOBALS['TL_LANG']['tl_install']['update'][0]; ?></h3>

<?php if (!$this->dbUpToDate): ?>
<p class="tl_error"><?php echo $GLOBALS['TL_LANG']['tl_install']['updateError']; ?></p>
<?php else: ?>
<p class="tl_confirm"><?php echo $GLOBALS['TL_LANG']['tl_install']['updateConfirm']; ?></p>
<?php endif; ?>
<p><?php echo $GLOBALS['TL_LANG']['tl_install']['update'][1]; ?></p>
<?php if (!$this->dbUpToDate): ?>

<form action="<?php echo $this->action; ?>" id="sql_form" class="tl_install_form" method="post">
<div class="tl_formbody">
  <input type="hidden" name="FORM_SUBMIT" value="tl_tables" /><?php echo $this->dbUpdate; ?>
</div>
<div class="tl_formbody_submit">
  <div class="tl_submit_container">
    <input type="submit" name="submit" alt="Update database" value="<?php echo $GLOBALS['TL_LANG']['tl_install']['updateSave']; ?>" onclick="Backend.getScrollOffset();" />
  </div>
</div>
</form>
<?php endif; ?>

<h3><?php echo $GLOBALS['TL_LANG']['tl_install']['template'][0]; ?></h3>

<?php if ($this->emptySelection): ?>
<p class="tl_error"><?php echo $GLOBALS['TL_LANG']['tl_install']['importError']; ?></p>
<?php elseif ($this->dateImported): ?>
<p class="tl_confirm"><?php printf($GLOBALS['TL_LANG']['tl_install']['importConfirm'], $this->dateImported); ?></p>
<?php else: ?>
<p class="tl_info"><?php echo $GLOBALS['TL_LANG']['tl_install']['importWarn']; ?></p>
<?php endif; ?>
<p><?php echo $GLOBALS['TL_LANG']['tl_install']['template'][1]; ?></p>

<form action="<?php echo $this->action; ?>" id="tl_static" class="tl_install_form" method="post">
<div class="tl_formbody_submit">
  <input type="hidden" name="FORM_SUBMIT" value="tl_tutorial" />
  <h4><?php echo $GLOBALS['TL_LANG']['tl_install']['templates']; ?></h4>
  <select name="template" class="tl_select"><?php echo $this->templates; ?></select>
  <div class="tl_checkbox_container" style="margin-top:3px;">
    <input type="checkbox" name="preserve" id="ctrl_preserve" class="tl_checkbox" value="1" /> <label for="ctrl_preserve"><?php echo $GLOBALS['TL_LANG']['tl_install']['doNotTruncate']; ?></label>
  </div>
</div>
<div class="tl_formbody_submit">
  <div class="tl_submit_container">
    <input type="submit" name="submit" alt="Import template" value="<?php echo $GLOBALS['TL_LANG']['tl_install']['importSave']; ?>" onclick="if (!confirm('<?php echo $GLOBALS['TL_LANG']['tl_install']['importContinue']; ?>')) return false; Backend.getScrollOffset();" />
  </div>
</div>
</form>

<h3><?php echo $GLOBALS['TL_LANG']['tl_install']['admin'][0]; ?></h3>

<?php if (!$this->adminCreated): ?>
<p class="tl_error"><?php echo $GLOBALS['TL_LANG']['tl_install']['adminError']; ?></p>
<?php else: ?>
<p class="tl_confirm"><?php echo $GLOBALS['TL_LANG']['tl_install']['adminConfirm']; ?></p>
<?php endif; ?>
<p><?php echo $GLOBALS['TL_LANG']['tl_install']['admin'][1]; ?></p>
<?php if (!$this->adminCreated): ?>

<form action="<?php echo $this->action; ?>" class="tl_install_form" method="post">
<div class="tl_formbody">
  <input type="hidden" name="FORM_SUBMIT" value="tl_admin" />
  <h4><?php echo $GLOBALS['TL_LANG']['MSC']['username']; ?></h4>
  <input type="text" name="username" id="username" class="tl_text" value="<?php echo $this->adminUser; ?>" />
  <h4><?php echo $GLOBALS['TL_LANG']['MSC']['com_name']; ?></h4>
  <input type="text" name="name" id="name" class="tl_text" value="<?php echo $this->adminName; ?>" />
  <h4><?php echo $GLOBALS['TL_LANG']['MSC']['emailAddress']; ?></h4>
  <input type="text" name="email" id="email" class="tl_text" value="<?php echo $this->adminEmail; ?>" />
  <h4><label for="password"><?php echo $GLOBALS['TL_LANG']['MSC']['password'][0]; ?></label></h4>
<?php if ($this->adminError): ?>
  <p class="tl_error"><?php echo $this->adminError; ?></p>
<?php endif; ?>
  <input type="password" name="pass" id="pass" class="tl_text" value="" />
  <h4><label for="confirm_password"><?php echo $GLOBALS['TL_LANG']['MSC']['confirm'][0]; ?></label></h4>
  <input type="password" name="confirm_pass" id="confirm_pass" class="tl_text" value="" />
</div>
<div class="tl_formbody_submit">
  <div class="tl_submit_container">
    <input type="submit" name="submit" alt="Create admin account" value="<?php echo $GLOBALS['TL_LANG']['tl_install']['adminSave']; ?>" onclick="Backend.getScrollOffset();" />
  </div>
</div>
</form>
<?php else: ?>

<h3><?php echo $GLOBALS['TL_LANG']['tl_install']['completed'][0]; ?></h3>

<p class="tl_confirm"><?php echo $GLOBALS['TL_LANG']['tl_install']['installConfirm']; ?></p>
<p><?php echo $GLOBALS['TL_LANG']['tl_install']['completed'][1]; ?></p>

<?php endif; endif; endif; endif; endif; ?>
<p id="go_to_login"><a href="typolight/index.php" title="TYPOlight back end login"><?php echo $GLOBALS['TL_LANG']['tl_install']['beLogin']; ?></a></p>

</div>
</div>
<?php if ($this->login): ?>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
window.addEvent('domready', function() {
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