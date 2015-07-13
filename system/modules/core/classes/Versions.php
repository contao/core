<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Provide methods to handle versioning.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Versions extends \Controller
{

	/**
	 * Table
	 * @var string
	 */
	protected $strTable;

	/**
	 * Parent ID
	 * @var integer
	 */
	protected $intPid;

	/**
	 * File path
	 * @var string
	 */
	protected $strPath;

	/**
	 * Edit URL
	 * @var string
	 */
	protected $strEditUrl;

	/**
	 * Username
	 * @var string
	 */
	protected $strUsername;

	/**
	 * User ID
	 * @var integer
	 */
	protected $intUserId;


	/**
	 * Initialize the object
	 *
	 * @param string  $strTable
	 * @param integer $intPid
	 */
	public function __construct($strTable, $intPid)
	{
		$this->import('Database');
		parent::__construct();

		$this->strTable = $strTable;
		$this->intPid = $intPid;

		// Store the path if it is an editable file
		if ($strTable == 'tl_files')
		{
			$objFile = \FilesModel::findByPk($intPid);

			if ($objFile !== null && in_array($objFile->extension, trimsplit(',', \Config::get('editableFiles'))))
			{
				$this->strPath = $objFile->path;
			}
		}
	}


	/**
	 * Set the edit URL
	 *
	 * @param string $strEditUrl
	 */
	public function setEditUrl($strEditUrl)
	{
		$this->strEditUrl = $strEditUrl;
	}


	/**
	 * Set the username
	 *
	 * @param string $strUsername
	 */
	public function setUsername($strUsername)
	{
		$this->strUsername = $strUsername;
	}


	/**
	 * Set the user ID
	 *
	 * @param integer $intUserId
	 */
	public function setUserId($intUserId)
	{
		$this->intUserId = $intUserId;
	}


	/**
	 * Create the initial version of a record
	 */
	public function initialize()
	{
		if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['enableVersioning'])
		{
			return;
		}

		$objVersion = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_version WHERE fromTable=? AND pid=?")
									 ->limit(1)
									 ->execute($this->strTable, $this->intPid);

		if ($objVersion->count < 1)
		{
			$this->create();
		}
	}


	/**
	 * Create a new version of a record
	 */
	public function create()
	{
		if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['enableVersioning'])
		{
			return;
		}

		// Delete old versions from the database
		$tstamp = time() - intval(\Config::get('versionPeriod'));
		$this->Database->query("DELETE FROM tl_version WHERE tstamp<$tstamp");

		// Get the new record
		$objRecord = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE id=?")
									->limit(1)
									->execute($this->intPid);

		if ($objRecord->numRows < 1 || $objRecord->tstamp < 1)
		{
			return;
		}

		if ($this->strPath !== null)
		{
			$objFile = new \File($this->strPath, true);

			if ($objFile->extension == 'svgz')
			{
				$objRecord->content = gzdecode($objFile->getContent());
			}
			else
			{
				$objRecord->content = $objFile->getContent();
			}
		}

		$intVersion = 1;

		$objVersion = $this->Database->prepare("SELECT MAX(version) AS version FROM tl_version WHERE pid=? AND fromTable=?")
									 ->execute($this->intPid, $this->strTable);

		if ($objVersion->version !== null)
		{
			$intVersion = $objVersion->version + 1;
		}

		$strDescription = '';

		if (isset($objRecord->title))
		{
			$strDescription = $objRecord->title;
		}
		elseif (isset($objRecord->name))
		{
			$strDescription = $objRecord->name;
		}
		elseif (isset($objRecord->firstname))
		{
			$strDescription = $objRecord->firstname . ' ' . $objRecord->lastname;
		}
		elseif (isset($objRecord->headline))
		{
			$strDescription = $objRecord->headline;
		}
		elseif (isset($objRecord->selector))
		{
			$strDescription = $objRecord->selector;
		}
		elseif (isset($objRecord->subject))
		{
			$strDescription = $objRecord->subject;
		}

		$this->Database->prepare("UPDATE tl_version SET active='' WHERE pid=? AND fromTable=?")
					   ->execute($this->intPid, $this->strTable);

		$this->Database->prepare("INSERT INTO tl_version (pid, tstamp, version, fromTable, username, userid, description, editUrl, active, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)")
					   ->execute($this->intPid, ($intVersion == 1 ? $objRecord->tstamp : time()), $intVersion, $this->strTable, $this->getUsername(), $this->getUserId(), $strDescription, $this->getEditUrl(), serialize($objRecord->row()));
	}


	/**
	 * Restore a version
	 *
	 * @param integer $intVersion
	 */
	public function restore($intVersion)
	{
		if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['enableVersioning'])
		{
			return;
		}

		$objData = $this->Database->prepare("SELECT * FROM tl_version WHERE fromTable=? AND pid=? AND version=?")
								  ->limit(1)
								  ->execute($this->strTable, $this->intPid, $intVersion);

		if ($objData->numRows)
		{
			$data = deserialize($objData->data);

			if (is_array($data))
			{
				// Restore the content
				if ($this->strPath !== null)
				{
					$objFile = new \File($this->strPath, true);
					$objFile->write($data['content']);
					$objFile->close();
				}

				// Get the currently available fields
				$arrFields = array_flip($this->Database->getFieldnames($this->strTable));

				// Unset fields that do not exist (see #5219)
				$data = array_intersect_key($data, $arrFields);

				$this->loadDataContainer($this->strTable);

				// Reset fields added after storing the version to their default value (see #7755)
				foreach (array_diff_key($arrFields, $data) as $k=>$v)
				{
					$data[$k] = \Widget::getEmptyValueByFieldType($GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['sql']);
				}

				$this->Database->prepare("UPDATE " . $objData->fromTable . " %s WHERE id=?")
							   ->set($data)
							   ->execute($this->intPid);

				$this->Database->prepare("UPDATE tl_version SET active='' WHERE pid=?")
							   ->execute($this->intPid);

				$this->Database->prepare("UPDATE tl_version SET active=1 WHERE pid=? AND version=?")
							   ->execute($this->intPid, $intVersion);

				$this->log('Version '.$intVersion.' of record "'.$this->strTable.'.id='.$this->intPid.'" has been restored'.$this->getParentEntries($this->strTable, $this->intPid), __METHOD__, TL_GENERAL);

				// Trigger the onrestore_callback
				if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onrestore_callback']))
				{
					foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onrestore_callback'] as $callback)
					{
						if (is_array($callback))
						{
							$this->import($callback[0]);
							$this->$callback[0]->$callback[1]($this->intPid, $this->strTable, $data, $intVersion);
						}
						elseif (is_callable($callback))
						{
							$callback($this->intPid, $this->strTable, $data, $intVersion);
						}
					}
				}
			}
		}
	}


	/**
	 * Compare versions
	 */
	public function compare()
	{
		$strBuffer = '';
		$arrVersions = array();
		$intTo = 0;
		$intFrom = 0;

		$objVersions = $this->Database->prepare("SELECT * FROM tl_version WHERE pid=? AND fromTable=? ORDER BY version DESC")
									  ->execute($this->intPid, $this->strTable);

		if ($objVersions->numRows < 2)
		{
			$strBuffer = '<p>There are no versions of ' . $this->strTable . '.id=' . $this->intPid . '</p>';
		}
		else
		{
			$intIndex = 0;
			$from = array();

			// Store the versions and mark the active one
			while ($objVersions->next())
			{
				if ($objVersions->active)
				{
					$intIndex = $objVersions->version;
				}

				$arrVersions[$objVersions->version] = $objVersions->row();
				$arrVersions[$objVersions->version]['info'] = $GLOBALS['TL_LANG']['MSC']['version'].' '.$objVersions->version.' ('.\Date::parse(\Config::get('datimFormat'), $objVersions->tstamp).') '.$objVersions->username;
			}

			// To
			if (\Input::post('to') && isset($arrVersions[\Input::post('to')]))
			{
				$intTo = \Input::post('to');
				$to = deserialize($arrVersions[\Input::post('to')]['data']);
			}
			elseif (\Input::get('to') && isset($arrVersions[\Input::get('to')]))
			{
				$intTo = \Input::get('to');
				$to = deserialize($arrVersions[\Input::get('to')]['data']);
			}
			else
			{
				$intTo = $intIndex;
				$to = deserialize($arrVersions[$intTo]['data']);
			}

			// From
			if (\Input::post('from') && isset($arrVersions[\Input::post('from')]))
			{
				$intFrom = \Input::post('from');
				$from = deserialize($arrVersions[\Input::post('from')]['data']);
			}
			elseif (\Input::get('from') && isset($arrVersions[\Input::get('from')]))
			{
				$intFrom = \Input::get('from');
				$from = deserialize($arrVersions[\Input::get('from')]['data']);
			}
			elseif ($intIndex > 1)
			{
				$intFrom = $intIndex-1;
				$from = deserialize($arrVersions[$intFrom]['data']);
			}

			// Only continue if both version numbers are set
			if ($intTo > 0 && $intFrom > 0)
			{
				\System::loadLanguageFile($this->strTable);
				$this->loadDataContainer($this->strTable);

				// Get the order fields
				$objDcaExtractor = \DcaExtractor::getInstance($this->strTable);
				$arrOrder = $objDcaExtractor->getOrderFields();

				// Find the changed fields and highlight the changes
				foreach ($to as $k=>$v)
				{
					if ($from[$k] != $to[$k])
					{
						if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['inputType'] == 'password' || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['eval']['doNotShow'] || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['eval']['hideInput'])
						{
							continue;
						}

						$blnIsBinary = ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['inputType'] == 'fileTree' || in_array($k, $arrOrder));

						// Decrypt the values
						if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['eval']['encrypt'])
						{
							$to[$k] = \Encryption::decrypt($to[$k]);
							$from[$k] = \Encryption::decrypt($from[$k]);
						}

						// Convert serialized arrays into strings
						if (is_array(($tmp = deserialize($to[$k]))) && !is_array($to[$k]))
						{
							$to[$k] = $this->implodeRecursive($tmp, $blnIsBinary);
						}
						if (is_array(($tmp = deserialize($from[$k]))) && !is_array($from[$k]))
						{
							$from[$k] = $this->implodeRecursive($tmp, $blnIsBinary);
						}

						unset($tmp);

						// Convert binary UUIDs to their hex equivalents (see #6365)
						if ($blnIsBinary && \Validator::isBinaryUuid($to[$k]))
						{
							$to[$k] = \String::binToUuid($to[$k]);
						}
						if ($blnIsBinary && \Validator::isBinaryUuid($from[$k]))
						{
							$to[$k] = \String::binToUuid($from[$k]);
						}

						// Convert date fields
						if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['eval']['rgxp'] == 'date')
						{
							$to[$k] = \Date::parse(\Config::get('dateFormat'), $to[$k] ?: '');
							$from[$k] = \Date::parse(\Config::get('dateFormat'), $from[$k] ?: '');
						}
						elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['eval']['rgxp'] == 'time')
						{
							$to[$k] = \Date::parse(\Config::get('timeFormat'), $to[$k] ?: '');
							$from[$k] = \Date::parse(\Config::get('timeFormat'), $from[$k] ?: '');
						}
						elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['eval']['rgxp'] == 'datim' || $k == 'tstamp')
						{
							$to[$k] = \Date::parse(\Config::get('datimFormat'), $to[$k] ?: '');
							$from[$k] = \Date::parse(\Config::get('datimFormat'), $from[$k] ?: '');
						}

						// Convert strings into arrays
						if (!is_array($to[$k]))
						{
							$to[$k] = explode("\n", $to[$k]);
						}
						if (!is_array($from[$k]))
						{
							$from[$k] = explode("\n", $from[$k]);
						}

						$objDiff = new \Diff($from[$k], $to[$k]);
						$strBuffer .= $objDiff->Render(new DiffRenderer(array('field'=>($GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['label'][0] ?: (isset($GLOBALS['TL_LANG']['MSC'][$k]) ? (is_array($GLOBALS['TL_LANG']['MSC'][$k]) ? $GLOBALS['TL_LANG']['MSC'][$k][0] : $GLOBALS['TL_LANG']['MSC'][$k]) : $k)))));
					}
				}
			}
		}

		// Identical versions
		if ($strBuffer == '')
		{
			$strBuffer = '<p>'.$GLOBALS['TL_LANG']['MSC']['identicalVersions'].'</p>';
		}

		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_diff');

		// Template variables
		$objTemplate->content = $strBuffer;
		$objTemplate->versions = $arrVersions;
		$objTemplate->to = $intTo;
		$objTemplate->from = $intFrom;
		$objTemplate->showLabel = specialchars($GLOBALS['TL_LANG']['MSC']['showDifferences']);
		$objTemplate->theme = \Backend::getTheme();
		$objTemplate->base = \Environment::get('base');
		$objTemplate->language = $GLOBALS['TL_LANGUAGE'];
		$objTemplate->title = specialchars($GLOBALS['TL_LANG']['MSC']['showDifferences']);
		$objTemplate->charset = \Config::get('characterSet');
		$objTemplate->action = ampersand(\Environment::get('request'));

		\Config::set('debugMode', false);
		$objTemplate->output();

		exit;
	}


	/**
	 * Render the versions dropdown menu
	 *
	 * @return string
	 */
	public function renderDropdown()
	{
		$objVersion = $this->Database->prepare("SELECT tstamp, version, username, active FROM tl_version WHERE fromTable=? AND pid=? ORDER BY version DESC")
								     ->execute($this->strTable, $this->intPid);

		if ($objVersion->numRows < 2)
		{
			return '';
		}

		$versions = '';

		while ($objVersion->next())
		{
			$versions .= '
  <option value="'.$objVersion->version.'"'.($objVersion->active ? ' selected="selected"' : '').'>'.$GLOBALS['TL_LANG']['MSC']['version'].' '.$objVersion->version.' ('.\Date::parse(\Config::get('datimFormat'), $objVersion->tstamp).') '.$objVersion->username.'</option>';
		}

		return '
<div class="tl_version_panel">

<form action="'.ampersand(\Environment::get('request'), true).'" id="tl_version" class="tl_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_version">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<select name="version" class="tl_select">'.$versions.'
</select>
<input type="submit" name="showVersion" id="showVersion" class="tl_submit" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['restore']).'">
<a href="'.$this->addToUrl('versions=1&amp;popup=1').'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['showDifferences']).'" onclick="Backend.openModalIframe({\'width\':768,\'title\':\''.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MSC']['showDifferences'])).'\',\'url\':this.href});return false">'.\Image::getHtml('diff.gif').'</a>
</div>
</form>

