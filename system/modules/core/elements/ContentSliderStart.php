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
 * Front end content element "slider" (wrapper start).
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
