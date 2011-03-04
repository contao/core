<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleUser
 *
 * Back end module "edit account".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleUser extends BackendModule
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_user';


	/**
	 * Change the palette of the current table and switch to edit mode
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
	 * Generate module
	 */
	protected function compile()
	{
		return '';
	}
}

?>