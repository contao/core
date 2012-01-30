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
 * Class SyndicationFacebook
 *
 * Parent class for syndication services.
 * @copyright  Leo Feyer 2005-2011
 * @author     Yanick Witschi <http://www.certo-net.ch>
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class SyndicationFacebook extends SyndicationService
{

	/**
	 * Initialize the object
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Generate the HTML view for the service
	 * @return string
	 */
	public function generateHtml()
	{
		global $objPage;
		
		$objTemplate = new FrontendTemplate('syndication_facebook');
		$objTemplate->encUrl = rawurlencode($this->Environment->base . $this->Environment->request);
		$objTemplate->encTitle = rawurlencode($objPage->pageTitle);
		$objTemplate->facebookTitle = specialchars($GLOBALS['TL_LANG']['MSC']['facebookShare']);
		return $objTemplate->parse();
	}
	
	
	/**
	 * Share
	 * @return boolean
	 */
	public function share()
	{
		if ($objInput->get('p') != 'facebook')
		{
			return false;
		}
		
		$query  = '?app_id=123050457758183';
		$query .= '&link=' . rawurlencode($objInput->get('u'));
		$query .= '&message=' . rawurlencode($objInput->get('t'));
		$query .= '&display=popup';
		$query .= '&redirect_uri=http%3A%2F%2Fwww.facebook.com';
		header('Location: http://www.facebook.com/dialog/feed' . $query);
		return true;
	}
}

?>