</div>
';
	}


	/**
	 * Add a list of versions to a template
	 *
	 * @param \BackendTemplate|object $objTemplate
	 */
	public static function addToTemplate(\BackendTemplate $objTemplate)
	{
		$arrVersions = array();

		$objUser = \BackendUser::getInstance();
		$objDatabase = \Database::getInstance();

		// Get the total number of versions
		$objTotal = $objDatabase->prepare("SELECT COUNT(*) AS count FROM tl_version WHERE version>1" . (!$objUser->isAdmin ? " AND userid=?" : ""))
								->execute($objUser->id);

		$intLast   = ceil($objTotal->count / 30);
		$intPage   = (\Input::get('vp') !== null) ? \Input::get('vp') : 1;
		$intOffset = ($intPage - 1) * 30;

		// Validate the page number
		if ($intPage < 1 || ($intLast > 0 && $intPage > $intLast))
		{
			header('HTTP/1.1 404 Not Found');
		}

		// Create the pagination menu
		$objPagination = new \Pagination($objTotal->count, 30, 7, 'vp', new \BackendTemplate('be_pagination'));
		$objTemplate->pagination = $objPagination->generate();

		// Get the versions
		$objVersions = $objDatabase->prepare("SELECT pid, tstamp, version, fromTable, username, userid, description, editUrl, active FROM tl_version" . (!$objUser->isAdmin ? " WHERE userid=?" : "") . " ORDER BY tstamp DESC, pid, version DESC")
								   ->limit(30, $intOffset)
								   ->execute($objUser->id);

		while ($objVersions->next())
		{
			$arrRow = $objVersions->row();

			// Add some parameters
			$arrRow['from'] = max(($objVersions->version - 1), 1); // see #4828
			$arrRow['to'] = $objVersions->version;
			$arrRow['date'] = date(\Config::get('datimFormat'), $objVersions->tstamp);
			$arrRow['description'] = \String::substr($arrRow['description'], 32);
			$arrRow['shortTable'] = \String::substr($arrRow['fromTable'], 18); // see #5769

			if ($arrRow['editUrl'] != '')
			{
				$arrRow['editUrl'] = preg_replace('/&(amp;)?rt=[a-f0-9]+/', '&amp;rt=' . REQUEST_TOKEN, ampersand($arrRow['editUrl']));
			}

			$arrVersions[] = $arrRow;
		}

		$intCount = -1;
		$arrVersions = array_values($arrVersions);

		// Add the "even" and "odd" classes
		foreach ($arrVersions as $k=>$v)
		{
			$arrVersions[$k]['class'] = (++$intCount%2 == 0) ? 'even' : 'odd';

			try
			{
				// Mark deleted versions (see #4336)
				$objDeleted = $objDatabase->prepare("SELECT COUNT(*) AS count FROM " . $v['fromTable'] . " WHERE id=?")
										  ->execute($v['pid']);

				$arrVersions[$k]['deleted'] = ($objDeleted->count < 1);
			}
			catch (\Exception $e)
			{
				// Probably a disabled module
				--$intCount;
				unset($arrVersions[$k]);
			}
		}

		$objTemplate->versions = $arrVersions;
	}


	/**
	 * Return the edit URL
	 *
	 * @return string
	 */
	protected function getEditUrl()
	{
		if ($this->strEditUrl !== null)
		{
			return sprintf($this->strEditUrl, $this->intPid);
		}

		$strUrl = \Environment::get('request');

		// Save the real edit URL if the visibility is toggled via Ajax
		if (preg_match('/&(amp;)?state=/', $strUrl))
		{
			$strUrl = preg_replace
			(
				array('/&(amp;)?id=[^&]+/', '/(&(amp;)?)t(id=[^&]+)/', '/(&(amp;)?)state=[^&]*/'),
				array('', '$1$3', '$1act=edit'), $strUrl
			);
		}

		// Correct the URL in "edit|override multiple" mode (see #7745)
		$strUrl = preg_replace('/act=(edit|override)All/', 'act=edit&id=' . $this->intPid, $strUrl);

		return $strUrl;
	}


	/**
	 * Return the username
	 *
	 * @return string
	 */
	protected function getUsername()
	{
		if ($this->strUsername !== null)
		{
			return $this->strUsername;
		}

		$this->import('BackendUser', 'User');

		return $this->User->username;
	}


	/**
	 * Return the user ID
	 *
	 * @return string
	 */
	protected function getUserId()
	{
		if ($this->intUserId !== null)
		{
			return $this->intUserId;
		}

		$this->import('BackendUser', 'User');

		return $this->User->id;
	}


	/**
	 * Implode a multi-dimensional array recursively
	 *
	 * @param mixed   $var
	 * @param boolean $binary
	 *
	 * @return string
	 */
	protected function implodeRecursive($var, $binary=false)
	{
		if (!is_array($var))
		{
			return $binary ? \String::binToUuid($var) : $var;
		}
		elseif (!is_array(current($var)))
		{
			return implode(', ', ($binary ? array_map('String::binToUuid', $var) : $var));
		}
		else
		{
			$buffer = '';

			foreach ($var as $k=>$v)
			{
				$buffer .= $k . ": " . $this->implodeRecursive($v) . "\n";
			}

			return trim($buffer);
		}
	}
}
