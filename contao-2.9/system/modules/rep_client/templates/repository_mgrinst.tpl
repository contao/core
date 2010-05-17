<?php

/**
 * TYPOlight Repository :: Template to install an extension
 *
 * @package    Repository
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @license    See accompaning file LICENSE.txt
 */

$rep = &$this->rep;
$theme = &$rep->theme;
$text = &$GLOBALS['TL_LANG']['tl_repository'];
$state_options = &$GLOBALS['TL_LANG']['tl_repository_state_options'];
$statext = &$GLOBALS['TL_LANG']['tl_repository_statext'];
$tabindex = 1;

?>

<div id="tl_buttons">
<a href="<?php echo $rep->homeLink; ?>" class="header_back" title="<?php echo $text['goback']; ?>" accesskey="b" onclick="Backend.getScrollOffset();"><?php echo $text['goback']; ?></a>
</div>

<h2 class="sub_headline"><?php echo $text['installextension']; ?></h2>

<div class="mod_repository block">
<form action="<?php echo $rep->f_link; ?>" id="repository_editform" method="post" >
<div class="tl_formbody_edit">
<input type="hidden" name="repository_action" value="<?php echo $rep->f_action; ?>" />
<input type="hidden" name="repository_stage" value="<?php echo $rep->f_stage; ?>" />

<div class="tl_tbox block">

<?php $nm = ($rep->f_stage==$rep->inst_stages) ? 'repository_' : 'repository_dis_'; ?>
  <h3><label for="<?php echo $nm; ?>container"><?php echo $text['status']; ?></label></h3>
  <div id="<?php echo $nm; ?>container" class="tl_checkbox_container">
    <input type="checkbox" name="<?php echo $nm; ?>alpha" id="<?php echo $nm; ?>alpha" tabindex="<?php echo $tabindex++; ?>" class="tl_checkbox" value="1"<?php if ($rep->f_alpha) echo ' checked="checked"'; if ($rep->f_stage>$rep->inst_stages) echo ' disabled="disabled"'; ?> onfocus="Backend.getScrollOffset();" /> <label for="<?php echo $nm; ?>alpha"><?php echo $state_options['alpha']; ?></label><br />
    <input type="checkbox" name="<?php echo $nm; ?>beta" id="<?php echo $nm; ?>beta" tabindex="<?php echo $tabindex++; ?>" class="tl_checkbox" value="1"<?php if ($rep->f_beta) echo ' checked="checked"'; if ($rep->f_stage>$rep->inst_stages) echo ' disabled="disabled"'; ?> onfocus="Backend.getScrollOffset();" /> <label for="<?php echo $nm; ?>beta"><?php echo $state_options['beta']; ?></label><br />
    <input type="checkbox" name="<?php echo $nm; ?>rc" id="<?php echo $nm; ?>rc" tabindex="<?php echo $tabindex++; ?>" class="tl_checkbox" value="1"<?php if ($rep->f_rc) echo ' checked="checked"'; if ($rep->f_stage>$rep->inst_stages) echo ' disabled="disabled"'; ?> onfocus="Backend.getScrollOffset();" /> <label for="<?php echo $nm; ?>rc"><?php echo $state_options['rc']; ?></label><br />
    <input type="checkbox" name="<?php echo $nm; ?>stable" id="<?php echo $nm; ?>stable" tabindex="<?php echo $tabindex++; ?>" class="tl_checkbox" value="1"<?php if ($rep->f_stable) echo ' checked="checked"'; if ($rep->f_stage>$rep->inst_stages) echo ' disabled="disabled"'; ?> onfocus="Backend.getScrollOffset();" /> <label for="<?php echo $nm; ?>stable"><?php echo $state_options['stable']; ?></label>
  </div>
  <p class="tl_help"><?php echo $text['stateshint']; ?></p>
<?php if ($rep->f_stage>$rep->inst_stages): ?>
  <input type="hidden" name="repository_alpha" value="<?php echo $rep->f_alpha ? '1' : ''; ?>" />
  <input type="hidden" name="repository_beta" value="<?php echo $rep->f_beta ? '1' : ''; ?>" />
  <input type="hidden" name="repository_rc" value="<?php echo $rep->f_rc ? '1' : ''; ?>" />
  <input type="hidden" name="repository_stable" value="<?php echo $rep->f_stable ? '1' : ''; ?>" />
<?php endif; ?>
<?php if ($rep->f_stage>=$rep->inst_extension): ?>

