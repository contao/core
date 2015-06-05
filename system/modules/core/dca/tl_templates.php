<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Load default language file
 */
System::loadLanguageFile('tl_files');


/**
 * Overwrite some settings
 */
if (Input::get('do') == 'tpl_editor')
{
	Config::set('uploadPath', 'templates');
	Config::set('editableFiles', Config::get('templateFiles'));
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
		'validFileTypes'              => Config::get('templateFiles'),
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
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirmFile'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'source' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_files']['source'],
				'href'                => 'act=source',
				'icon'                => 'editor.gif',
				'button_callback'     => array('tl_templates', 'editSource')
			),
			'compare' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_templates']['compare'],
				'href'                => 'key=compare',
				'icon'                => 'diffTemplate.gif',
				'button_callback'     => array('tl_templates', 'compareButton')
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
			'eval'                    => array('mandatory'=>true, 'maxlength'=>32, 'spaceToUnderscore'=>true)
		)
	)
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_templates extends Backend
{

	/**
	 * Add the breadcrumb menu
	 *
	 * @throws RuntimeException
	 */
	public function addBreadcrumb()
	{
		// Set a new node
		if (isset($_GET['node']))
		{
			// Check the path (thanks to Arnaud Buchoux)
			if (Validator::isInsecurePath(Input::get('node', true)))
			{
				throw new RuntimeException('Insecure path ' . Input::get('node', true));
			}

			$this->Session->set('tl_templates_node', Input::get('node', true));
			$this->redirect(preg_replace('/(&|\?)node=[^&]*/', '', Environment::get('request')));
		}

		$strNode = $this->Session->get('tl_templates_node');

		if ($strNode == '')
		{
			return;
		}

		// Check the path (thanks to Arnaud Buchoux)
		if (Validator::isInsecurePath($strNode))
		{
			throw new RuntimeException('Insecure path ' . $strNode);
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
		$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . Backend::getTheme() . '/images/filemounts.gif" width="18" height="18" alt=""> <a href="' . $this->addToUrl('node=') . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectAllNodes']).'">' . $GLOBALS['TL_LANG']['MSC']['filterAll'] . '</a>';

		// Generate breadcrumb trail
		foreach ($arrNodes as $strFolder)
		{
			$strPath .= '/' . $strFolder;

			// No link for the active folder
			if ($strFolder == basename($strNode))
			{
				$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . Backend::getTheme() . '/images/folderC.gif" width="18" height="18" alt=""> ' . $strFolder;
			}
			else
			{
				$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . Backend::getTheme() . '/images/folderC.gif" width="18" height="18" alt=""> <a href="' . $this->addToUrl('node='.$strPath) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'">' . $strFolder . '</a>';
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
	 *
	 * @return string
	 */
	public function addNewTemplate()
	{
		$strError = '';

		// Copy an existing template
		if (Input::post('FORM_SUBMIT') == 'tl_create_template')
		{
			$strOriginal = Input::post('original');

			if (Validator::isInsecurePath($strOriginal))
			{
				throw new RuntimeException('Invalid path ' . $strOriginal);
			}

			$strTarget = Input::post('target');

			if (Validator::isInsecurePath($strTarget))
			{
				throw new RuntimeException('Invalid path ' . $strTarget);
			}

			// Validate the source path
			if (strncmp($strOriginal, 'system/modules/', 15) !== 0 || !file_exists(TL_ROOT . '/' . $strOriginal))
			{
				$strError = sprintf($GLOBALS['TL_LANG']['tl_templates']['invalid'], $strOriginal);
			}
			else
			{
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
						$this->Files->copy($strOriginal, $strTarget);
						$this->redirect($this->getReferer());
					}
				}
			}
		}

		$arrAllTemplates = array();
		$arrAllowed = trimsplit(',', Config::get('templateFiles'));

		// Get all templates
		foreach (ModuleLoader::getActive() as $strModule)
		{
			// Continue if there is no templates folder
			if ($strModule == 'repository' || !is_dir(TL_ROOT . '/system/modules/' . $strModule . '/templates'))
			{
				continue;
			}

			/** @var \SplFileInfo[] $objFiles */
			$objFiles = new SortedIterator(
				new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator(
						TL_ROOT . '/system/modules/' . $strModule . '/templates',
						FilesystemIterator::UNIX_PATHS|FilesystemIterator::FOLLOW_SYMLINKS|FilesystemIterator::SKIP_DOTS
					)
				)
			);

			foreach ($objFiles as $objFile)
			{
				$strExtension = pathinfo($objFile->getFilename(), PATHINFO_EXTENSION);

				if (in_array($strExtension, $arrAllowed))
				{
					$strRelpath = str_replace(TL_ROOT . '/', '', $objFile->getPathname());
					$arrAllTemplates[$strModule][basename($strRelpath)] = $strRelpath;
				}
			}
		}

		$strAllTemplates = '';

		// Group the templates by module
		foreach ($arrAllTemplates as $k=>$v)
		{
			$strAllTemplates .= '<optgroup label="' . $k . '">';

			foreach ($v as $kk=>$vv)
			{
				$strAllTemplates .= sprintf('<option value="%s"%s>%s</option>', $vv, ((Input::post('original') == $vv) ? ' selected="selected"' : ''), $kk);
			}

			$strAllTemplates .= '</optgroup>';
		}

		// Show form
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>'.(($strError != '') ? '

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
  <select name="original" id="ctrl_original" class="tl_select tl_chosen" onfocus="Backend.getScrollOffset()">'.$strAllTemplates.'</select>'.(($GLOBALS['TL_LANG']['tl_templates']['original'][1] && Config::get('showHelp')) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_templates']['original'][1].'</p>' : '').'
</div>
<div>
  <h3><label for="ctrl_target">'.$GLOBALS['TL_LANG']['tl_templates']['target'][0].'</label></h3>
  <select name="target" id="ctrl_target" class="tl_select" onfocus="Backend.getScrollOffset()"><option value="templates">templates</option>'. $this->getTargetFolders('templates') .'</select>'.(($GLOBALS['TL_LANG']['tl_templates']['target'][1] && Config::get('showHelp')) ? '
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
	 * Compares the current to the original template
	 *
	 * @param DataContainer $dc
	 *
	 * @return string
	 */
	public function compareTemplate(DataContainer $dc)
	{
		$strCurrentPath = $dc->id;
		$strName = pathinfo($strCurrentPath, PATHINFO_FILENAME);
		$strExtension = pathinfo($strCurrentPath, PATHINFO_EXTENSION);
		$arrTemplates = TemplateLoader::getFiles();
		$blnOverridesAnotherTpl = isset($arrTemplates[$strName]);

		$strPrefix = '';

		if (($pos = strpos($strName, '_')) !== false)
		{
			$strPrefix = substr($strName, 0, $pos + 1);
		}

		$strBuffer = '';
		$strCompareName = null;
		$strComparePath = null;

		// By default it's the original template to compare against
		if ($blnOverridesAnotherTpl)
		{
			$strCompareName = $strName;
			$strComparePath = $arrTemplates[$strCompareName] . '/' .$strCompareName . '.' . $strExtension;

			if ($strComparePath !== null)
			{
				$strBuffer .= '<p class="tl_info" style="margin-bottom:1em">' . sprintf($GLOBALS['TL_LANG']['tl_templates']['overridesAnotherTpl'], $strComparePath) . '</p>';
			}
		}

		// User selected template to compare against
		if (\Input::post('from') && isset($arrTemplates[\Input::post('from')]))
		{
			$strCompareName = \Input::post('from');
			$strComparePath = $arrTemplates[$strCompareName] . '/' .$strCompareName . '.' . $strExtension;
		}

		if ($strComparePath !== null)
		{
			$objCurrentFile = new \File($strCurrentPath, true);
			$objCompareFile = new \File($strComparePath, true);

			// Abort if one file is missing
			if (!$objCurrentFile->exists() || !$objCompareFile->exists())
			{
				$this->redirect('contao/main.php?act=error');
			}

			$objDiff = new Diff($objCompareFile->getContentAsArray(), $objCurrentFile->getContentAsArray());
			$strDiff = $objDiff->Render(new DiffRenderer(array('field'=>$strCurrentPath)));

			// Identical versions
			if ($strDiff == '')
			{
				$strBuffer .= '<p>' . $GLOBALS['TL_LANG']['MSC']['identicalVersions'] . '</p>';
			}
			else
			{
				$strBuffer .= $strDiff;
			}
		}
		else
		{
			$strBuffer .= '<p class="tl_info">' . $GLOBALS['TL_LANG']['tl_templates']['pleaseSelect'] . '</p>';
		}

		// Templates to compare against
		$arrComparable = array();
		$intPrefixLength = strlen($strPrefix);

		foreach ($arrTemplates as $k => $v)
		{
			if (substr($k, 0, $intPrefixLength) === $strPrefix)
			{
				$arrComparable[$k] = array
				(
					'version' => $k,
					'info'    => $k . '.' . $strExtension
				);
			}
		}

		$objTemplate = new \BackendTemplate('be_diff');

		// Template variables
		$objTemplate->staticTo = $strCurrentPath;
		$objTemplate->versions = $arrComparable;
		$objTemplate->from = $strCompareName;
		$objTemplate->showLabel = specialchars($GLOBALS['TL_LANG']['MSC']['showDifferences']);
		$objTemplate->content = $strBuffer;
		$objTemplate->theme = \Backend::getTheme();
		$objTemplate->base = \Environment::get('base');
		$objTemplate->language = $GLOBALS['TL_LANGUAGE'];
		$objTemplate->title = specialchars($GLOBALS['TL_LANG']['MSC']['showDifferences']);
		$objTemplate->charset = \Config::get('characterSet');

		\Config::set('debugMode', false);

		$objTemplate->output();
		exit;
	}


	/**
	 * Return the "compare template" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function compareButton($row, $href, $label, $title, $icon, $attributes)
	{
		return is_file(TL_ROOT . '/' . $row['id']) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . specialchars(str_replace("'", "\\'", $row['id'])) . '\',\'url\':this.href});return false"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Recursively scan the templates directory and return all folders as array
	 *
	 * @param string  $strFolder
	 * @param integer $intLevel
	 *
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
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function editSource($row, $href, $label, $title, $icon, $attributes)
	{
		return is_file(TL_ROOT . '/' . $row['id']) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
}
