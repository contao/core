<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2008
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Memberlist
 * @license    LGPL
 * @filesource
 */


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['memberlist'] = 'name,type,headline;ml_groups;perPage,ml_fields;guests,protected;align,space,cssID';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['ml_groups'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['ml_groups'],
	'exclude'       => true,
	'inputType'     => 'checkbox',
	'foreignKey'    => 'tl_member_group.name',
	'eval'          => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['ml_fields'] = array
(
	'label'              => &$GLOBALS['TL_LANG']['tl_module']['ml_fields'],
	'exclude'            => true,
	'inputType'          => 'checkboxWizard',
	'options_callback'   => array('tl_module_memberlist', 'getViewableMemberProperties'),
	'eval'               => array('multiple'=>true)
);


/**
 * Class tl_module_memberlist
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_module_memberlist extends Backend
{

	/**
	 * Return all editable fields of table tl_member
	 * @return array
	 */
	public function getViewableMemberProperties()
	{
		$return = array();

		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');

		foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k=>$v)
		{
			if ($k == 'password' || $k == 'newsletter' || $k == 'publicFields' || $k == 'allowEmail')
			{
				continue;
			}

			if ($v['eval']['feViewable'])
			{
				$return[$k] = $GLOBALS['TL_DCA']['tl_member']['fields'][$k]['label'][0];
			}
		}

		return $return;
	}
}

?>