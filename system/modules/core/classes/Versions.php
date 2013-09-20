<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
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
 * Class Versions
 *
 * Provide methods to handle versioning.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class Versions extends \Backend
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
	 * Initialize the object
	 * @param string
	 * @param integer
	 */
	public function __construct($strTable, $intPid)
	{
		parent::__construct();
		$this->strTable = $strTable;
		$this->intPid = $intPid;
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
		$tstamp = time() - intval($GLOBALS['TL_CONFIG']['versionPeriod']);
		$this->Database->query("DELETE FROM tl_version WHERE tstamp<$tstamp");

		// Get the new record
		$objRecord = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE id=?")
									->limit(1)
									->execute($this->intPid);

		if ($objRecord->numRows < 1 || $objRecord->tstamp < 1)
		{
			return;
		}

		$intVersion = 1;
		$this->import('BackendUser', 'User');

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
		elseif (isset($objRecord->headline))
		{
			$strDescription = $objRecord->headline;
		}
		elseif (isset($objRecord->selector))
		{
			$strDescription = $objRecord->selector;
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

		$this->Database->prepare("UPDATE tl_version SET active='' WHERE pid=? AND fromTable=?")
					   ->execute($this->intPid, $this->strTable);

		$this->Database->prepare("INSERT INTO tl_version (pid, tstamp, version, fromTable, username, userid, description, editUrl, active, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)")
					   ->execute($this->intPid, time(), $intVersion, $this->strTable, $this->User->username, $this->User->id, $strDescription, $strUrl, serialize($objRecord->row()));
	}


	/**
	 * Restore a version
	 * @param integer
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
				// Get the currently available fields
				$arrFields = array_flip($this->Database->getFieldnames($this->strTable));

				// Unset fields that do not exist (see #5219)
				foreach (array_keys($data) as $k)
				{
					if (!isset($arrFields[$k]))
					{
						unset($data[$k]);
					}
				}

				$this->Database->prepare("UPDATE " . $objData->fromTable . " %s WHERE id=?")
							   ->set($data)
							   ->execute($this->intPid);

				$this->Database->prepare("UPDATE tl_version SET active='' WHERE pid=?")
							   ->execute($this->intPid);

				$this->Database->prepare("UPDATE tl_version SET active=1 WHERE pid=? AND version=?")
							   ->execute($this->intPid, $intVersion);

				$this->log('Version '.$intVersion.' of record "'.$this->strTable.'.id='.$this->intPid.'" has been restored'.$this->getParentEntries($this->strTable, $this->intPid), 'Versions activate()', TL_GENERAL);

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

		if ($objVersions->numRows < 1)
		{
			$strBuffer = 'There are no versions of ' . $this->strTable . '.id=' . $this->intPid;
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
				$arrVersions[$objVersions->version]['info'] = $GLOBALS['TL_LANG']['MSC']['version'].' '.$objVersions->version.' ('.\Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $objVersions->tstamp).') '.$objVersions->username;
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

			\System::loadLanguageFile($this->strTable);
			$this->loadDataContainer($this->strTable);

			// Include the PhpDiff library
			require_once TL_ROOT . '/system/modules/core/vendor/phpdiff/Diff.php';
			require_once TL_ROOT . '/system/modules/core/vendor/phpdiff/Diff/Renderer/Html/Contao.php';

			$arrFields = $GLOBALS['TL_DCA'][$this->strTable]['fields'];

			// Find the changed fields and highlight the changes
			foreach ($to as $k=>$v)
			{
				if ($from[$k] != $to[$k])
				{
					if (!isset($arrFields[$k]['inputType']) || $arrFields[$k]['inputType'] == 'password' || $arrFields[$k]['eval']['doNotShow'] || $arrFields[$k]['eval']['hideInput'])
					{
						continue;
					}

					// Convert serialized arrays into strings
					if (is_array(($tmp = deserialize($to[$k]))) && !is_array($to[$k]))
					{
						$to[$k] = $this->implodeRecursive($tmp);
					}
					if (is_array(($tmp = deserialize($from[$k]))) && !is_array($from[$k]))
					{
						$from[$k] = $this->implodeRecursive($tmp);
					}
					unset($tmp);

					// Convert date fields
					if ($arrFields[$k]['eval']['rgxp'] == 'date')
					{
						$to[$k] = \Date::parse($GLOBALS['TL_CONFIG']['dateFormat'], $to[$k] ?: '');
						$from[$k] = \Date::parse($GLOBALS['TL_CONFIG']['dateFormat'], $from[$k] ?: '');
					}
					elseif ($arrFields[$k]['eval']['rgxp'] == 'time')
					{
						$to[$k] = \Date::parse($GLOBALS['TL_CONFIG']['timeFormat'], $to[$k] ?: '');
						$from[$k] = \Date::parse($GLOBALS['TL_CONFIG']['timeFormat'], $from[$k] ?: '');
					}
					elseif ($arrFields[$k]['eval']['rgxp'] == 'datim')
					{
						$to[$k] = \Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $to[$k] ?: '');
						$from[$k] = \Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $from[$k] ?: '');
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
					$strBuffer .= $objDiff->Render(new \Diff_Renderer_Html_Contao(array('field'=>($arrFields[$k]['label'][0] ?: $k))));
				}
			}
		}

		// Identical versions
		if ($strBuffer == '')
		{
			$strBuffer = '<p>'.$GLOBALS['TL_LANG']['MSC']['identicalVersions'].'</p>';
		}

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
		$objTemplate->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$objTemplate->action = ampersand(\Environment::get('request'));

		$GLOBALS['TL_CONFIG']['debugMode'] = false;
		$objTemplate->output();
	}


	/**
	 * Render the versions dropdown menu
	 * @return string
	 */
	public function renderDropdown()
	{
		$objVersion = $this->Database->prepare("SELECT tstamp, version, username, active FROM tl_version WHERE fromTable=? AND pid=? ORDER BY version DESC")
									     ->execute($this->strTable, $this->intPid);

		if ($objVersion->numRows < 1)
		{
			return '';
		}

		$versions = '';

		while ($objVersion->next())
		{
			$versions .= '
  <option value="'.$objVersion->version.'"'.($objVersion->active ? ' selected="selected"' : '').'>'.$GLOBALS['TL_LANG']['MSC']['version'].' '.$objVersion->version.' ('.\Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $objVersion->tstamp).') '.$objVersion->username.'</option>';
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
<a href="'.$this->addToUrl('versions=1&amp;popup=1').'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['showDifferences']).'" onclick="Backend.openModalIframe({\'width\':765,\'title\':\''.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MSC']['showDifferences'])).'\',\'url\':this.href});return false">'.\Image::getHtml('diff.gif').'</a>
</div>
</form>

</div>
';
	}


	/**
	 * Add a list of versions to a template
	 * @param object
	 */
	public static function addToTemplate(\BackendTemplate $objTemplate)
	{
		$arrVersions = array();

		$objUser = \BackendUser::getInstance();
		$objDatabase = \Database::getInstance();

		// Get the total number of versions
		$objTotal = $objDatabase->prepare("SELECT COUNT(*) AS count FROM tl_version" . (!$objUser->isAdmin ? " WHERE userid=?" : ""))
							    ->execute($objUser->id);

		$intPage   = \Input::get('vp') ?: 1;
		$intOffset = ($intPage - 1) * 30;
		$intLast   = ceil($objTotal->count / 30);

		// Validate the page number
		if ($intPage < 1 || $intPage > $intLast)
		{
			header('HTTP/1.1 404 Not Found');
		}

		// Create the pagination menu
		$objPagination = new \Pagination($objTotal->count, 30, 7, 'vp', new \BackendTemplate('be_pagination'));
		$objTemplate->pagination = $objPagination->generate();

		// Get the versions
		$objVersions = $objDatabase->prepare("SELECT pid, tstamp, version, fromTable, username, userid, description, editUrl, active FROM tl_version v" . (!$objUser->isAdmin ? " WHERE userid=?" : "") . " ORDER BY tstamp DESC, pid, version DESC")
								   ->limit(30, $intOffset)
								   ->execute($objUser->id);

		while ($objVersions->next())
		{
			$arrRow = $objVersions->row();

			// Add some parameters
			$arrRow['from'] = max(($objVersions->version - 1), 1); // see #4828
			$arrRow['to'] = $objVersions->version;
			$arrRow['date'] = date($GLOBALS['TL_CONFIG']['datimFormat'], $objVersions->tstamp);
			$arrRow['description'] = \String::substr($arrRow['description'], 32);
			$arrRow['fromTable'] = \String::substr($arrRow['fromTable'], 18); // see #5769

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
	 * Implode a multi-dimensional array recursively
	 * @param mixed
	 * @return string
	 */
	protected function implodeRecursive($var)
	{
		if (!is_array($var))
		{
			return $var;
		}
		elseif (!is_array(current($var)))
		{
			return implode(', ', $var);
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
