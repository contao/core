<?php

/**
 * TYPOlight Repository :: Template to display list of installed extensions
 *
 * @package    Repository
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @license    See accompaning file LICENSE.txt
 */

$rep = &$this->rep;
$theme = &$rep->theme;
$text = &$GLOBALS['TL_LANG']['tl_repository'];
$statext = &$GLOBALS['TL_LANG']['tl_repository_statext'];
$state_options = &$GLOBALS['TL_LANG']['tl_repository_state_options'];

?>

<div id="tl_buttons" class="buttonwrapper">
<?php echo $theme->createMainButton('dbcheck16', $rep->updateLink, $text['updatedatabase']); ?> &nbsp; :: &nbsp; 
<?php echo $theme->createMainButton('install16', $rep->installLink, $text['installextension']); ?> 
</div>

<div class="mod_repository block">
<div class="extension_container">
<?php if (count($rep->extensions) < 1): ?>

<p><?php echo $text['noextensionsfound']; ?></p>
<?php else: ?>

<table cellpadding="0" cellspacing="0" class="installs" summary="">
<tr class="title">
  <th class="col_extension"><?php echo $text['extension'][0]; ?></th>
  <th class="col_version"><?php echo $text['version'][0]; ?></th>
  <th class="col_build"><?php echo $text['build']; ?></th>
  <th class="col_updates"><?php echo $text['updates']; ?></th>
  <th class="col_status"><?php echo $text['status']; ?></th>
  <th class="col_functions">&nbsp;</th>
</tr>
<?php foreach ($rep->extensions as $ext): ?>
<tr class="datarow">
  <td class="col_extension"><?php echo property_exists($ext, 'catalogLink') ? '<a href="'.$ext->catalogLink.'">'.$ext->extension.'</a>' : $ext->extension; ?></td>
  <td class="col_version"><?php echo Repository::formatVersion($ext->version); ?></td>
  <td class="col_build"><?php echo $ext->build; ?></td>
  <td class="col_updates"><?php if ((int)$ext->stable>0) echo $theme->createImage('stable16', $state_options['stable'], 'title="'.$state_options['stable'].'"'); ?><?php if ((int)$ext->rc>0) echo $theme->createImage('rc16', $state_options['rc'], 'title="'.$state_options['rc'].'"'); ?><?php if ((int)$ext->beta>0) echo $theme->createImage('beta16', $state_options['beta'], 'title="'.$state_options['beta'].'"'); ?><?php if ((int)$ext->alpha>0) echo $theme->createImage('alpha16', $state_options['alpha'], 'title="'.$state_options['alpha'].'"'); ?></td>
  <td class="col_status"><?php foreach ($ext->status as $sta) echo '<div class="color_'.$sta->color.'">'.sprintf($statext[$sta->text], $sta->par1, $sta->par2).'</div>'; ?></td>
  <td class="col_functions"><?php echo $theme->createListButton('edit', $ext->editLink, $text['editextension']); ?> <?php echo $theme->createListButton('install16', $ext->updateLink, $text['updateextension']); ?> <?php if (property_exists($ext, 'uninstallLink')) echo $theme->createListButton('uninstall', $ext->uninstallLink, $text['uninstallextension']); ?> <?php if (property_exists($ext, 'manualLink')) echo $theme->createListButton('manual16', $ext->manualLink, $text['manual'], '', true); ?> <?php if (property_exists($ext, 'forumLink')) echo $theme->createListButton('forum16', $ext->forumLink, $text['forum'], '', true); ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

</div>
</div>
