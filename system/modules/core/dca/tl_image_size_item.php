<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Table tl_image_size_item
 */
$GLOBALS['TL_DCA']['tl_image_size_item'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_image_size',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_image_size_item', 'checkPermission')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'panelLayout'             => 'limit',
			'headerFields'            => array('name', 'tstamp', 'width', 'height', 'resizeMode', 'zoom'),
			'child_record_callback'   => array('tl_image_size_item', 'listImageSizeItem')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size_item']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size_item']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size_item']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size_item']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size_item']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s,\'tl_image_size_item\')"',
				'button_callback'     => array('tl_image_size_item', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size_item']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'media,width,height,resizeMode,zoom;{expert_legend},sizes,densities;{visibility_legend},invisible',
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_image_size.name',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'media' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size_item']['media'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'long', 'decodeEntities'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'sizes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size_item']['sizes'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50', 'decodeEntities'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'densities' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size_item']['densities'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'width' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size_item']['width'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'clr w50'),
			'sql'                     => "int(10) NULL"
		),
		'height' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size_item']['height'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) NULL"
		),
		'resizeMode' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size_item']['resizeMode'],
			'inputType'               => 'select',
			'options'                 => array('proportional', 'box', 'crop'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'exclude'                 => true,
			'eval'                    => array('helpwizard'=>true, 'tl_class'=>'clr w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'zoom' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size_item']['zoom'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('rgxp'=>'prcnt', 'nospace'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) NULL"
		),
		'invisible' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size_item']['invisible'],
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);


/**
 * Class tl_image_size_item
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class tl_image_size_item extends Backend
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
	 * Check permissions to edit the table
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		if (!$this->User->hasAccess('image_sizes', 'themes'))
		{
			$this->log('Not enough permissions to access the image sizes module', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	}


	/**
	 * List an image size item
	 * @param array
	 * @return string
	 */
	public function listImageSizeItem($row)
	{
		$html = '<div class="tl_content_left">';
		$html .= $row['media'];

		if ($row['width'] || $row['height']) {
			$html .= ' <span style="padding-left:3px">' . $row['width'] . 'x' . $row['height'] . '</span>';
		}

		if ($row['zoom']) {
			$html .= ' <span style="color:#b3b3b3;padding-left:3px">(' . $row['zoom'] . '%)</span>';
		}

		$html .= "</div>\n";

		return $html;
	}


	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];

		if ($row['invisible'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}


	/**
	 * Toggle the visibility of a format definition
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		$objVersions = new Versions('tl_image_size_item', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_image_size_item']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_image_size_item']['fields']['invisible']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, $this);
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_image_size_item SET tstamp=". time() .", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_image_size_item.id='.$intId.'" has been created'.$this->getParentEntries('tl_image_size_item', $intId), __METHOD__, TL_GENERAL);
	}
}
