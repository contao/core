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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['personalData'] = str_replace(',editable', ',editable,newsletters', $GLOBALS['TL_DCA']['tl_module']['palettes']['personalData']);
$GLOBALS['TL_DCA']['tl_module']['palettes']['subscribe']    = '{title_legend},name,headline,type;{config_legend},nl_channels,nl_hideChannels;{redirect_legend},jumpTo;{email_legend:hide},nl_subscribe;{template_legend:hide},nl_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['unsubscribe']  = '{title_legend},name,headline,type;{config_legend},nl_channels,nl_hideChannels;{redirect_legend},jumpTo;{email_legend:hide},nl_unsubscribe;{template_legend:hide},nl_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['nl_list']      = '{title_legend},name,headline,type;{config_legend},nl_channels;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['nl_reader']    = '{title_legend},name,headline,type;{config_legend},nl_channels,nl_includeCss;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['newsletters'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['newsletters'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_newsletter_channel.title',
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_channels'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nl_channels'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_newsletter', 'getChannels'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_hideChannels'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nl_hideChannels'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_subscribe'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nl_subscribe'],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('style'=>'height:120px;', 'decodeEntities'=>true, 'alwaysSave'=>true),
	'load_callback' => array
	(
		array('tl_module_newsletter', 'getSubscribeDefault')
	)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_unsubscribe'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nl_unsubscribe'],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('style'=>'height:120px;', 'decodeEntities'=>true, 'alwaysSave'=>true),
	'load_callback' => array
	(
		array('tl_module_newsletter', 'getUnsubscribeDefault')
	)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nl_template'],
	'default'                 => 'nl_simple',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_newsletter', 'getNewsletterTemplates')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_includeCss'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nl_includeCss'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);


/**
 * Class tl_module_newsletter
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_module_newsletter extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Load the default subscribe text
	 * @param string
	 * @return string
	 */
	public function getSubscribeDefault($varValue)
	{
		if (!trim($varValue))
		{
			$varValue = $GLOBALS['TL_LANG']['tl_module']['text_subscribe'][1];
		}

		return $varValue;
	}


	/**
	 * Load the default unsubscribe text
	 * @param string
	 * @return string
	 */
	public function getUnsubscribeDefault($varValue)
	{
		if (!trim($varValue))
		{
			$varValue = $GLOBALS['TL_LANG']['tl_module']['text_unsubscribe'][1];
		}

		return $varValue;
	}


	/**
	 * Get all channels and return them as array
	 * @return array
	 */
	public function getChannels()
	{
		if (!$this->User->isAdmin && !is_array($this->User->newsletters))
		{
			return array();
		}

		$arrChannels = array();
		$objChannels = $this->Database->execute("SELECT id, title FROM tl_newsletter_channel ORDER BY title");

		while ($objChannels->next())
		{
			if ($this->User->isAdmin || $this->User->hasAccess($objChannels->id, 'newsletters'))
			{
				$arrChannels[$objChannels->id] = $objChannels->title;
			}
		}

		return $arrChannels;
	}


	/**
	 * Return all newsletter templates as array
	 * @param object
	 * @return array
	 */
	public function getNewsletterTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('nl_', $intPid);
	}
}

?>