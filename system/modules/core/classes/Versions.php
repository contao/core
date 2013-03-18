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
	 * Compare versions
	 * @param string
	 * @param integer
	 * @return string
	 */
	public function compare($strTable, $intPid)
	{
		$strBuffer = '';
		$arrVersions = array();
		$intTo = 0;
		$intFrom = 0;

		$objVersions = $this->Database->prepare("SELECT * FROM tl_version WHERE pid=? AND fromTable=? ORDER BY version DESC")
									  ->execute($intPid, $strTable);

		if ($objVersions->numRows < 1)
		{
			$strBuffer = 'There are no versions of ' . $strTable . '.id=' . $intPid;
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

			\System::loadLanguageFile($strTable);
			$this->loadDataContainer($strTable);

			// Include the PhpDiff library
			require_once TL_ROOT . '/system/modules/core/vendor/phpdiff/Diff.php';
			require_once TL_ROOT . '/system/modules/core/vendor/phpdiff/Diff/Renderer/Html/Contao.php';

			$arrFields = $GLOBALS['TL_DCA'][$strTable]['fields'];

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
		elseif (!is_array(next($var)))
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
