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
 * @package    Backend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class PageTree
 *
 * Provide methods to handle input field "page tree".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class PageTree extends \Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Load the database object
	 * @param array
	 */
	public function __construct($arrAttributes=null)
	{
		$this->import('Database');
		parent::__construct($arrAttributes);
	}


	/**
	 * Return an array if the "multiple" attribute is set
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		if (strpos($varInput, ',') === false)
		{
			return $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['multiple'] ? array(intval($varInput)) : intval($varInput);
		}
		else
		{
			return array_map('intval', array_filter(explode(',', $varInput)));
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$strValues = '';
		$arrValues = array();

		if ($this->varValue != '')
		{
			$strValues = implode(',', array_map('intval', (array)$this->varValue));
			$objPages = $this->Database->execute("SELECT id, title, alias, type, hide, protected, published, start, stop FROM tl_page WHERE id IN($strValues) ORDER BY " . $this->Database->findInSet('id', $strValues));

			while ($objPages->next())
			{
				$arrValues[] = $this->generateImage($this->getPageStatusIcon($objPages)) . ' ' . $objPages->title . ' (' . $objPages->alias . $GLOBALS['TL_CONFIG']['urlSuffix'] . ')';
			}
		}

		return '<input type="hidden" name="'.$this->strName.'" id="ctrl_'.$this->strId.'" value="'.$strValues.'">
  <div class="selector_container" id="target_'.$this->strId.'">
    <ul><li>' . implode('</li><li>', $arrValues) . '</li></ul>
    <p><a href="contao/page.php?table='.$this->strTable.'&amp;field='.$this->strField.'&amp;id='.\Input::get('id').'&amp;value='.$strValues.'" class="tl_submit" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\''.$GLOBALS['TL_LANG']['MOD']['page'][0].'\',\'url\':this.href,\'id\':\''.$this->strId.'\'});return false">'.$GLOBALS['TL_LANG']['MSC']['changeSelection'].'</a></p>
  </div>';
	}
}
