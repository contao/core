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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentText
 *
 * Front end content element "text".
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Controller
 */
class ContentText extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_text';


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		$this->import('String');

		// Clean RTE output
		$this->Template->text = str_ireplace
		(
			array('<u>', '</u>', '</p>', '<br /><br />', ' target="_self"'),
			array('<span style="text-decoration:underline;">', '</span>', "</p>\n", "<br /><br />\n", ''),
			$this->String->encodeEmail($this->text)
		);

		$this->Template->addImage = false;

		// Add image
		if ($this->addImage && strlen($this->singleSRC) && is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			$this->addImageToTemplate($this->Template, $this->arrData);
		}
	}
}

?>