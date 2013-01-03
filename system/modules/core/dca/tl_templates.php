<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Load default language file
 */
$this->loadLanguageFile('tl_files');


/**
 * Overwrite some settings
 */
if (Input::get('do') == 'tpl_editor')
{
	$GLOBALS['TL_CONFIG']['uploadPath'] = 'templates';
	$GLOBALS['TL_CONFIG']['editableFiles'] = $GLOBALS['TL_CONFIG']['templateFiles'];
}


/**
 * Template editor
 */
$GLOBALS['TL_DCA']['tl_templates'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Folder',
		'validFileTypes'              => $GLOBALS['TL_CONFIG']['templateFiles'],
		'closed'                      => true,
		'onload_callback' => array
		(
			array('tl_templates', 'addBreadcrumb'),
		)
	),

	// List
	'list' => array
	(
		'global_operations' => array
		(
			'new_tpl' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_templates']['new_tpl'],
				'href'                => 'key=new_tpl',
				'class'               => 'header_new'
			),
			'toggleNodes' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleAll'],
				'href'                => 'tg=all',
				'class'               => 'header_toggle'
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'source' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['source'],
				'href'                => 'act=source',
				'icon'                => 'editor.gif',
				'button_callback'     => array('tl_templates', 'editSource')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
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
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'spaceToUnderscore'=>true)
		)
	)
);



/**
 * Class tl_templates
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class tl_templates extends Backend
{

	/**
	 * Add the breadcrumb menu
	 */
	public function addBreadcrumb()
	{
		// Set a new node
		if (isset($_GET['node']))
		{
			$this->Session->set('tl_templates_node', Input::get('node', true));
			$this->redirect(preg_replace('/(&|\?)node=[^&]*/', '', Environment::get('request')));
		}

		$strNode = $this->Session->get('tl_templates_node');

		if ($strNode == '')
		{
			return;
		}

		// Currently selected folder does not exist
		if (!is_dir(TL_ROOT . '/' . $strNode))
		{
			$this->Session->set('tl_templates_node', '');
			return;
		}

		$strPath = 'templates';
		$arrNodes = explode('/', preg_replace('/^templates\//', '', $strNode));
		$arrLinks = array();

		// Add root link
		$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/filemounts.gif" width="18" height="18" alt=""> <a href="' . $this->addToUrl('node=') . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectAllNodes']).'">' . $GLOBALS['TL_LANG']['MSC']['filterAll'] . '</a>';

		// Generate breadcrumb trail
		foreach ($arrNodes as $strFolder)
		{
			$strPath .= '/' . $strFolder;

			// No link for the active folder
			if ($strFolder == basename($strNode))
			{
				$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/folderC.gif" width="18" height="18" alt=""> ' . $strFolder;
			}
			else
			{
				$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/folderC.gif" width="18" height="18" alt=""> <a href="' . $this->addToUrl('node='.$strPath) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'">' . $strFolder . '</a>';
			}
		}

		// Limit tree
		$GLOBALS['TL_DCA']['tl_templates']['list']['sorting']['root'] = array($strNode);

		// Insert breadcrumb menu
		$GLOBALS['TL_DCA']['tl_templates']['list']['sorting']['breadcrumb'] .= '

<ul id="tl_breadcrumb">
  <li>' . implode(' &gt; </li><li>', $arrLinks) . '</li>
