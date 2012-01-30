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
 * Class ModuleSyndication
 *
 * Provides methodes to handle syndication services
 * @copyright  Leo Feyer 2005-2011
 * @author     Yanick Witschi <http://www.certo-net.ch>
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleSyndication extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_syndication';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### SYNDICATION ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// don't return anything at all if there are no syndication services available
		if (!count($GLOBALS['TL_SYS']))
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		// order services
		$arrServices = array();
		$arrConfig = deserialize($this->syn_services, true);
		
		foreach ($arrConfig as $k)
		{
			if (array_key_exists($k, $GLOBALS['TL_SYS']))
			{
				$arrServices[$k] = $GLOBALS['TL_SYS'][$k];
			}			
		}

		$strServices = '';

		foreach ($arrServices as $k => $strClass)
		{
			$this->import($strClass);
			$strServices .= $this->{$strClass}->generateHtml();
		}
		
		$this->Template->services = $strServices;
	}
}

?>