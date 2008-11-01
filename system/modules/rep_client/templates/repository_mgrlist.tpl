<?php
/**
 * TYPOlight Repository :: Template to display list of installed extensions
 *
 * NOTE: this file was edited with tabs set to 4.
 * @package Repository
 * @copyright Copyright (C) 2008 by Peter Koch, IBK Software AG
 * @license See accompaning file LICENSE.txt
 */
?>
<?php 
	$rep = &$this->rep;
	$theme = &$rep->theme;
	$text = &$GLOBALS['TL_LANG']['tl_repository'];
?>

<div id="tl_buttons" class="buttonwrapper">
<?php echo $theme->createMainButton('dbcheck16', $rep->updateLink, $text['updatedatabase']); ?> &nbsp; :: &nbsp; 
<?php echo $theme->createMainButton('install16', $rep->installLink, $text['installextension']); ?> 
</div>

<div class="mod_repository block">

<div class="extension_container">
<?php if (count($rep->extensions)>0) { ?>
<table cellpadding="0" cellspacing="0" class="installs" summary="">
<tr class="title">
  <th class="col_extension"><?php echo $text['extension'][0]; ?></th>
  <th class="col_version"><?php echo $text['version'][0]; ?></th>
  <th class="col_build"><?php echo $text['build']; ?></th>
  <th class="col_updates"><?php echo $text['updates']; ?></th>
  <th class="col_status"><?php echo $text['status']; ?></th>
  <th class="col_functions">&nbsp;</th>
</tr>

<?php foreach ($rep->extensions as $ext) { ?>
<tr class="datarow">
  <td class="col_extension"><?php echo property_exists($ext, 'catalogLink') ? '<a href="'.$ext->catalogLink.'">'.$ext->extension.'</a>' : $ext->extension; ?></td>
  <td class="col_version"><?php echo Repository::formatVersion($ext->version); ?></td>
  <td class="col_build"><?php echo $ext->build; ?></td>
  <td class="col_updates">
<?php 
if ((int)$ext->stable>0) echo $theme->createImage('stable16', $text['state_options']['stable'], 'title="'.$text['state_options']['stable'].'"');
if ((int)$ext->rc>0) echo $theme->createImage('rc16', $text['state_options']['rc'], 'title="'.$text['state_options']['rc'].'"');
if ((int)$ext->beta>0) echo $theme->createImage('beta16', $text['state_options']['beta'], 'title="'.$text['state_options']['beta'].'"');
if ((int)$ext->alpha>0) echo $theme->createImage('alpha16', $text['state_options']['alpha'], 'title="'.$text['state_options']['alpha'].'"'); 
?>
  </td>
  <td class="col_status">
<?php
foreach ($ext->status as $sta) {
	echo '<div class="color_'.$sta->color.'">'.sprintf($text['statext'][$sta->text], $sta->par1, $sta->par2).'</div>'."\n";
} // foreach status 
?>
  </td>
  <td class="col_functions">
  <?php echo $theme->createListButton('edit', $ext->editLink, $text['editextension']); ?> 
  <?php echo $theme->createListButton('install16', $ext->updateLink, $text['updateextension']); ?>
  <?php if (property_exists($ext, 'uninstallLink')) echo $theme->createListButton('uninstall', $ext->uninstallLink, $text['uninstallextension']); ?> 
  <?php if (property_exists($ext, 'manualLink')) echo $theme->createListButton('manual16', $ext->manualLink, $text['manual'], '', true); ?> 
  <?php if (property_exists($ext, 'forumLink')) echo $theme->createListButton('forum16', $ext->forumLink, $text['forum'], '', true); ?> 
  </td>
</tr>
<?php } // foreach rep->extensions ?>
</table>
<?php } else { ?>
<p><?php echo $text['noextensionsfound']; ?></p>
<?php } // if count rep->extensions ?>
</div>

</div>
