<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Devtools
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleLabels
 *
 * Back end module "missing labels".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Devtools
 */
class ModuleLabels extends \BackendModule
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'dev_labels';


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->loadLanguageFile('tl_labels');

		$this->Template->label = $GLOBALS['TL_LANG']['tl_labels']['label'][0];
		$this->Template->headline = sprintf($GLOBALS['TL_LANG']['tl_labels']['headline'], \Input::get('id'));
		$this->Template->help = ($GLOBALS['TL_CONFIG']['showHelp'] && strlen($GLOBALS['TL_LANG']['tl_labels']['label'][1])) ? $GLOBALS['TL_LANG']['tl_labels']['label'][1] : '';
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['tl_labels']['submitBT']);

		$strOptions = '';
		$arrLanguages = $this->getLanguages();

		// Get languages
		foreach (scan(TL_ROOT . '/system/modules/core/languages') as $strLanguage)
		{
			if ($strLanguage != 'en' && substr($strLanguage, 0, 1) != '.')
			{
				$strOptions .= sprintf('<option value="%s"%s>%s</option>', $strLanguage, (($strLanguage == \Input::post('language')) ? ' selected="selected"' : ''), $arrLanguages[$strLanguage]);
			}
		}

		$this->Template->options = $strOptions;
		$this->Template->base = \Environment::get('base');
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
		$this->Template->selectAll = $GLOBALS['TL_LANG']['MSC']['selectAll'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
		$this->Template->href = (\Input::post('FORM_SUBMIT') == 'tl_labels') ? \Environment::get('request') : $this->getReferer(true);
		$this->Template->action = ampersand(\Environment::get('request'));
		$this->Template->warning = $GLOBALS['TL_LANG']['tl_labels']['warning'];
		$this->Template->error = $GLOBALS['TL_LANG']['tl_labels']['error'];
		$this->Template->ok = $GLOBALS['TL_LANG']['tl_labels']['ok'];

		// Find missing labels
		if (\Input::post('FORM_SUBMIT') == 'tl_labels')
		{
			$arrLang = array();

			foreach (scandir(TL_ROOT . '/system/modules') as $strDir)
			{
				$strPath = TL_ROOT . '/system/modules/' . $strDir . '/languages/en';
				$strLang = TL_ROOT . '/system/modules/' . $strDir . '/languages/' . \Input::post('language');

				// Continue if language folder does not exists
				if (in_array($strDir, array('.', '..')) || !is_dir($strPath))
				{
					continue;
				}

				// Scan folder
				foreach (scandir($strPath) as $strFile)
				{
					// Continue if the file is not a language file
					if (in_array($strFile, array('.', '..', '.htaccess')) || substr($strFile, -4) != '.php')
					{
						continue;
					}

					// Log missing files
					if (!file_exists($strLang . '/' . $strFile))
					{
						$arrLang[$strDir][$strFile] = null;
						continue;
					}

					$arrLang[$strDir][$strFile] = array();

					// Include English file
					$arrBuffer = $GLOBALS['TL_LANG'];
					$GLOBALS['TL_LANG'] = array();
					include $strPath . '/' . $strFile;
					$arrOld = $GLOBALS['TL_LANG'];
					$GLOBALS['TL_LANG'] = $arrBuffer;

					// Include foreign file
					$arrBuffer = $GLOBALS['TL_LANG'];
					$GLOBALS['TL_LANG'] = array();
					include $strLang . '/' . $strFile;
					$arrNew = $GLOBALS['TL_LANG'];
					$GLOBALS['TL_LANG'] = $arrBuffer;

					// Check labels
					foreach ($arrOld as $k=>$v)
					{
						foreach ($v as $kk=>$vv)
						{
							if (!is_array($arrNew[$k]) || !array_key_exists($kk, $arrNew[$k]))
							{
								$arrLang[$strDir][$strFile]["\$GLOBALS['TL_LANG']['$k']['$kk']"] = $vv;
							}
						}
					}
				}
			}

			$this->Template->files = $arrLang;
			$this->Template->headline .= ' - ' . $arrLanguages[\Input::post('language')];
		}
	}
}
