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
 * @package    Listing
 * @license    LGPL
 * @filesource
 */


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['listing'] = '{title_legend},name,headline,type;{config_legend},list_table,list_fields,list_where,list_search,list_sort,perPage,list_info,list_info_where;{template_legend:hide},list_layout,list_info_layout;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['list_table'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_table'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_listing', 'getAllTables'),
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_fields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_fields'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_where'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_where'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('preserveTags'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_search'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_search'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_sort'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_sort'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_info'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_info'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_info_where'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_info_where'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('preserveTags'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_layout'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_layout'],
	'default'                 => 'list_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_listing', 'getListTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_info_layout'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_info_layout'],
	'default'                 => 'info_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_listing', 'getInfoTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);


/**
 * Class tl_module_listing
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_module_listing extends Backend
{

	/**
	 * Get all tables and return them as array
	 * @return array
	 */
	public function getAllTables()
	{
		return $this->Database->listTables();
	}


	/**
	 * Return all list templates as array
	 * @param object
	 * @return array
	 */
	public function getListTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('list_', $intPid);
	}


	/**
	 * Return all info templates as array
	 * @param object
	 * @return array
	 */
	public function getInfoTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('info_', $intPid);
	}
}

?>