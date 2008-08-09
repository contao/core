<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Development
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleLabels
 *
 * Back end module "missing labels".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleLabels extends BackendModule
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'dev_labels';


	/**
	 * Generate module
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
		foreach (scan(TL_ROOT . '/system/modules/backend/languages') as $strLanguage)
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
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBT']);
		$this->Template->href = ($this->Input->post('FORM_SUBMIT') == 'tl_labels') ? $this->Environment->request : $this->getReferer(ENCODE_AMPERSANDS);
		$this->Template->action = ampersand($this->Environment->request, true);
		$this->Template->warning = $GLOBALS['TL_LANG']['tl_labels']['warning'];
		$this->Template->error = $GLOBALS['TL_LANG']['tl_labels']['error'];
		$this->Template->ok = $GLOBALS['TL_LANG']['tl_labels']['ok'];

		// Find missing labels
		if ($this->Input->post('FORM_SUBMIT') == 'tl_labels')
		{
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
					include($strPath . '/' . $strFile);
					$arrOld = $GLOBALS['TL_LANG'];
					$GLOBALS['TL_LANG'] = $arrBuffer;

					// Include foreign file
					$arrBuffer = $GLOBALS['TL_LANG'];
					$GLOBALS['TL_LANG'] = array();
					include($strLang . '/' . $strFile);
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

?>