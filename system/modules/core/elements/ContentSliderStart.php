<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
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
 * Class ContentSliderStart
 *
 * Front end content element "slider" (wrapper start).
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentSliderStart extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_slider_start';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		if (TL_MODE == 'BE')
		{
			$this->strTemplate = 'be_wildcard';
			$this->Template = new \BackendTemplate($this->strTemplate);
			$this->Template->title = $this->headline;
		}
		else
		{
			$this->strTemplate = 'ce_slider_start';
			$this->Template = new \FrontendTemplate($this->strTemplate);
			$this->Template->setData($this->arrData);
			$this->Template->config = $this->sliderDelay . ',' . $this->sliderSpeed . ',' . $this->sliderStartSlide . ',' . $this->sliderContinuous;
		}
	}
}
