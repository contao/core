<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Reads and writes forms
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $title
 * @property string  $alias
 * @property integer $jumpTo
 * @property boolean $sendViaEmail
 * @property string  $recipient
 * @property string  $subject
 * @property string  $format
 * @property boolean $skipEmpty
 * @property boolean $storeValues
 * @property string  $targetTable
 * @property string  $method
 * @property boolean $novalidate
 * @property string  $attributes
 * @property string  $formID
 * @property boolean $tableless
 * @property boolean $allowTags
 *
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneByTitle($val, $opt=array())
 * @method static $this findOneByAlias($val, $opt=array())
 * @method static $this findOneByJumpTo($val, $opt=array())
 * @method static $this findOneBySendViaEmail($val, $opt=array())
 * @method static $this findOneByRecipient($val, $opt=array())
 * @method static $this findOneBySubject($val, $opt=array())
 * @method static $this findOneByFormat($val, $opt=array())
 * @method static $this findOneBySkipEmpty($val, $opt=array())
 * @method static $this findOneByStoreValues($val, $opt=array())
 * @method static $this findOneByTargetTable($val, $opt=array())
 * @method static $this findOneByMethod($val, $opt=array())
 * @method static $this findOneByNovalidate($val, $opt=array())
 * @method static $this findOneByAttributes($val, $opt=array())
 * @method static $this findOneByFormID($val, $opt=array())
 * @method static $this findOneByTableless($val, $opt=array())
 * @method static $this findOneByAllowTags($val, $opt=array())
 *
 * @method static \Model\Collection|\FormModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByTitle($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByAlias($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\FormModel findBySendViaEmail($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByRecipient($val, $opt=array())
 * @method static \Model\Collection|\FormModel findBySubject($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByFormat($val, $opt=array())
 * @method static \Model\Collection|\FormModel findBySkipEmpty($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByStoreValues($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByTargetTable($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByMethod($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByNovalidate($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByAttributes($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByFormID($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByTableless($val, $opt=array())
 * @method static \Model\Collection|\FormModel findByAllowTags($val, $opt=array())
 * @method static \Model\Collection|\FormModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\FormModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\FormModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByTitle($val, $opt=array())
 * @method static integer countByAlias($val, $opt=array())
 * @method static integer countByJumpTo($val, $opt=array())
 * @method static integer countBySendViaEmail($val, $opt=array())
 * @method static integer countByRecipient($val, $opt=array())
 * @method static integer countBySubject($val, $opt=array())
 * @method static integer countByFormat($val, $opt=array())
 * @method static integer countBySkipEmpty($val, $opt=array())
 * @method static integer countByStoreValues($val, $opt=array())
 * @method static integer countByTargetTable($val, $opt=array())
 * @method static integer countByMethod($val, $opt=array())
 * @method static integer countByNovalidate($val, $opt=array())
 * @method static integer countByAttributes($val, $opt=array())
 * @method static integer countByFormID($val, $opt=array())
 * @method static integer countByTableless($val, $opt=array())
 * @method static integer countByAllowTags($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
			return \Config::get('maxFileSize');
		}
	}
}
