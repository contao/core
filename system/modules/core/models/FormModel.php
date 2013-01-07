<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes forms
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class FormModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_form';


	/**
	 * Get the maximum file size that is allowed for file uploads
	 * 
	 * @return integer The maximum file size in bytes
	 */
	public function getMaxUploadFileSize()
	{
		$objResult = \Database::getInstance()->prepare("SELECT MAX(maxlength) AS maxlength FROM tl_form_field WHERE pid=? AND invisible='' AND type='upload' AND maxlength>0")
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
