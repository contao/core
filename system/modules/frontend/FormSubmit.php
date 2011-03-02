<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class FormSubmit
 *
 * Form submit button.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
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
			case 'required':
			case 'mandatory':
				// Ignore
				break;

			case 'singleSRC':
				$this->arrConfiguration['singleSRC'] = $varValue;
				break;

			case 'imageSubmit':
				$this->arrConfiguration['imageSubmit'] = $varValue ? true : false;
				break;

			case 'name':
				$this->arrAttributes['name'] = $varValue;
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