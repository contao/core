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
 * Class ContentHtml
 *
 * Front end content element "HTML".
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentHtml extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_html';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		if (TL_MODE == 'FE')
		{
			$this->Template->html = $this->html;
		}
		else
		{
			$this->Template->html = '<pre>' . htmlspecialchars($this->html) . '</pre>';
		}
	}
}
