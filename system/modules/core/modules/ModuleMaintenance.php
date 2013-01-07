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
 * Class ModuleMaintenance
 *
 * Back end module "maintenance".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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
	 * @throws \Exception
	 */
	protected function compile()
	{
		$this->loadLanguageFile('tl_maintenance');

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
