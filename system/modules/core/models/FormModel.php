<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class FormModel
 *
 * Provide methods to find and save forms.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class FormModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_form';


	/**
	 * Get the maximum file size that is allowed for file uploads
	 * @return integer
	 */
	public function getMaxUploadFileSize()
	{
		$objResult = \Database::getInstance()->prepare("SELECT MAX(maxlength) AS maxlength FROM tl_form_field WHERE pid=? AND type='upload' AND maxlength>0")
											 ->execute($this->id);

		if ($objResult->numRows > 0 && $objResult->maxlength > 0)
		{
			return $objResult->maxlength;
		}
		else
		{
			return $GLOBALS['TL_CONFIG']['maxFileSize'];
		}
	}
}
