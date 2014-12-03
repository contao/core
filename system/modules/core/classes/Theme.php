<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class Theme
 *
 * Provide methods to handle themes.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class Theme extends \Backend
{

	/**
	 * Import a theme
	 * @return string
	 */
	public function importTheme()
	{
		$this->import('BackendUser', 'User');
		$class = $this->User->uploader;

		// See #4086 and #7046
		if (!class_exists($class) || $class == 'DropZone')
		{
			$class = 'FileUpload';
		}

		$objUploader = new $class();

		if (\Input::post('FORM_SUBMIT') == 'tl_theme_import')
		{
			if (!\Input::post('confirm'))
			{
				$arrUploaded = $objUploader->uploadTo('system/tmp');

				if (empty($arrUploaded))
				{
					\Message::addError($GLOBALS['TL_LANG']['ERR']['all_fields']);
					$this->reload();
				}

				$arrFiles = array();

				foreach ($arrUploaded as $strFile)
				{
					// Skip folders
					if (is_dir(TL_ROOT . '/' . $strFile))
					{
						\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['importFolder'], basename($strFile)));
						continue;
					}

					$objFile = new \File($strFile, true);

					// Skip anything but .cto files
					if ($objFile->extension != 'cto')
					{
						\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension));
						continue;
					}

					$arrFiles[] = $strFile;
				}
			}
			else
			{
				$arrFiles = explode(',', $this->Session->get('uploaded_themes'));
			}

			// Check whether there are any files
			if (empty($arrFiles))
			{
				\Message::addError($GLOBALS['TL_LANG']['ERR']['all_fields']);
				$this->reload();
			}

			// Store the field names of the theme tables
			$arrDbFields = array
			(
				'tl_theme'           => $this->Database->getFieldNames('tl_theme'),
				'tl_style_sheet'     => $this->Database->getFieldNames('tl_style_sheet'),
				'tl_style'           => $this->Database->getFieldNames('tl_style'),
				'tl_module'          => $this->Database->getFieldNames('tl_module'),
				'tl_layout'          => $this->Database->getFieldNames('tl_layout'),
				'tl_image_size'      => $this->Database->getFieldNames('tl_image_size'),
				'tl_image_size_item' => $this->Database->getFieldNames('tl_image_size_item')
			);

			// Proceed
			if (\Input::post('confirm') == 1)
			{
				$this->extractThemeFiles($arrFiles, $arrDbFields);
			}
			else
			{
				$this->Session->set('uploaded_themes', implode(',', $arrFiles));
				return $this->compareThemeFiles($arrFiles, $arrDbFields);
			}
		}

		// Return the form
		return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=importTheme', '', \Environment::get('request'))).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>
'.\Message::generate().'
<form action="'.ampersand(\Environment::get('request'), true).'" id="tl_theme_import" class="tl_form" method="post" enctype="multipart/form-data">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_theme_import">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<input type="hidden" name="MAX_FILE_SIZE" value="'.\Config::get('maxFileSize').'">

<div class="tl_tbox">
  <h3>'.$GLOBALS['TL_LANG']['tl_theme']['source'][0].'</h3>'.$objUploader->generateMarkup().(isset($GLOBALS['TL_LANG']['tl_theme']['source'][1]) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_theme']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_theme']['importTheme'][0]).'">
</div>

</div>
</form>';
	}


	/**
	 * Compare the theme tables with the local database and check
	 * whether there are custom layout sections
	 * @param array
	 * @param array
	 * @return string
	 */
	protected function compareThemeFiles($arrFiles, $arrDbFields)
	{
		$return = '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=importTheme', '', \Environment::get('request'))).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>
