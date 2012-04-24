<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Development
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleLabels
 *
 * Back end module "missing labels".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
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
	 * @return void
	 */
	protected function compile()
	{
		$this->loadLanguageFile('tl_labels');

		$this->Template->label = $GLOBALS['TL_LANG']['tl_labels']['label'][0];
		$this->Template->headline = sprintf($GLOBALS['TL_LANG']['tl_labels']['headline'], $this->Input->get('id'));
		$this->Template->help = ($GLOBALS['TL_CONFIG']['showHelp'] && strlen($GLOBALS['TL_LANG']['tl_labels']['label'][1])) ? $GLOBALS['TL_LANG']['tl_labels']['label'][1] : '';
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['tl_labels']['submitBT']);

		$strOptions = '';
		$arrLanguages = $this->getLanguages();

		// Get languages
		foreach (scan(TL_ROOT . '/system/modules/core/languages') as $strLanguage)
		{
			if ($strLanguage != 'en' && substr($strLanguage, 0, 1) != '.')
			{
				$strOptions .= sprintf('<option value="%s"%s>%s</option>', $strLanguage, (($strLanguage == $this->Input->post('language')) ? ' selected="selected"' : ''), $arrLanguages[$strLanguage]);
			}
		}

		$this->Template->options = $strOptions;
		$this->Template->base = $this->Environment->base;
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
		$this->Template->selectAll = $GLOBALS['TL_LANG']['MSC']['selectAll'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
		$this->Template->href = ($this->Input->post('FORM_SUBMIT') == 'tl_labels') ? $this->Environment->request : $this->getReferer(true);
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->warning = $GLOBALS['TL_LANG']['tl_labels']['warning'];
		$this->Template->error = $GLOBALS['TL_LANG']['tl_labels']['error'];
		$this->Template->ok = $GLOBALS['TL_LANG']['tl_labels']['ok'];

		// Find missing labels
		if ($this->Input->post('FORM_SUBMIT') == 'tl_labels')
		{
			$arrLang = array();

			foreach (scandir(TL_ROOT . '/system/modules') as $strDir)
			{
				$strPath = TL_ROOT . '/system/modules/' . $strDir . '/languages/en';
				$strLang = TL_ROOT . '/system/modules/' . $strDir . '/languages/' . $this->Input->post('language');

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
			$this->Template->headline .= ' - ' . $arrLanguages[$this->Input->post('language')];
		}
	}
}