</ul>';
	}


	/**
	 * Create a new template
	 * @return string
	 */
	public function addNewTemplate()
	{
		$strError = '';

		// Copy an existing template
		if (Input::post('FORM_SUBMIT') == 'tl_create_template' && file_exists(TL_ROOT . '/system/modules/' . Input::post('original')))
		{
			$strOriginal = Input::post('original');
			$strTarget = str_replace('../', '', Input::post('target'));

			// Validate the target path
			if (strncmp($strTarget, 'templates', 9) !== 0 || !is_dir(TL_ROOT . '/' . $strTarget))
			{
				$strError = sprintf($GLOBALS['TL_LANG']['tl_templates']['invalid'], $strTarget);
			}
			else
			{
				$strTarget .= '/' . basename($strOriginal);

				// Check whether the target file exists
				if (file_exists(TL_ROOT . '/' . $strTarget))
				{
					$strError = sprintf($GLOBALS['TL_LANG']['tl_templates']['exists'], $strTarget);
				}
				else
				{
					$this->import('Files');
					$this->Files->copy('system/modules/' . $strOriginal, $strTarget);
					$this->redirect($this->getReferer());
				}
			}
		}

		$arrAllTemplates = array();
		$arrAllowed = trimsplit(',', $GLOBALS['TL_CONFIG']['templateFiles']);

		// Get all templates
		foreach ($this->Config->getActiveModules() as $strModule)
		{
			// Continue if there is no templates folder
			if ($strModule == 'repository' || !is_dir(TL_ROOT . '/system/modules/' . $strModule . '/templates'))
			{
				continue;
			}

			// Find all templates
			foreach (scan(TL_ROOT . '/system/modules/' . $strModule . '/templates') as $strTemplate)
			{
				// Ignore non-template files
				if (strncmp($strTemplate, '.', 1) === 0 || $strTemplate == 'tpl_editor.html5' || !preg_match('/\.(' . implode('|', $arrAllowed) . ')$/', $strTemplate))
				{
					continue;
				}

				$arrAllTemplates[$strModule][$strTemplate] = $strModule . '/templates/' . $strTemplate;
			}
		}

		$strAllTemplates = '';

		// Group the templates by module
		foreach ($arrAllTemplates as $k=>$v)
		{
			$strAllTemplates .= '<optgroup label="' . $k . '">';

			foreach ($v as $kk=>$vv)
			{
				$strAllTemplates .= sprintf('<option value="%s" class="%s"%s>%s</option>', $vv, ((strpos($vv, '.html5') === false) ? 'tl_gray' : ''), ((Input::post('original') == $vv) ? ' selected="selected"' : ''), $kk);
			}

			$strAllTemplates .= '</optgroup>';
		}

		// Show form
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_templates']['headline'].'</h2>'.(($strError != '') ? '

<div class="tl_message">
<p class="tl_error">'.$strError.'</p>
</div>' : '').'

<form action="'.ampersand(Environment::get('request')).'" id="tl_create_template" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_create_template">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<div class="tl_tbox">
<div>
  <h3><label for="ctrl_original">'.$GLOBALS['TL_LANG']['tl_templates']['original'][0].'</label></h3>
  <select name="original" id="ctrl_original" class="tl_select tl_chosen" onfocus="Backend.getScrollOffset()">'.$strAllTemplates.'</select>'.(($GLOBALS['TL_LANG']['tl_templates']['original'][1] && $GLOBALS['TL_CONFIG']['showHelp']) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_templates']['original'][1].'</p>' : '').'
</div>
<div>
  <h3><label for="ctrl_target">'.$GLOBALS['TL_LANG']['tl_templates']['target'][0].'</label></h3>
  <select name="target" id="ctrl_target" class="tl_select" onfocus="Backend.getScrollOffset()"><option value="templates">templates</option>'. $this->getTargetFolders('templates') .'</select>'.(($GLOBALS['TL_LANG']['tl_templates']['target'][1] && $GLOBALS['TL_CONFIG']['showHelp']) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_templates']['target'][1].'</p>' : '').'
</div>
</div>
</div>

<div class="tl_formbody_submit">
<div class="tl_submit_container">
  <input type="submit" name="create" id="create" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_templates']['newTpl']).'">
</div>
</div>
</form>';
	}


	/**
	 * Recursively scan the templates directory and return all folders as array
	 * @param string
	 * @param integer
	 * @return string
	 */
	protected function getTargetFolders($strFolder, $intLevel=1)
	{
		$strFolders = '';
		$strPath = TL_ROOT .'/'. $strFolder;

		foreach (scan($strPath) as $strFile)
		{
			if (!is_dir($strPath .'/'. $strFile) || strncmp($strFile, '.', 1) === 0)
			{
				continue;
			}

			$strRelPath = $strFolder .'/'. $strFile;
			$strFolders .= sprintf('<option value="%s"%s>%s%s</option>', $strRelPath, ((Input::post('target') == $strRelPath) ? ' selected="selected"' : ''), str_repeat(' &nbsp; ', $intLevel), basename($strRelPath));
			$strFolders .= $this->getTargetFolders($strRelPath, ($intLevel + 1));
		}

		return $strFolders;
	}


	/**
	 * Return the edit file source button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editSource($row, $href, $label, $title, $icon, $attributes)
	{
		return is_file(TL_ROOT . '/' . $row['id']) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
}
