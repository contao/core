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
 * Back end module "edit account".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 *
	 * @return string
	 */
	public function generate()
	{
		$this->import('BackendUser', 'User');

		$GLOBALS['TL_DCA'][$this->table]['config']['closed'] = true;
		$GLOBALS['TL_DCA'][$this->table]['config']['hideVersionMenu'] = true;

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
	 *
	 * @return string|void
	 */
	protected function compile()
	{
		return '';
	}
}