'.\Message::generate().'
<form action="'.ampersand(\Environment::get('request'), true).'" id="tl_theme_import" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_theme_import">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<input type="hidden" name="confirm" value="1">';

		$count = 0;

		// Check the theme data
		foreach ($arrFiles as $strFile)
		{
			$return .= '

<div class="tl_'. (($count++ < 1) ? 't' : '') .'box theme_import">
  <h3>'. basename($strFile) .'</h3>
  <h4>'.$GLOBALS['TL_LANG']['tl_theme']['tables_fields'].'</h4>';

			// Find the XML file
			$objArchive = new \ZipReader($strFile);

			// Continue if there is no XML file
			if ($objArchive->getFile('theme.xml') === false)
			{
				$return .= "\n  " . '<p class="tl_red" style="margin:0">'. sprintf($GLOBALS['TL_LANG']['tl_theme']['missing_xml'], basename($strFile)) ."</p>\n</div>";
				continue;
			}

			// Open the XML file
			$xml = new \DOMDocument();
			$xml->preserveWhiteSpace = false;
			$xml->loadXML($objArchive->unzip());
			$tables = $xml->getElementsByTagName('table');

			$blnHasError = false;

			// Loop through the tables
			for ($i=0; $i<$tables->length; $i++)
			{
				$rows = $tables->item($i)->childNodes;
				$table = $tables->item($i)->getAttribute('name');

				// Skip invalid tables
				if ($table != 'tl_theme' && $table != 'tl_style_sheet' && $table != 'tl_style' && $table != 'tl_module' && $table != 'tl_layout' && $table != 'tl_image_size' && $table != 'tl_image_size_item')
				{
					continue;
				}

				$fields = $rows->item(0)->childNodes;

				// Loop through the fields
				for ($j=0; $j<$fields->length; $j++)
				{
					$name = $fields->item($j)->getAttribute('name');

					// Print a warning if a field is missing
					if (!in_array($name, $arrDbFields[$table]))
					{
						$blnHasError = true;
						$return .= "\n  " . '<p class="tl_red" style="margin:0">'. sprintf($GLOBALS['TL_LANG']['tl_theme']['missing_field'], $table .'.'. $name) .'</p>';
					}
				}
			}

			// Confirmation
			if (!$blnHasError)
			{
				$return .= "\n  " . '<p class="tl_green" style="margin:0">'. $GLOBALS['TL_LANG']['tl_theme']['tables_ok'] .'</p>';
			}

			// Check the custom templates
			$return .= '
  <h4>'.$GLOBALS['TL_LANG']['tl_theme']['custom_templates'].'</h4>';

			$objArchive->reset();
			$blnTplExists = false;

			// Loop through the archive
			while ($objArchive->next())
			{
				if (strncmp($objArchive->file_name, 'templates/', 10) !== 0)
				{
					continue;
				}

				if (file_exists(TL_ROOT .'/'. $objArchive->file_name))
				{
					$blnTplExists = true;
					$return .= "\n  " . '<p class="tl_red" style="margin:0">'. sprintf($GLOBALS['TL_LANG']['tl_theme']['template_exists'], $objArchive->file_name) .'</p>';
				}
			}

			// Confirmation
			if (!$blnTplExists)
			{
				$return .= "\n  " . '<p class="tl_green" style="margin:0">'. $GLOBALS['TL_LANG']['tl_theme']['templates_ok'] .'</p>';
			}

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['compareThemeFiles']) && is_array($GLOBALS['TL_HOOKS']['compareThemeFiles']))
			{
				foreach ($GLOBALS['TL_HOOKS']['compareThemeFiles'] as $callback)
				{
					$return .= \System::importStatic($callback[0])->$callback[1]($xml, $objArchive);
				}
			}

			$return .= '
</div>';
		}

		// Return the form
		return $return . '

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['continue']).'">
</div>

