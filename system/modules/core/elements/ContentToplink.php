<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Front end content element "toplink".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
