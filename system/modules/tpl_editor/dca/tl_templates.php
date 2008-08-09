<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @package    TemplateEditor
 * @license    LGPL
 * @filesource
 */


/**
 * Load default language file
 */
$this->loadLanguageFile('tl_files');


/**
 * Overwrite some settings
 */
$GLOBALS['TL_CONFIG']['uploadPath'] = 'templates';
$GLOBALS['TL_CONFIG']['editableFiles'] = 'tpl';


/**
 * Template editor
 */
$GLOBALS['TL_DCA']['tl_templates'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Folder',
		'validFileTypes'              => 'tpl',
		'closed'                      => true
	),

	// List
	'list' => array
	(
		'new' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_templates']['new'],
			'href'                    => 'key=new',
			'class'                   => 'header_new'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_templates']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'source' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_templates']['source'],
				'href'                => 'act=source',
				'icon'                => 'editor.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_templates']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'name'
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_files']['name'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'spaceToUnderscore'=>true)
		)
	)
);



/**
 * Class tl_templates
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_templates extends Backend
{

	/**
	 * Create a new template
	 * @return string
	 */
	public function addNewTemplate()
	{
		// Copy an existing template
		if ($this->Input->post('FORM_SUBMIT') == 'tl_create_template' && file_exists(TL_ROOT . '/system/modules/' . $this->Input->post('original')))
		{
			$strOriginal = preg_replace('#.*/(.*)$#', '$1', $this->Input->post('original'));

			if (file_exists(TL_ROOT . '/templates/' . $strOriginal))
			{
				$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['tl_templates']['exists'], $strOriginal);
				$this->reload();
			}

			$this->import('Files');

			if ($this->Files->copy('system/modules/' . $this->Input->post('original'), 'templates/' . $strOriginal))
			{
				$this->redirect($this->getReferer());
			}

			$this->reload();
		}

		$arrAllTemplates = array();

		// Get all templates
		foreach ($this->Config->getActiveModules() as $strModule)
		{
			if (!is_dir(TL_ROOT . '/system/modules/' . $strModule . '/templates'))
			{
				continue;
			}

			foreach (scan(TL_ROOT . '/system/modules/' . $strModule . '/templates') as $strTemplate)
			{
				if (strncmp($strTemplate, '.', 1) === 0 || $strTemplate == 'tpl_editor.tpl' || substr($strTemplate, -4) != '.tpl')
				{
					continue;
				}

				$arrAllTemplates[$strTemplate] = $strModule . '/templates/' . $strTemplate;
			}
		}

		$arrAllTemplates = natcaseksort($arrAllTemplates);
		$strAllTemplates = '';

		foreach ($arrAllTemplates as $k=>$v)
		{
			$strAllTemplates .= sprintf('<option value="%s">%s</option>', $v, $k);
		}

		// Show form
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(ENCODE_AMPERSANDS).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset();">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_templates']['headline'].'</h2>'.$this->getMessages().'

<form action="'.ampersand($this->Environment->request).'" id="tl_create_template" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_create_template" />
<div class="tl_tbox">
  <h3><label for="ctrl_original">'.$GLOBALS['TL_LANG']['tl_templates']['original'][0].'</label></h3>
  <select name="original" id="ctrl_original" class="tl_select" onfocus="Backend.getScrollOffset();">'.$strAllTemplates.'</select>'.(($GLOBALS['TL_LANG']['tl_templates']['original'][1] && $GLOBALS['TL_CONFIG']['showHelp']) ? '
  <p class="tl_help">'.$GLOBALS['TL_LANG']['tl_templates']['original'][1].'</p>' : '').'
</div>
</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="create" id="create" class="tl_submit" alt="create a new template" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_templates']['newTpl']).'" /> 
</div>

</div>
</form>';
	}
}

?>