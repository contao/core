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
 * Class ModuleUser
 *
 * Back end module "edit account".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleUser extends \BackendModule
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_user';


	/**
	 * Change the palette of the current table and switch to edit mode
	 * @return string
	 */
	public function generate()
	{
		$this->import('BackendUser', 'User');

		$GLOBALS['TL_DCA'][$this->table]['config']['closed'] = true;
		$GLOBALS['TL_DCA'][$this->table]['config']['enableVersioning'] = false;

		$GLOBALS['TL_DCA'][$this->table]['palettes'] = array
		(
			'__selector__' => $GLOBALS['TL_DCA'][$this->table]['palettes']['__selector__'],
			'default' => $GLOBALS['TL_DCA'][$this->table]['palettes']['login']
		);

		$arrFields = trimsplit('[,;]', $GLOBALS['TL_DCA'][$this->table]['palettes']['default']);

		foreach ($arrFields as $strField)
		{
			$GLOBALS['TL_DCA'][$this->table]['fields'][$strField]['exclude'] = false;
		}

		return $this->objDc->edit($this->User->id);
	}


	/**
	 * Generate the module
	 * @return string|void
	 */
	protected function compile()
	{
		return '';
	}
}
