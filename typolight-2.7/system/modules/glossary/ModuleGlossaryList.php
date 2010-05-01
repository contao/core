<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Glossary
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleGlossaryList
 *
 * @copyright  Leo Feyer 2008-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleGlossaryList extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_glossary_list';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### GLOSSARY LIST ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->glossaries = deserialize($this->glossaries, true);

		// Return if there are no glossaries
		if (!is_array($this->glossaries) || count($this->glossaries) < 1)
		{
			return '';
		}

		return parent::generate();
	}
	

	/**
	 * Generate module
	 */
	protected function compile()
	{
		$objTerm = $this->Database->execute("SELECT * FROM tl_glossary_term WHERE pid IN(" . implode(',', $this->glossaries) . ")" . " ORDER BY term");

		if ($objTerm->numRows < 1)
		{
			$this->Template->terms = array();
			return;
		}

		$arrTerms = array();

		while ($objTerm->next())
		{
			$arrTemp = array();
			$key = utf8_substr($objTerm->term, 0, 1);

			$arrTemp['term'] = $objTerm->term;
			$arrTemp['definition'] = $objTerm->definition;
			$arrTemp['anchor'] = 'gl' . utf8_romanize($key);
			$arrTemp['addImage'] = false;

			// Add image
			if ($objTerm->addImage && is_file(TL_ROOT . '/' . $objTerm->singleSRC))
			{
				$size = deserialize($objTerm->size);
				$src = $this->getImage($this->urlEncode($objTerm->singleSRC), $size[0], $size[1]);

				if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
				{
					$arrTemp['imgSize'] = ' ' . $imgSize[3];
				}

				$arrTemp['src'] = $src;
				$arrTemp['href'] = $objTerm->singleSRC;
				$arrTemp['alt'] = htmlspecialchars($objTerm->alt);
				$arrTemp['fullsize'] = $objTerm->fullsize ? true : false;
				$arrTemp['margin'] = $this->generateMargin(deserialize($objTerm->imagemargin), 'padding');
				$arrTemp['float'] = in_array($objTerm->floating, array('left', 'right')) ? sprintf(' float:%s;', $objTerm->floating) : '';
				$arrTemp['caption'] = $objTerm->caption;
				$arrTemp['addImage'] = true;
			}

			$arrEnclosures = array();

			// Add enclosure
			if ($objTerm->addEnclosure)
			{
				$arrEnclosure = deserialize($objTerm->enclosure, true);
				$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

				if (is_array($arrEnclosure))
				{
					// Send file to the browser
					if (strlen($this->Input->get('file', true)) && in_array($this->Input->get('file', true), $arrEnclosure))
					{
						$this->sendFileToBrowser($this->Input->get('file', true));
					}

					// Add download link
					for ($i=0; $i<count($arrEnclosure); $i++)
					{
						if (is_file(TL_ROOT . '/' . $arrEnclosure[$i]))
						{
							$objFile = new File($arrEnclosure[$i]);

							if (in_array($objFile->extension, $allowedDownload))
							{
								$size = ' ('.number_format(($objFile->filesize/1024), 1, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']).' kB)';
								$src = 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon;

								if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
								{
									$arrEnclosures[$i]['size'] = ' ' . $imgSize[3];
								}

								$arrEnclosures[$i]['icon'] = $src;
								$arrEnclosures[$i]['link'] = basename($arrEnclosure[$i]) . $size;
								$arrEnclosures[$i]['title'] = ucfirst(str_replace('_', ' ', $objFile->filename));
								$arrEnclosures[$i]['href'] = $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($arrEnclosure[$i]);
								$arrEnclosures[$i]['enclosure'] = $arrEnclosure[$i];
							}
						}
					}
				}
			}

			$arrTemp['enclosures'] = $arrEnclosures;
			$arrTerms[$key][] = $arrTemp;
		}

		$this->Template->terms = $arrTerms;
		$this->Template->request = ampersand($this->Environment->request, true);
		$this->Template->topLink = $GLOBALS['TL_LANG']['MSC']['backToTop'];
	}
}

?>