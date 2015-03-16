<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Load tl_content language file
 */
System::loadLanguageFile('tl_content');


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['comments'] = '{title_legend},name,headline,type;{comment_legend},com_order,perPage,com_moderate,com_bbcode,com_protected,com_requireLogin,com_disableCaptcha;{template_legend:hide},com_template,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['com_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_order'],
	'default'                 => 'ascending',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('ascending', 'descending'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['com_moderate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_moderate'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['com_bbcode'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_bbcode'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['com_requireLogin'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_requireLogin'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['com_disableCaptcha'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_disableCaptcha'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['com_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_template'],
	'default'                 => 'com_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_comments', 'getCommentTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_module_comments extends Backend
{

	/**
	 * Return all navigation templates as array
	 *
	 * @return array
	 */
	public function getCommentTemplates()
	{
		return $this->getTemplateGroup('com_');
	}
}
