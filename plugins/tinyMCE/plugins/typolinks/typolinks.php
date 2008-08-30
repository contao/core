<?php

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Plugins
 * @license    LGPL
 * @filesource
 */


/**
 * Initialize system
 */
define('TL_MODE', 'FE');
require('../../../../system/initialize.php');


/**
 * Include library class
 */
require('typolib.php');


/**
 * Generate page
 */
header('Content-Type: text/html; charset='.$GLOBALS['TL_CONFIG']['characterSet']);
$objLib = new typolib();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#advanced_dlg.link_title}</title>
	<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script type="text/javascript" src="../../utils/mctabs.js"></script>
	<script type="text/javascript" src="../../utils/form_utils.js"></script>
	<script type="text/javascript" src="../../utils/validate.js"></script>
	<script type="text/javascript" src="../../themes/advanced/js/link.js"></script>
	<base target="_self" />
</head>
<body id="link" style="display: none">
<form onsubmit="LinkDialog.update();return false;" action="#">
	<div class="tabs">
		<ul>
			<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;">{#advanced_dlg.link_title}</a></span></li>
		</ul>
	</div>

	<div class="panel_wrapper" style="height:152px;">
		<div id="general_panel" class="panel current">

		<table border="0" cellpadding="4" cellspacing="0">
		  <!-- TYPOlight patch -->
          <tr>
            <td nowrap="nowrap"><label for="tlpage">{#typolinks.page}</label></td>
            <td><table border="0" cellspacing="0" cellpadding="0"> 
				  <tr> 
					<td><select id="tlpage" name="tlpage" style="width: 200px" onchange="document.forms[0].tlfile.value='';document.forms[0].href.value=this.value;"><option value="">-</option><?php echo $objLib->createPageList(); ?></select></td> 
					<td id="hrefbrowsercontainer">&nbsp;</td>
				  </tr> 
				</table></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><label for="tlfile">{#typolinks.file}</label></td>
            <td><table border="0" cellspacing="0" cellpadding="0"> 
				  <tr> 
					<td><select id="tlfile" name="tlfile" style="width: 200px" onchange="document.forms[0].tlpage.value='';document.forms[0].href.value=this.value;"><option value="">-</option><?php echo $objLib->createFileList(); ?></select></td> 
					<td id="hrefbrowsercontainer">&nbsp;</td>
				  </tr> 
				</table></td>
          </tr>
		  <!-- /TYPOlight patch -->
          <tr>
            <td nowrap="nowrap"><label for="href">{#advanced_dlg.link_url}</label></td>
            <td><table border="0" cellspacing="0" cellpadding="0"> 
				  <tr> 
					<td><input id="href" name="href" type="text" class="mceFocus" value="" style="width: 200px" onchange="LinkDialog.checkPrefix(this);" /></td> 
					<td id="hrefbrowsercontainer">&nbsp;</td>
				  </tr> 
				</table></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><label for="linktitle">{#advanced_dlg.link_titlefield}</label></td>
            <td><input id="linktitle" name="linktitle" type="text" value="" style="width: 200px" /></td>
          </tr>
          <tr>
            <td><label id="targetlistlabel" for="targetlist">{#advanced_dlg.link_target}</label></td>
            <td><select id="target_list" name="target_list"></select></td>
          </tr>
          <tr>
            <td><label for="class_list">{#class_name}</label></td>
            <td><select id="class_list" name="class_list"></select></td>
          </tr>
        </table>
		</div>
	</div>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="submit" id="insert" name="insert" value="{#insert}" />
		</div>

		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>
	</div>
</form>
</body>
</html>