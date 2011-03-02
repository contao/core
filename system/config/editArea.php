<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Config
 * @license    LGPL
 * @filesource
 */


/**
 * Choose the syntax based on the file extension
 */
if (is_null($this->syntax))
{
	switch ($this->extension)
	{
		case 'c':
		case 'h':
			$this->syntax = 'c';
			break;

		case 'cc':
		case 'cpp':
		case 'c++':
		case 'hh':
		case 'hpp':
		case 'h++':
			$this->syntax = 'cpp';
			break;

		case 'htm':
		case 'html':
		case 'tpl':
		default:
			$this->syntax = 'html';
			break;

		case 'pl':
		case 'cgi':
			$this->syntax = 'perl';
			break;

		case 'py':
		case 'pyc':
		case 'pyd':
		case 'pyo':
			$this->syntax = 'python';
			break;

		case 'rb':
		case 'rbw':
			$this->syntax = 'ruby';
			break;

		case 'css':
		case 'java':
		case 'js':
		case 'php':
		case 'sql':
		case 'vb':
		case 'xml':
			$this->syntax = $this->extension;
			break;
	}
}

// Version 0.8.2 fix (see #2112)
if ($this->syntax == '')
{
	$this->syntax = 'html';
}


/**
 * This is the editArea (code editor) configuration file. Please visit
 * http://www.cdolivet.com/editarea/ for more information.
 */
if ($GLOBALS['TL_CONFIG']['useCE']): ?>
<script type="text/javascript" src="<?php echo $this->base; ?>plugins/editArea/edit_area_full.js?<?php echo EDITAREA; ?>"></script>
<?php foreach ($this->ceFields as $strField): ?>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
editAreaLoader.init({
  id : "<?php echo $strField; ?>",
  syntax: "<?php echo $this->syntax; ?>",
  language: "<?php echo $this->language; ?>",
  start_highlight: true,
  toolbar: "new_document,search,go_to_line,fullscreen,|,syntax_selection,select_font,|,change_smooth_selection,highlight,reset_highlight,word_wrap,|,undo,redo,|,help",
  syntax_selection_allow: "c,cpp,css,html,java,js,perl,php,phyton,ruby,sql,vb,xml"
});
//--><!]]>
</script>
<?php endforeach; ?>
<?php endif; ?>