</div>
</form>';
	}


	/**
	 * Extract the theme files and write the data to the database
	 * @param array
	 * @param array
	 */
	protected function extractThemeFiles($arrFiles, $arrDbFields)
	{
		foreach ($arrFiles as $strZipFile)
		{
			$xml = null;

			// Open the archive
			$objArchive = new \ZipReader($strZipFile);

			// Extract all files
			while ($objArchive->next())
			{
				// Load the XML file
				if ($objArchive->file_name == 'theme.xml')
				{
					$xml = new \DOMDocument();
					$xml->preserveWhiteSpace = false;
					$xml->loadXML($objArchive->unzip());
					continue;
				}

				// Limit file operations to files and the templates directory
				if (strncmp($objArchive->file_name, 'files/', 6) !== 0 && strncmp($objArchive->file_name, 'tl_files/', 9) !== 0 && strncmp($objArchive->file_name, 'templates/', 10) !== 0)
				{
					\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalidFile'], $objArchive->file_name));
					continue;
				}

				// Extract the files
				try
				{
					\File::putContent($this->customizeUploadPath($objArchive->file_name), $objArchive->unzip());
				}
				catch (\Exception $e)
				{
					\Message::addError($e->getMessage());
				}
			}

			// Continue if there is no XML file
			if (!$xml instanceof \DOMDocument)
			{
				\Message::addError(sprintf($GLOBALS['TL_LANG']['tl_theme']['missing_xml'], basename($strZipFile)));
				continue;
			}

			$arrMapper = array();
			$tables = $xml->getElementsByTagName('table');
			$arrNewFolders = array();

			// Extract the folder names from the XML file
			for ($i=0; $i<$tables->length; $i++)
			{
				if ($tables->item($i)->getAttribute('name') == 'tl_theme')
				{
					$fields = $tables->item($i)->childNodes->item(0)->childNodes;

					for ($k=0; $k<$fields->length; $k++)
					{
						if ($fields->item($k)->getAttribute('name') == 'folders')
						{
							$arrNewFolders = deserialize($fields->item($k)->nodeValue);
							break;
						}
					}

					break;
				}
			}

			// Sync the new folder(s)
			if (!empty($arrNewFolders) && is_array($arrNewFolders))
			{
				foreach ($arrNewFolders as $strFolder)
				{
					\Dbafs::addResource($this->customizeUploadPath($strFolder));
				}
			}

			// Lock the tables
			$arrLocks = array
			(
				'tl_files'           => 'WRITE',
				'tl_layout'          => 'WRITE',
				'tl_module'          => 'WRITE',
				'tl_style_sheet'     => 'WRITE',
				'tl_style'           => 'WRITE',
				'tl_theme'           => 'WRITE',
				'tl_image_size'      => 'WRITE',
				'tl_image_size_item' => 'WRITE'
			);

			// Load the DCAs of the locked tables (see #7345)
			foreach (array_keys($arrLocks) as $table)
			{
				if ($table != 'tl_files')
				{
					$this->loadDataContainer($table);
				}
			}

			$this->Database->lockTables($arrLocks);

			// Get the current auto_increment values
			$tl_theme = $this->Database->getNextId('tl_theme');
			$tl_style_sheet = $this->Database->getNextId('tl_style_sheet');
			$tl_style = $this->Database->getNextId('tl_style');
			$tl_module = $this->Database->getNextId('tl_module');
			$tl_layout = $this->Database->getNextId('tl_layout');
			$tl_image_size = $this->Database->getNextId('tl_image_size');
			$tl_image_size_item = $this->Database->getNextId('tl_image_size_item');

			// Loop through the tables
			for ($i=0; $i<$tables->length; $i++)
			{
				$rows = $tables->item($i)->childNodes;
				$table = $tables->item($i)->getAttribute('name');

				// Skip invalid tables
				if ($table == 'tl_files' || !in_array($table, array_keys($arrLocks)))
				{
					continue;
				}

				// Get the order fields
				$objDcaExtractor = \DcaExtractor::getInstance($table);
				$arrOrder = $objDcaExtractor->getOrderFields();

				// Loop through the rows
				for ($j=0; $j<$rows->length; $j++)
				{
					$set = array();
					$fields = $rows->item($j)->childNodes;

					// Loop through the fields
					for ($k=0; $k<$fields->length; $k++)
					{
						$value = $fields->item($k)->nodeValue;
						$name = $fields->item($k)->getAttribute('name');

						// Skip NULL values
						if ($value == 'NULL')
						{
							continue;
						}

						// Increment the ID
						elseif ($name == 'id')
						{
							$id = ${$table}++;
							$arrMapper[$table][$value] = $id;
							$value = $id;
						}

						// Increment the parent IDs
						elseif ($name == 'pid')
						{
							if ($table == 'tl_style')
							{
								$value = $arrMapper['tl_style_sheet'][$value];
							}
							elseif ($table == 'tl_image_size_item')
							{
								$value = $arrMapper['tl_image_size'][$value];
							}
							else
							{
								$value = $arrMapper['tl_theme'][$value];
							}
						}

						// Handle fallback fields
						elseif ($name == 'fallback')
						{
							$value = '';
						}

						// Adjust the style sheet IDs of the page layout
						elseif ($table == 'tl_layout' && $name == 'stylesheet')
						{
							$stylesheets = deserialize($value);

							if (is_array($stylesheets))
							{
								foreach (array_keys($stylesheets) as $key)
								{
									$stylesheets[$key] = $arrMapper['tl_style_sheet'][$stylesheets[$key]];
								}

								$value = serialize($stylesheets);
							}
						}

						// Adjust the module IDs of the page layout
						elseif ($table == 'tl_layout' && $name == 'modules')
						{
							$modules = deserialize($value);

							if (is_array($modules))
							{
								foreach ($modules as $key=>$mod)
								{
									if ($mod['mod'] > 0)
									{
										$modules[$key]['mod'] = $arrMapper['tl_module'][$mod['mod']];
									}
								}

								$value = serialize($modules);
							}
						}

						// Adjust duplicate theme and style sheet names
						elseif (($table == 'tl_theme' || $table == 'tl_style_sheet') && $name == 'name')
						{
							$objCount = $this->Database->prepare("SELECT COUNT(*) AS count FROM ". $table ." WHERE name=?")
													   ->execute($value);

							if ($objCount->count > 0)
							{
								$value = preg_replace('/( |\-)[0-9]+$/', '', $value);
								$value .= (($table == 'tl_style_sheet') ? '-' : ' ') . ${$table};
							}
						}

						// Adjust the file paths in style sheets
						elseif (($table == 'tl_style_sheet' || $table == 'tl_style') && strpos($value, 'files') !== false)
						{
							$tmp = deserialize($value);

							if (is_array($tmp))
							{
								foreach ($tmp as $kk=>$vv)
								{
									$tmp[$kk] = $this->customizeUploadPath($vv);
								}

								$value = serialize($tmp);
							}
							else
							{
								$value = $this->customizeUploadPath($value);
							}
						}

						// Replace the file paths in singleSRC fields with their tl_files ID
						elseif ($GLOBALS['TL_DCA'][$table]['fields'][$name]['inputType'] == 'fileTree' && !$GLOBALS['TL_DCA'][$table]['fields'][$name]['eval']['multiple'])
						{
							if (!$value)
							{
								$value = null; // Contao >= 3.2
							}
							else
							{
								// Do not use the FilesModel here – tables are locked!
								$objFile = $this->Database->prepare("SELECT uuid FROM tl_files WHERE path=?")
														  ->limit(1)
														  ->execute($this->customizeUploadPath($value));

								$value = $objFile->uuid;
							}
						}

						// Replace the file paths in multiSRC fields with their tl_files ID
						elseif ($GLOBALS['TL_DCA'][$table]['fields'][$name]['inputType'] == 'fileTree' || in_array($name, $arrOrder))
						{
							$tmp = deserialize($value);

							if (is_array($tmp))
							{
								foreach ($tmp as $kk=>$vv)
								{
									// Do not use the FilesModel here – tables are locked!
									$objFile = $this->Database->prepare("SELECT uuid FROM tl_files WHERE path=?")
															  ->limit(1)
															  ->execute($this->customizeUploadPath($vv));

									$tmp[$kk] = $objFile->uuid;
								}

								$value = serialize($tmp);
							}
						}

						// Adjust the imageSize widget data
						elseif ($GLOBALS['TL_DCA'][$table]['fields'][$name]['inputType'] == 'imageSize')
						{
							$imageSizes = deserialize($value, true);

							if (!empty($imageSizes))
							{
								if (is_numeric($imageSizes[2]))
								{
									$imageSizes[2] = $arrMapper['tl_image_size'][$imageSizes[2]];
								}
							}

							$value = serialize($imageSizes);
						}

						$set[$name] = $value;
					}

					// Skip fields that are not in the database (e.g. because of missing extensions)
					foreach ($set as $k=>$v)
					{
						if (!in_array($k, $arrDbFields[$table]))
						{
							unset($set[$k]);
						}
					}

					// Create the templates folder even if it is empty (see #4793)
					if ($table == 'tl_theme' && isset($set['templates']) && strncmp($set['templates'], 'templates/', 10) === 0 && !is_dir(TL_ROOT . '/' . $set['templates']))
					{
						new \Folder($set['templates']);
					}

					// Update the datatbase
					$this->Database->prepare("INSERT INTO ". $table ." %s")->set($set)->execute();
				}
			}

			// Unlock the tables
			$this->Database->unlockTables();

			// Update the style sheets
			$this->import('StyleSheets');
			$this->StyleSheets->updateStyleSheets();

			// Notify the user
			\Message::addConfirmation(sprintf($GLOBALS['TL_LANG']['tl_theme']['theme_imported'], basename($strZipFile)));

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['extractThemeFiles']) && is_array($GLOBALS['TL_HOOKS']['extractThemeFiles']))
			{
				$intThemeId = empty($arrMapper['tl_theme']) ? null : reset($arrMapper['tl_theme']);

				foreach ($GLOBALS['TL_HOOKS']['extractThemeFiles'] as $callback)
				{
					\System::importStatic($callback[0])->$callback[1]($xml, $objArchive, $intThemeId, $arrMapper);
				}
			}
		}

		\System::setCookie('BE_PAGE_OFFSET', 0, 0);
		$this->Session->remove('uploaded_themes');

		// Redirect
		$this->redirect(str_replace('&key=importTheme', '', \Environment::get('request')));
	}


	/**
	 * Export a theme
	 * @param \DataContainer
	 */
	public function exportTheme(\DataContainer $dc)
	{
		// Get the theme meta data
		$objTheme = $this->Database->prepare("SELECT * FROM tl_theme WHERE id=?")
								   ->limit(1)
								   ->execute($dc->id);

		if ($objTheme->numRows < 1)
		{
			return;
		}

		// Romanize the name
		$strName = utf8_romanize($objTheme->name);
		$strName = strtolower(str_replace(' ', '_', $strName));
		$strName = preg_replace('/[^A-Za-z0-9\._-]/', '', $strName);
		$strName = basename($strName);

		// Create a new XML document
		$xml = new \DOMDocument('1.0', 'UTF-8');
		$xml->formatOutput = true;

		// Root element
		$tables = $xml->createElement('tables');
		$tables = $xml->appendChild($tables);

		// Add the tables
		$this->addTableTlTheme($xml, $tables, $objTheme);
		$this->addTableTlStyleSheet($xml, $tables, $objTheme);
		$this->addTableTlImageSize($xml, $tables, $objTheme);
		$this->addTableTlModule($xml, $tables, $objTheme);
		$this->addTableTlLayout($xml, $tables, $objTheme);

		// Generate the archive
		$strTmp = md5(uniqid(mt_rand(), true));
		$objArchive = new \ZipWriter('system/tmp/'. $strTmp);

		// Add the folders
		$arrFolders = deserialize($objTheme->folders);

		if (!empty($arrFolders) && is_array($arrFolders))
		{
			$objFolders = \FilesModel::findMultipleByUuids($arrFolders);

			if ($objFolders !== null)
			{
				foreach ($this->eliminateNestedPaths($objFolders->fetchEach('path')) as $strFolder)
				{
					$this->addFolderToArchive($objArchive, $strFolder);
				}
			}
		}

		// Add the template files
		$this->addTemplatesToArchive($objArchive, $objTheme->templates);

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['exportTheme']) && is_array($GLOBALS['TL_HOOKS']['exportTheme']))
		{
			foreach ($GLOBALS['TL_HOOKS']['exportTheme'] as $callback)
			{
				\System::importStatic($callback[0])->$callback[1]($xml, $objArchive, $objTheme->id);
			}
		}

		// Add the XML document
		$objArchive->addString($xml->saveXML(), 'theme.xml');

		// Close the archive
		$objArchive->close();

		// Open the "save as …" dialogue
		$objFile = new \File('system/tmp/'. $strTmp, true);
		$objFile->sendToBrowser($strName . '.cto');
	}


	/**
	 * Add the table tl_theme
	 * @param \DOMDocument
	 * @param \DOMElement
	 * @param \Database\Result
	 */
	protected function addTableTlTheme(\DOMDocument $xml, \DOMElement $tables, \Database\Result $objTheme)
	{
		// Add the table
		$table = $xml->createElement('table');
		$table->setAttribute('name', 'tl_theme');
		$table = $tables->appendChild($table);

		// Load the DCA
		$this->loadDataContainer('tl_theme');

		// Get the order fields
		$objDcaExtractor = \DcaExtractor::getInstance('tl_theme');
		$arrOrder = $objDcaExtractor->getOrderFields();

		// Add the row
		$this->addDataRow($xml, $table, $objTheme, $arrOrder);
	}


	/**
	 * Add the table tl_style_sheet
	 * @param \DOMDocument
	 * @param \DOMElement
	 * @param \Database\Result
	 */
	protected function addTableTlStyleSheet(\DOMDocument $xml, \DOMElement $tables, \Database\Result $objTheme)
	{
		// Add the table
		$table = $xml->createElement('table');
		$table->setAttribute('name', 'tl_style_sheet');
		$table = $tables->appendChild($table);

		// Load the DCA
		$this->loadDataContainer('tl_style_sheet');

		// Get the order fields
		$objDcaExtractor = \DcaExtractor::getInstance('tl_style_sheet');
		$arrOrder = $objDcaExtractor->getOrderFields();

		// Get all style sheets
		$objStyleSheet = $this->Database->prepare("SELECT * FROM tl_style_sheet WHERE pid=? ORDER BY name")
										->execute($objTheme->id);

		// Add the rows
		while ($objStyleSheet->next())
		{
			$this->addDataRow($xml, $table, $objStyleSheet, $arrOrder);
		}

		$objStyleSheet->reset();

		// Add the child table
		$table = $xml->createElement('table');
		$table->setAttribute('name', 'tl_style');
		$table = $tables->appendChild($table);

		// Load the DCA
		$this->loadDataContainer('tl_style');

		// Get the order fields
		$objDcaExtractor = \DcaExtractor::getInstance('tl_style');
		$arrOrder = $objDcaExtractor->getOrderFields();

		// Add the child rows
		while ($objStyleSheet->next())
		{
			// Get all format definitions
			$objStyle = $this->Database->prepare("SELECT * FROM tl_style WHERE pid=? ORDER BY sorting")
									   ->execute($objStyleSheet->id);

			// Add the rows
			while ($objStyle->next())
			{
				$this->addDataRow($xml, $table, $objStyle, $arrOrder);
			}
		}
	}


	/**
	 * Add the table tl_module
	 * @param \DOMDocument
	 * @param \DOMElement
	 * @param \Database\Result
	 */
	protected function addTableTlModule(\DOMDocument $xml, \DOMElement $tables, \Database\Result $objTheme)
	{
		// Add the table
		$table = $xml->createElement('table');
		$table->setAttribute('name', 'tl_module');
		$table = $tables->appendChild($table);

		// Load the DCA
		$this->loadDataContainer('tl_module');

		// Get the order fields
		$objDcaExtractor = \DcaExtractor::getInstance('tl_module');
		$arrOrder = $objDcaExtractor->getOrderFields();

		// Get all modules
		$objModule = $this->Database->prepare("SELECT * FROM tl_module WHERE pid=? ORDER BY name")
									->execute($objTheme->id);

		// Add the rows
		while ($objModule->next())
		{
			$this->addDataRow($xml, $table, $objModule, $arrOrder);
		}
	}


	/**
	 * Add the table tl_layout
	 * @param \DOMDocument
	 * @param \DOMElement
	 * @param \Database\Result
	 */
	protected function addTableTlLayout(\DOMDocument $xml, \DOMElement $tables, \Database\Result $objTheme)
	{
		// Add the table
		$table = $xml->createElement('table');
		$table->setAttribute('name', 'tl_layout');
		$table = $tables->appendChild($table);

		// Load the DCA
		$this->loadDataContainer('tl_layout');

		// Get the order fields
		$objDcaExtractor = \DcaExtractor::getInstance('tl_layout');
		$arrOrder = $objDcaExtractor->getOrderFields();

		// Get all layouts
		$objLayout = $this->Database->prepare("SELECT * FROM tl_layout WHERE pid=? ORDER BY name")
									->execute($objTheme->id);

		// Add the rows
		while ($objLayout->next())
		{
			$this->addDataRow($xml, $table, $objLayout, $arrOrder);
		}
	}


	/**
	 * Add the table tl_image_size
	 * @param \DOMDocument
	 * @param \DOMElement
	 * @param \Database\Result
	 */
	protected function addTableTlImageSize(\DOMDocument $xml, \DOMElement $tables, \Database\Result $objTheme)
	{
		// Add the tables
		$imageSizeTable = $xml->createElement('table');
		$imageSizeTable->setAttribute('name', 'tl_image_size');
		$imageSizeTable = $tables->appendChild($imageSizeTable);

		$imageSizeItemTable = $xml->createElement('table');
		$imageSizeItemTable->setAttribute('name', 'tl_image_size_item');
		$imageSizeItemTable = $tables->appendChild($imageSizeItemTable);

		// Get all sizes
		$objSizes = $this->Database->prepare("SELECT * FROM tl_image_size WHERE pid=?")
								   ->execute($objTheme->id);

		// Add the rows
		while ($objSizes->next())
		{
			$this->addDataRow($xml, $imageSizeTable, $objSizes);

			// Get all size items
			$objSizeItems = $this->Database->prepare("SELECT * FROM tl_image_size_item WHERE pid=?")
										   ->execute($objSizes->id);

			// Add the rows
			while ($objSizeItems->next())
			{
				$this->addDataRow($xml, $imageSizeItemTable, $objSizeItems);
			}
		}
	}


	/**
	 * Add a data row to the XML document
	 * @param \DOMDocument
	 * @param \DOMElement
	 * @param \Database\Result
	 * @param array
	 */
	protected function addDataRow(\DOMDocument $xml, \DOMElement $table, \Database\Result $objData, array $arrOrder=array())
	{
		$t = $table->getAttribute('name');

		$row = $xml->createElement('row');
		$row = $table->appendChild($row);

		foreach ($objData->row() as $k=>$v)
		{
			$field = $xml->createElement('field');
			$field->setAttribute('name', $k);
			$field = $row->appendChild($field);

			if ($v === null)
			{
				$v = 'NULL';
			}

			// Replace the IDs of singleSRC fields with their path (see #4952)
			elseif ($GLOBALS['TL_DCA'][$t]['fields'][$k]['inputType'] == 'fileTree' && !$GLOBALS['TL_DCA'][$t]['fields'][$k]['eval']['multiple'])
			{
				$objFile = \FilesModel::findByUuid($v);

				if ($objFile !== null)
				{
					$v = $this->standardizeUploadPath($objFile->path);
				}
				else
				{
					$v = 'NULL';
				}
			}

			// Replace the IDs of multiSRC fields with their paths (see #4952)
			elseif ($GLOBALS['TL_DCA'][$t]['fields'][$k]['inputType'] == 'fileTree' || in_array($k, $arrOrder))
			{
				$arrFiles = deserialize($v);

				if (!empty($arrFiles) && is_array($arrFiles))
				{
					$objFiles = \FilesModel::findMultipleByUuids($arrFiles);

					if ($objFiles !== null)
					{
						$arrTmp = array();

						while ($objFiles->next())
						{
							$arrTmp[] = $this->standardizeUploadPath($objFiles->path);
						}

						$v = serialize($arrTmp);
					}
					else
					{
						$v = 'NULL';
					}
				}
			}
			elseif ($t == 'tl_style' && ($k == 'bgimage' || $k == 'liststyleimage'))
			{
				$v = $this->standardizeUploadPath($v);
			}

			$value = $xml->createTextNode($v);
			$field->appendChild($value);
		}
	}


	/**
	 * Recursively add a folder to the archive
	 * @param \ZipWriter
	 * @param string
	 */
	protected function addFolderToArchive(\ZipWriter $objArchive, $strFolder)
	{
		// Strip the custom upload folder name
		$strFolder = preg_replace('@^'.preg_quote(\Config::get('uploadPath'), '@').'/@', '', $strFolder);

		// Add the default upload folder name
		if ($strFolder == '')
		{
			$strTarget = 'files';
			$strFolder = \Config::get('uploadPath');
		}
		else
		{
			$strTarget = 'files/' . $strFolder;
			$strFolder = \Config::get('uploadPath') .'/'. $strFolder;
		}

		if (\Validator::isInsecurePath($strFolder))
		{
			throw new \RuntimeException('Insecure path ' . $strFolder);
		}

		// Return if the folder does not exist
		if (!is_dir(TL_ROOT .'/'. $strFolder))
		{
			return;
		}

		// Recursively add the files and subfolders
		foreach (scan(TL_ROOT .'/'. $strFolder) as $strFile)
		{
			// Skip hidden resources
			if ($strFile == '.svn' || $strFile == '.DS_Store')
			{
				continue;
			}

			if (is_dir(TL_ROOT .'/'. $strFolder .'/'. $strFile))
			{
				$this->addFolderToArchive($objArchive, $strFolder .'/'. $strFile);
			}
			else
			{
				// Always store files in files and convert the directory upon import
				$objArchive->addFile($strFolder .'/'. $strFile, $strTarget .'/'. $strFile);
			}
		}
	}


	/**
	 * Add templates to the archive
	 * @param \ZipWriter
	 * @param string
	 */
	protected function addTemplatesToArchive(\ZipWriter $objArchive, $strFolder)
	{
		// Strip the templates folder name
		$strFolder = preg_replace('@^templates/@', '', $strFolder);

		// Re-add the templates folder name
		if ($strFolder == '')
		{
			$strFolder = 'templates';
		}
		else
		{
			$strFolder = 'templates/' . $strFolder;
		}

		if (\Validator::isInsecurePath($strFolder))
		{
			throw new \RuntimeException('Insecure path ' . $strFolder);
		}

		// Return if the folder does not exist
		if (!is_dir(TL_ROOT .'/'. $strFolder))
		{
			return;
		}

		$arrAllowed = trimsplit(',', \Config::get('templateFiles'));
		array_push($arrAllowed, 'sql'); // see #7048

		// Add all template files to the archive
		foreach (scan(TL_ROOT .'/'. $strFolder) as $strFile)
		{
			if (preg_match('/\.(' . implode('|', $arrAllowed) . ')$/', $strFile) && strncmp($strFile, 'be_', 3) !== 0 && strncmp($strFile, 'nl_', 3) !== 0)
			{
				$objArchive->addFile($strFolder .'/'. $strFile);
			}
		}
	}


	/**
	 * Replace files/ with the custom upload folder name
	 * @param string
	 * @return string
	 */
	protected function customizeUploadPath($strPath)
	{
		if ($strPath == '')
		{
			return '';
		}

		return preg_replace('@^(tl_)?files/@', \Config::get('uploadPath') . '/', $strPath);
	}


	/**
	 * Replace a custom upload folder name with files/
	 * @param string
	 * @return string
	 */
	protected function standardizeUploadPath($strPath)
	{
		if ($strPath == '')
		{
			return '';
		}

		return preg_replace('@^' . preg_quote(\Config::get('uploadPath'), '@') . '/@', 'files/', $strPath);
	}
}
