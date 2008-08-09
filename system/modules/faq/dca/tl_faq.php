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
 * @package    Faq 
 * @license    LGPL 
 * @filesource
 */


/**
 * Load tl_content language file
 */
$this->loadLanguageFile('tl_content');


/**
 * Table tl_faq 
 */
$GLOBALS['TL_DCA']['tl_faq'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_faq_category',
		'enableVersioning'            => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'filter'                  => true,
			'fields'                  => array('sorting'),
			'panelLayout'             => 'filter;search,limit',
			'headerFields'            => array('title', 'headline', 'jumpTo', 'tstamp'),
			'child_record_callback'   => array('tl_faq', 'listQuestions')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_faq']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_faq']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_faq']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_faq']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_faq']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addImage', 'addEnclosure'),
		'default'                     => 'question,alias;answer;addImage;addEnclosure;author,published'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addImage'                    => 'singleSRC,alt,imagemargin,size,caption,floating,fullsize',
		'addEnclosure'                => 'enclosure'
	),

	// Fields
	'fields' => array
	(
		'question' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_faq']['question'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_faq']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128),
			'save_callback' => array
			(
				array('tl_faq', 'generateAlias')
			)
		),
		'answer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_faq']['answer'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'rte'=>'tinyMCE', 'helpwizard'=>true),
			'explanation'             => 'insertTags'
		),
		'addImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_faq']['addImage'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true)
		),
		'size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['size'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'alt' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['alt'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'maxlength'=>255)
		),
		'caption' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['caption'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'extnd', 'maxlength'=>255)
		),
		'floating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['floating'],
			'exclude'                 => true,
			'inputType'               => 'radioTable',
			'options'                 => array('above', 'left', 'right'),
			'eval'                    => array('cols'=>3),
			'reference'               => &$GLOBALS['TL_LANG']['MSC']
		),
		'imagemargin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imagemargin'],
			'exclude'                 => true,
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true)
		),
		'fullsize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fullsize'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_faq']['author'],
			'default'                 => $this->User->id,
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name'
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_faq']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 2,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true)
		),
		'addEnclosure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_faq']['addEnclosure'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'enclosure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_faq']['enclosure'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true)
		)
	)
);


/**
 * Class tl_faq
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_faq extends Backend
{

	/**
	 * Autogenerate a news alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($varValue))
		{
			$objTitle = $this->Database->prepare("SELECT question FROM tl_faq WHERE id=?")
									   ->limit(1)
									   ->execute($dc->id);

			$autoAlias = true;
			$varValue = standardize($objTitle->question);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_faq WHERE question=?")
								   ->execute($varValue, $dc->id);

		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '.' . $dc->id;
		}

		return $varValue;
	}


	/**
	 * Add the type of input field
	 * @param array
	 * @return string
	 */
	public function listQuestions($arrRow)
	{
		$key = $arrRow['published'] ? 'published' : 'unpublished';
		$date = date($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['tstamp']);

		return '
<div class="cte_type ' . $key . '"><strong>' . $arrRow['question'] . '</strong> - ' . $date . '</div>
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h52' : '') . ' block">
'.$arrRow['answer'].'
</div>' . "\n";
	}
}

?>