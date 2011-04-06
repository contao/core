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
 * @package    RssReader
 * @license    LGPL
 * @filesource
 */


/**
 * Load tl_page labels
 */
$this->loadLanguageFile('tl_page');


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['rss_reader'] = '{title_legend},name,headline,type;{config_legend},rss_feed,rss_numberOfItems,perPage,skipFirst,rss_cache;{template_legend:hide},rss_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['rss_cache'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['rss_cache'],
	'default'                 => 3600,
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array(0, 5, 15, 30, 60, 300, 900, 1800, 3600, 10800, 21600, 43200, 86400),
	'eval'                    => array('tl_class'=>'w50'),
	'reference'               => &$GLOBALS['TL_LANG']['CACHE']
);

$GLOBALS['TL_DCA']['tl_module']['fields']['rss_feed'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['rss_feed'],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'style'=>'height:60px;')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['rss_numberOfItems'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['rss_numberOfItems'],
	'default'                 => 3,
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['rss_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['rss_template'],
	'default'                 => 'rss_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_rss_reader', 'getRssTemplates')
);



/**
 * Class tl_module_rss_reader
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_module_rss_reader extends Backend
{

	/**
	 * Return all navigation templates as array
	 * @param object
	 * @return array
	 */
	public function getRssTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('rss_', $intPid);
	}
}

?>