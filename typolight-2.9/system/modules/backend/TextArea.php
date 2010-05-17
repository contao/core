<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * PHP version 5
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class TextArea
 *
 * Provide methods to handle textareas.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Controller
 */
class TextArea extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Rows
	 * @var integer
	 */
	protected $intRows = 12;

	/**
	 * Columns
	 * @var integer
	 */
	protected $intCols = 80;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'rows':
				$this->intRows = $varValue;
				break;

			case 'cols':
				$this->intCols = $varValue;
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		// Register field name for rich text editor usage
		if (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['rte']) && $GLOBALS['TL_CONFIG']['useRTE'])
		{
			$GLOBALS['TL_RTE']['type'] = $this->rte;
			$GLOBALS['TL_RTE']['fields'][] = 'ctrl_' . $this->strId;
		}

		return sprintf('<textarea name="%s" id="ctrl_%s" class="tl_textarea%s" rows="%s" cols="%s"%s onfocus="Backend.getScrollOffset();">%s</textarea>%s',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->intRows,
						$this->intCols,
						$this->getAttributes(),
						specialchars($this->varValue),
						$this->wizard);
	}
}

?>