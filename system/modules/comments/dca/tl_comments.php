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
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Comments
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_comments 
 */
$GLOBALS['TL_DCA']['tl_comments'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_content',
		'doNotCopyRecords'            => true,
		'enableVersioning'            => true,
		'closed'                      => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('date DESC'),
			'flag'                    => 8,
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s',
			'label_callback'          => array('tl_comments', 'listComments')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_comments']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_comments']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_comments']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'name,email,website;comment;published'
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64)
		),
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'rgxp'=>'email')
		),
		'website' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['website'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128, 'rgxp'=>'url', 'decodeEntities'=>true)
		),
		'comment' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['comment'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE')
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true)
		),
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_comments']['date'],
			'sorting'                 => true,
			'filter'                  => true,
			'flag'                    => 8
		),
		'pid' => array
		(
			'label'                   => array('PID', 'Parent ID'),
			'filter'                  => true,
			'sorting'                 => true
		)
	)
);


/**
 * Class tl_comments
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_comments extends Backend
{

	/**
	 * Database result
	 * @var array
	 */
	protected $arrData = null;


	/**
	 * List a particular record
	 * @param array
	 * @return string
	 */
	public function listComments($arrRow)
	{
		if (is_null($this->arrData))
		{
			$objData = $this->Database->execute("SELECT c.id, a.title FROM tl_content c, tl_article a WHERE c.pid=a.id AND c.type='comments'");

			while ($objData->next())
			{
				$this->arrData[$objData->id] = $objData->title;
			}
		}

		$key = $arrRow['published'] ? 'published' : 'unpublished';

		return '
<div class="comment_wrap">
<div class="cte_type ' . $key . '"><strong><a href="mailto:' . $arrRow['email'] . '" title="' . specialchars($arrRow['email']) . '">' . $arrRow['name'] . '</a></strong>' . (strlen($arrRow['website']) ? ' (<a href="' . $arrRow['website'] . '" title="' . specialchars($arrRow['website']) . '" onclick="window.open(this.href); return false;">' . $GLOBALS['TL_LANG']['MSC']['com_website'] . '</a>)' : '') . ' - ' . date($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['date']) . ' - IP ' . $arrRow['ip'] . '<br />' . $this->arrData[$arrRow['pid']] . ' (PID: ' . $arrRow['pid'] . ')</div>
<div class="limit_height mark_links' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h52' : '') . ' block">
' . $arrRow['comment'] . '
</div>
</div>' . "\n    ";
	}
}

?>