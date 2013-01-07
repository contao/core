<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Typolinks
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Initialize system
 */
define('TL_MODE', 'BE');
require '../../../../system/initialize.php';


/**
 * Include library class
 */
require 'typolib.php';


/**
 * Generate page
 */
header('Content-Type: text/html; charset=' . $GLOBALS['TL_CONFIG']['characterSet']);
$objLib = new typolib();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#typolinks_dlg.link_title}</title>
	<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script type="text/javascript" src="../../utils/mctabs.js"></script>
	<script type="text/javascript" src="../../utils/editable_selects.js"></script>
	<script type="text/javascript" src="../../utils/form_utils.js"></script>
	<script type="text/javascript" src="../../utils/validate.js"></script>
	<script type="text/javascript" src="js/typolinks.js"></script>
</head>
<body id="link" style="display: none">
<form onsubmit="LinkDialog.update();return false;" action="#">
	<div class="tabs">
		<ul>
			<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;">{#typolinks_dlg.link_title}</a></span></li>
		</ul>
	</div>

	<div class="panel_wrapper" style="height:178px;">
		<div id="general_panel" class="panel current">
		<table border="0" cellpadding="4" cellspacing="0">
          <tr>
            <td class="nowrap"><label for="tlpage">{#typolinks_dlg.page}</label></td>
            <td><select id="tlpage" name="tlpage" style="width: 200px" onchange="document.forms[0].tlfile.value='';document.forms[0].href.value=this.value;"><option value="">-</option><?php echo $objLib->createPageList(); ?></select></td>
          </tr>
          <tr>
            <td class="nowrap"><label for="tlfile">{#typolinks_dlg.file}</label></td>
            <td><select id="tlfile" name="tlfile" style="width: 200px" onchange="document.forms[0].tlpage.value='';document.forms[0].href.value=this.value;"><option value="">-</option><?php echo $objLib->createFileList(); ?></select></td>
          </tr>
          <tr>
            <td class="nowrap"><label for="href">{#typolinks_dlg.link_url}</label></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td><input id="href" name="href" type="text" class="mceFocus" value="" style="width: 200px" onchange="LinkDialog.checkPrefix(this);" /></td>
					<td id="hrefbrowsercontainer">&nbsp;</td>
				  </tr>
				</table></td>
          </tr>
          <tr>
            <td class="nowrap"><label for="linktitle">{#typolinks_dlg.link_titlefield}</label></td>
            <td><input id="linktitle" name="linktitle" type="text" value="" style="width: 200px" /></td>
          </tr>
          <tr>
            <td><label id="rellistlabel" for="rel_list">{#typolinks_dlg.image_rel}</label></td>
            <td><select id="rel_list" name="rel_list" class="mceEditableSelect" style="width: 200px"></select></td>
          </tr>
          <tr>
            <td><label id="targetlistlabel" for="target_list">{#typolinks_dlg.link_target}</label></td>
            <td><select id="target_list" name="target_list" style="width: 200px"></select></td>
          </tr>
          <tr>
            <td><label for="class_list">{#class_name}</label></td>
            <td><select id="class_list" name="class_list" class="mceEditableSelect" style="width: 200px"></select></td>
          </tr>
        </table>
		</div>
	</div>

	<div class="mceActionPanel">
		<input type="submit" id="insert" name="insert" value="{#insert}" />
		<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
	</div>
</form>
</body>
</html>