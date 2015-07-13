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
 * Back end module "missing labels".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
		\System::loadLanguageFile('tl_labels');

		$this->Template->label = $GLOBALS['TL_LANG']['tl_labels']['label'][0];
		$this->Template->headline = sprintf($GLOBALS['TL_LANG']['tl_labels']['headline'], \Input::get('id'));
		$this->Template->help = (\Config::get('showHelp') && strlen($GLOBALS['TL_LANG']['tl_labels']['label'][1])) ? $GLOBALS['TL_LANG']['tl_labels']['label'][1] : '';
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['tl_labels']['submitBT']);

		$strOptions = '';
		$arrLanguages = $this->getLanguages();

		// Get languages
		foreach (scan(TL_ROOT . '/system/modules/core/languages') as $strLanguage)
		{
			if ($strLanguage != 'en' && strncmp($strLanguage, '.', 1) !== 0)
			{
				$strOptions .= sprintf('<option value="%s"%s>%s</option>', $strLanguage, (($strLanguage == \Input::post('language') || $strLanguage == $GLOBALS['TL_LANGUAGE']) ? ' selected="selected"' : ''), $arrLanguages[$strLanguage]);
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
			$lng = \Input::post('language');

			foreach (scan(TL_ROOT . '/system/modules') as $strDir)
			{
				$strPath = 'system/modules/' . $strDir . '/languages/en';
				$strLang = 'system/modules/' . $strDir . '/languages/' . $lng;

				// Continue if language folder does not exists
				if (!is_dir(TL_ROOT . '/' . $strPath))
				{
					continue;
				}

				// Scan folder
				foreach (scan(TL_ROOT . '/' . $strPath) as $strFile)
				{
					// Continue if the file is not a language file
					if (substr($strFile, -4) != '.xlf')
					{
						continue;
					}

					// Log missing files
					if (!file_exists(TL_ROOT . '/' . $strLang . '/' . $strFile))
					{
						$arrLang[$strDir][$strFile] = null;
						continue;
					}

					$arrLang[$strDir][$strFile] = array();

					// Buffer the current labels
					$arrBuffer = $GLOBALS['TL_LANG'];

					// Include English file
					$GLOBALS['TL_LANG'] = array();
					\System::convertXlfToPhp($strPath . '/' . $strFile, 'en', true);
					$arrOld = $GLOBALS['TL_LANG'];

					// Include foreign file
					$GLOBALS['TL_LANG'] = array();
					\System::convertXlfToPhp($strLang . '/' . $strFile, $lng, true);
					$arrNew = $GLOBALS['TL_LANG'];

					// Restore the former labels
					$GLOBALS['TL_LANG'] = $arrBuffer;
					unset($arrBuffer);

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
