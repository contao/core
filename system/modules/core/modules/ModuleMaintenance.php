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
 * Back end module "maintenance".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleMaintenance extends \BackendModule
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_maintenance';


	/**
	 * Generate the module
	 *
	 * @throws \Exception
	 */
	protected function compile()
	{
		\System::loadLanguageFile('tl_maintenance');

		$this->Template->content = '';
		$this->Template->href = $this->getReferer(true);
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];

		foreach ($GLOBALS['TL_MAINTENANCE'] as $callback)
		{
			$this->import($callback);

			if (!$this->$callback instanceof \executable)
			{
				throw new \Exception("$callback is not an executable class");
			}

			$buffer = $this->$callback->run();

			if ($this->$callback->isActive())
			{
				$this->Template->content = $buffer;
				break;
			}
			else
			{
				$this->Template->content .= $buffer;
			}
		}
	}
}
