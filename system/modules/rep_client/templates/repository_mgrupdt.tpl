<?php
/**
 * TYPOlight Repository :: Template to update the database
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
	$tabindex = 1;
?>

<div id="tl_buttons">
<a href="<?php echo $rep->homeLink; ?>" class="header_back" title="<?php echo $text['goback']; ?>" accesskey="b" onclick="Backend.getScrollOffset();"><?php echo $text['goback']; ?></a>
</div>

<h2 class="sub_headline"><?php echo $text['updatedatabase']; ?></h2>

<div class="mod_repository block">
<form action="<?php echo $rep->f_link; ?>" id="repository_editform" method="post" >
<div class="tl_formbody_edit">
<input type="hidden" name="repository_action" value="<?php echo $rep->f_action; ?>" />

<div class="tl_tbox block">
<?php echo ($rep->dbUpdate != '') ? $rep->dbUpdate : '<div class="color_green">'.$text['dbuptodate'].'</div>'; ?> 
</div>

</div>

<div class="mod_repository_submit tl_formbody_submit">

<div class="tl_submit_container">
<?php if (property_exists($rep, 'f_submit')) { ?>
<input type="submit" name="repository_submitbutton" class="tl_submit" value="<?php echo $text[$rep->f_submit]; ?>" />
<?php } // if f_submit ?>
<input type="submit" name="repository_cancelbutton" class="tl_submit" value="<?php echo $text[$rep->f_cancel]; ?>" />
</div>

</div>

</form>
</div>