<?php $nm = ($rep->f_stage==$rep->inst_extension) ? 'repository_extension' : 'repository_extension_dis'; ?>
  <h3><label for="<?php echo $nm; ?>"><?php echo $text['extension'][0]; ?></label></h3>
  <select name="<?php echo $nm; ?>" id="<?php echo $nm; ?>" class="tl_select" tabindex="<?php echo $tabindex++; ?>"<?php if ($rep->f_stage>$rep->inst_extension): ?> disabled="disabled"<?php endif; ?>>
<?php foreach ($rep->extensions as $ext) echo '    <option'.($ext==$rep->f_extension ? ' selected="selected"' : '').'>'.$ext.'</option>'."\n"; ?>
  </select>
<?php if ($rep->f_stage>$rep->inst_extension): ?>
  <input type="hidden" name="repository_extension" value="<?php echo $rep->f_extension; ?>" />
<?php endif; ?>
  <p class="tl_help"><?php echo $text['extension'][1]; ?></p>
<?php endif; ?>
<?php if ($rep->f_stage>=$rep->inst_lickey): ?>

<?php $nm = ($rep->f_stage==$rep->inst_lickey) ? 'repository_lickey' : 'repository_lickey_dis'; ?>
  <h3><label for="<?php echo $nm; ?>"><?php echo $text['lickey'][0]; ?></label></h3>
  <input type="text" tabindex="<?php echo $tabindex++; ?>" maxlength="255" name="<?php echo $nm; ?>" id="<?php echo $nm; ?>" value="<?php echo $rep->f_lickey; ?>" class="tl_text"<?php if ($rep->f_stage>$rep->inst_lickey): ?> disabled="disabled"<?php endif; ?> />
<?php if ($rep->f_stage>$rep->inst_lickey): ?>
  <input type="hidden" name="repository_lickey" value="<?php echo $rep->f_lickey; ?>" />
<?php endif; ?>
<?php if (property_exists($rep, 'f_lickey_msg')): ?>
  <div class="tl_error"><?php echo $text[$rep->f_lickey_msg]; ?></div>
<?php endif; ?>
  <p class="tl_help"><?php echo $text['lickey'][1]; ?></p>
<?php endif; ?>
<?php if ($rep->f_stage>=$rep->inst_actions): ?>

  <h3><label><?php echo $text['actionsummary']; ?></label></h3>
  <table cellpadding="0" cellspacing="0" class="installs" summary="">
  <tr class="title">
    <th class="col_extension"><?php echo $text['extension'][0]; ?></th>
    <th class="col_version"><?php echo $text['version'][0]; ?></th>
    <th class="col_build"><?php echo $text['build']; ?></th>
    <th class="col_action"><?php echo $text['action']; ?></th>
    <th class="col_status"><?php echo $text['status']; ?></th>
  </tr>
<?php foreach ($rep->actions as $act): ?>
  <tr class="datarow">
    <td class="col_extension"><?php echo $act->extension; ?></td>
    <td class="col_version"><?php echo Repository::formatVersion($act->version); ?></td>
    <td class="col_build"><?php echo $act->build; ?></td>
    <td class="col_action">
    <div class="tl_checkbox_container">
      <input type="checkbox" name="repository_enable[]" id="repository_enable_<?php echo $act->extension; ?>"  tabindex="<?php echo $tabindex++; ?>" class="tl_checkbox" value="<?php echo $act->extension; ?>"<?php if ($act->enabled): ?> checked="checked"<?php endif; ?> />
      <label for="repository_enable_<?php echo $act->extension; ?>"><?php echo $text[$act->action]; ?></label>
    </div>
    </td>
    <td class="col_status"><?php foreach ($act->status as $sta) echo '<div class="color_'.$sta->color.'">'.sprintf($statext[$sta->text], $sta->par1, $sta->par2).'</div>'; ?></td>
  </tr>
<?php endforeach; ?>
  </table>
<?php endif; ?>

</div>
<?php if ($rep->f_stage>=$rep->inst_showlog): ?>

<div class="installlog">
<?php echo $rep->log; ?> 
</div>
<?php endif; ?>

</div>

<div class="mod_repository_submit tl_formbody_submit">
<div class="tl_submit_container">
<?php if (property_exists($rep, 'f_submit')): ?>
  <input type="submit" name="repository_submitbutton" class="tl_submit" value="<?php echo $text[$rep->f_submit]; ?>" />
<?php endif; ?>
  <input type="submit" name="repository_cancelbutton" class="tl_submit" value="<?php echo $text['cancel']; ?>" />
</div>
</div>

</form>
</div>
