<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']    = '{title_legend},name,headline,type;{config_legend},news_archives,numberOfItems,news_featured,perPage,skipFirst;{template_legend:hide},news_metaFields,news_template,customTpl;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader']  = '{title_legend},name,headline,type;{config_legend},news_archives;{template_legend:hide},news_metaFields,news_template,customTpl;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsarchive'] = '{title_legend},name,headline,type;{config_legend},news_archives,news_jumpToCurrent,news_readerModule,perPage,news_format;{template_legend:hide},news_metaFields,news_template,customTpl;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsmenu']    = '{title_legend},name,headline,type;{config_legend},news_archives,news_showQuantity,news_format,news_startDay,news_order;{redirect_legend},jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['news_archives'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_archives'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_news', 'getNewsArchives'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_featured'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_featured'],
	'default'                 => 'all_items',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('all_items', 'featured', 'unfeatured'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(16) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_jumpToCurrent'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_jumpToCurrent'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('hide_module', 'show_current', 'all_items'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(16) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_readerModule'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_readerModule'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_news', 'getReaderModules'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_metaFields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_metaFields'],
	'default'                 => array('date', 'author'),
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('date', 'author', 'comments'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_template'],
	'default'                 => 'news_latest',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_news', 'getNewsTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_format'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_format'],
	'default'                 => 'news_month',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('news_day', 'news_month', 'news_year'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'wizard' => array
	(
		array('tl_module_news', 'hideStartDay')
	),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_startDay'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_startDay'],
	'default'                 => 0,
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array(0, 1, 2, 3, 4, 5, 6),
	'reference'               => &$GLOBALS['TL_LANG']['DAYS'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_order'],
	'default'                 => 'descending',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('ascending', 'descending'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['news_showQuantity'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_showQuantity'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'sql'                     => "char(1) NOT NULL default ''"
);


/**
 * Add the comments template drop-down menu
 */
if (in_array('comments', ModuleLoader::getActive()))
{
	$GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader'] = str_replace('{protected_legend:hide}', '{comment_legend:hide},com_template;{protected_legend:hide}', $GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader']);
}


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_module_news extends Backend
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
	 * Get all news archives and return them as array
	 *
	 * @return array
	 */
	public function getNewsArchives()
	{
		if (!$this->User->isAdmin && !is_array($this->User->news))
		{
			return array();
		}

		$arrArchives = array();
		$objArchives = $this->Database->execute("SELECT id, title FROM tl_news_archive ORDER BY title");

		while ($objArchives->next())
		{
			if ($this->User->hasAccess($objArchives->id, 'news'))
			{
				$arrArchives[$objArchives->id] = $objArchives->title;
			}
		}

		return $arrArchives;
	}


	/**
	 * Get all news reader modules and return them as array
	 *
	 * @return array
	 */
	public function getReaderModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='newsreader' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}


	/**
	 * Hide the start day drop-down if not applicable
	 *
	 * @return string
	 */
	public function hideStartDay()
	{
		return '
  <script>
    var enableStartDay = function() {
      var e1 = $("ctrl_news_startDay").getParent("div");
      var e2 = $("ctrl_news_order").getParent("div");
      if ($("ctrl_news_format").value == "news_day") {
        e1.setStyle("display", "block");
        e2.setStyle("display", "none");
	  } else {
        e1.setStyle("display", "none");
        e2.setStyle("display", "block");
	  }
    };
    window.addEvent("domready", function() {
      if ($("ctrl_news_startDay")) {
        enableStartDay();
        $("ctrl_news_format").addEvent("change", enableStartDay);
      }
    });
  </script>';
	}


	/**
	 * Return all news templates as array
	 *
	 * @return array
	 */
	public function getNewsTemplates()
	{
		return $this->getTemplateGroup('news_');
	}
}
