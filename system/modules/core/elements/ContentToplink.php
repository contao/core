<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ContentToplink
 *
 * Front end content element "toplink".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentToplink extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_toplink';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		if ($this->linkTitle == '')
		{
			$this->linkTitle = $GLOBALS['TL_LANG']['MSC']['backToTop'];
		}

		$this->Template->label = $this->linkTitle;
		$this->Template->title = specialchars($this->linkTitle);
		$this->Template->request = ampersand(\Environment::get('request'), true);
	}
}
