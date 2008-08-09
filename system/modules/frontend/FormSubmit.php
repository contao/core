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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class FormSubmit
 *
 * Form submit button.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class FormSubmit extends Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_submit';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'singleSRC':
				$this->arrConfiguration['singleSRC'] = $varValue;
				break;

			case 'imageSubmit':
				$this->arrConfiguration['imageSubmit'] = $varValue ? true : false;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		return;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		if ($this->imageSubmit && is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			return sprintf('<input type="image" src="%s" id="ctrl_%s" class="submit%s" alt="%s" value="%s"%s />',
							$this->singleSRC,
							$this->strId,
							(strlen($this->strClass) ? ' ' . $this->strClass : ''),
							specialchars($this->slabel),
							specialchars($this->slabel),
							$this->getAttributes());
		}

		return sprintf('<input type="submit" id="ctrl_%s" class="submit%s" value="%s"%s />',
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						specialchars($this->slabel),
						$this->getAttributes());
	}
}

?>