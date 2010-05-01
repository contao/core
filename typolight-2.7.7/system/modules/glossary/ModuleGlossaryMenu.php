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
 * Class ModuleGlossaryMenu
 *
 * @copyright  Leo Feyer 2008-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleGlossaryMenu extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_glossary_menu';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### GLOSSARY MENU ###';
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
			return;
		}

		$arrAnchor = array();

		while ($objTerm->next())
		{
			$link = utf8_substr($objTerm->term, 0, 1);
			$key = 'gl' . utf8_romanize($link);

			$arrAnchor[$key] = $link;
		}

		$this->Template->request = ampersand($this->Environment->request, true);
		$this->Template->anchors = $arrAnchor;
	}
}

?>