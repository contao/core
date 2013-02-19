<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
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
 * Class ContentSliderStop
 *
 * Front end content element "slider" (wrapper stop).
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentSliderStop extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_slider_stop';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		if (TL_MODE == 'FE')
		{
			$this->strTemplate = 'ce_slider_stop';
			$this->Template = new \FrontendTemplate($this->strTemplate);
			$this->Template->setData($this->arrData);
		}
		else
		{
			$this->strTemplate = 'be_wildcard';
			$this->Template = new \BackendTemplate($this->strTemplate);
		}

		// Previous and next labels
		$this->Template->previous = $GLOBALS['TL_LANG']['MSC']['previous'];
		$this->Template->next = $GLOBALS['TL_LANG']['MSC']['next'];
	}
}
