<?php

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
 * Initialize the system
 */
define('TL_MODE', 'FE');
require('system/initialize.php');


/**
 * Class Flash
 *
 * This class works as communication interface between the TYPOlight database 
 * and the Flash movie. It allows to dynamically load data into a Flash movie.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class Flash extends Controller
{

	/**
	 * Flash ID
	 * @var string
	 */
	protected $strId;


	/**
	 * Set the current flash movie
	 */
	public function __construct()
	{
		parent::__construct();
		$this->strId = $this->Input->post('flashID');
	}


	/**
	 * Generate the Flash content
	 */
	public function run()
	{
		if (!strlen($this->strId))
		{
			return;
		}

		$this->import('String');
		$this->import('Database');

		$objArticle = $this->Database->prepare("SELECT * FROM tl_flash WHERE flashID=?")
									 ->limit(1)
									 ->execute($this->strId);

		if ($objArticle->numRows < 1)
		{
			$this->log('Could not find a content element with flashID "'.$this->Input->post('flashID').'"', 'Flash run()', TL_ERROR);
			echo 'content=<p class="error">Could not find element "'.$this->Input->post('flashID').'"!</p>';

			return;
		}

		// Strip all tags that Flash can not handle
		$ce = $this->String->decodeEntities($objArticle->content);
		$ce = strip_tags($ce, '<a><b><br><font><img><i><li><p><span><textformat><u>');

		// Convert <br /> to <br> and remove all line-breaks as they might get transformed into additional <br> tags
		$ce = preg_replace('/<br \/>/i', '<br>', $ce);
		$ce = preg_replace('/[\n\r]+/i', '', $ce);

		$objTemplate = new FrontendTemplate('mod_flash_content');
		$objTemplate->text = 'content='.trim(urlencode($ce));

		echo $objTemplate->parse();
	}
}


/**
 * Instantiate controller
 */
$objFlash = new Flash();
$objFlash->run();

?>