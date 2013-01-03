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
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once '../system/initialize.php';


/**
 * Class DiffController
 *
 * Show the difference between two versions of a record.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class DiffController extends Backend
{

	/**
	 * Initialize the controller
	 *
	 * 1. Import the user
	 * 2. Call the parent constructor
	 * 3. Authenticate the user
	 * 4. Load the language files
	 * DO NOT CHANGE THIS ORDER!
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();

		$this->User->authenticate();
		$this->loadLanguageFile('default');

		// Include the PhpDiff library
		require TL_ROOT . '/system/vendor/phpdiff/Diff.php';
		require TL_ROOT . '/system/vendor/phpdiff/Diff/Renderer/Html/Contao.php';
	}


	/**
	 * Run the controller
	 */
	public function run()
	{
		$strBuffer = '';
		$arrVersions = array();
		$intTo = 0;
		$intFrom = 0;

		if (!Input::get('table') || !Input::get('pid'))
		{
			$strBuffer = 'Please provide the table name and PID';
		}
		else
		{
			$objVersions = $this->Database->prepare("SELECT * FROM tl_version WHERE pid=? AND fromTable=?" . (!$this->User->isAdmin ? " AND userid=?" : "") . " ORDER BY version DESC")
										  ->execute(Input::get('pid'), Input::get('table'), $this->User->id);

			if ($objVersions->numRows < 1)
			{
				$strBuffer = 'There are no versions of ' . Input::get('table') . '.id=' . Input::get('pid');
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
					$arrVersions[$objVersions->version]['info'] = $GLOBALS['TL_LANG']['MSC']['version'].' '.$objVersions->version.' ('.$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objVersions->tstamp).') '.$objVersions->username;
				}

				// To
				if (Input::get('to') && isset($arrVersions[Input::get('to')]))
				{
					$intTo = Input::get('to');
					$to = deserialize($arrVersions[Input::get('to')]['data']);
				}
				else
				{
					$intTo = $intIndex;
					$to = deserialize($arrVersions[$intTo]['data']);
				}

				// From
				if (Input::get('from') && isset($arrVersions[Input::get('from')]))
				{
					$intFrom = Input::get('from');
					$from = deserialize($arrVersions[Input::get('from')]['data']);
				}
				elseif ($intIndex > 1)
				{
					$intFrom = $intIndex-1;
					$from = deserialize($arrVersions[$intFrom]['data']);
				}

				$this->loadLanguageFile(Input::get('table'));
				$this->loadDataContainer(Input::get('table'));

				$arrFields = $GLOBALS['TL_DCA'][Input::get('table')]['fields'];

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
							$to[$k] = $this->implode($tmp);
						}
						if (is_array(($tmp = deserialize($from[$k]))) && !is_array($from[$k]))
						{
							$from[$k] = $this->implode($tmp);
						}
						unset($tmp);

						// Convert date fields
						if ($arrFields[$k]['eval']['rgxp'] == 'date')
						{
							$to[$k] = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $to[$k] ?: '');
							$from[$k] = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $from[$k] ?: '');
						}
						elseif ($arrFields[$k]['eval']['rgxp'] == 'time')
						{
							$to[$k] = $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $to[$k] ?: '');
							$from[$k] = $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $from[$k] ?: '');
						}
						elseif ($arrFields[$k]['eval']['rgxp'] == 'datim')
						{
							$to[$k] = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $to[$k] ?: '');
							$from[$k] = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $from[$k] ?: '');
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

						$objDiff = new Diff($from[$k], $to[$k]);
						$strBuffer .= $objDiff->Render(new Diff_Renderer_Html_Contao(array('field'=>($arrFields[$k]['label'][0] ?: $k))));
					}
				}
			}
		}

		// Identical versions
		if ($strBuffer == '')
		{
			$strBuffer = '<p>'.$GLOBALS['TL_LANG']['MSC']['identicalVersions'].'</p>';
		}

		$this->Template = new BackendTemplate('be_diff');

		// Template variables
		$this->Template->content = $strBuffer;
		$this->Template->versions = $arrVersions;
		$this->Template->to = $intTo;
		$this->Template->from = $intFrom;
		$this->Template->fromLabel = 'Von';
		$this->Template->toLabel = 'Zu';
		$this->Template->showLabel = specialchars($GLOBALS['TL_LANG']['MSC']['showDifferences']);
		$this->Template->table = Input::get('table');
		$this->Template->pid = intval(Input::get('pid'));
		$this->Template->theme = $this->getTheme();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['showDifferences']);
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->action = ampersand(Environment::get('request'));

		$GLOBALS['TL_CONFIG']['debugMode'] = false;
		$this->Template->output();
	}


	/**
	 * Implode a multi-dimensional array recursively
	 * @param mixed
	 * @return string
	 */
	protected function implode($var)
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
				$buffer .= $k . ": " . $this->implode($v) . "\n";
			}

			return trim($buffer);
		}
	}
}


/**
 * Instantiate the controller
 */
$objDiff = new DiffController();
$objDiff->run();
