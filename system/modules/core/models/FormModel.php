<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
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
 * @method static \FormModel|null findById($id, $opt=array())
 * @method static \FormModel|null findByPk($id, $opt=array())
 * @method static \FormModel|null findByIdOrAlias($val, $opt=array())
 * @method static \FormModel|null findOneBy($col, $val, $opt=array())
 * @method static \FormModel|null findOneByTstamp($val, $opt=array())
 * @method static \FormModel|null findOneByTitle($val, $opt=array())
 * @method static \FormModel|null findOneByAlias($val, $opt=array())
 * @method static \FormModel|null findOneByJumpTo($val, $opt=array())
 * @method static \FormModel|null findOneBySendViaEmail($val, $opt=array())
 * @method static \FormModel|null findOneByRecipient($val, $opt=array())
 * @method static \FormModel|null findOneBySubject($val, $opt=array())
 * @method static \FormModel|null findOneByFormat($val, $opt=array())
 * @method static \FormModel|null findOneBySkipEmpty($val, $opt=array())
 * @method static \FormModel|null findOneByStoreValues($val, $opt=array())
 * @method static \FormModel|null findOneByTargetTable($val, $opt=array())
 * @method static \FormModel|null findOneByMethod($val, $opt=array())
 * @method static \FormModel|null findOneByNovalidate($val, $opt=array())
 * @method static \FormModel|null findOneByAttributes($val, $opt=array())
 * @method static \FormModel|null findOneByFormID($val, $opt=array())
 * @method static \FormModel|null findOneByTableless($val, $opt=array())
 * @method static \FormModel|null findOneByAllowTags($val, $opt=array())
 *
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByTitle($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByAlias($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findBySendViaEmail($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByRecipient($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findBySubject($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByFormat($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findBySkipEmpty($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByStoreValues($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByTargetTable($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByMethod($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByNovalidate($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByAttributes($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByFormID($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByTableless($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findByAllowTags($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\FormModel[]|\FormModel|null findAll($opt=array())
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
