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
 * @method static $this findById()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByTitle()
 * @method static $this findOneByAlias()
 * @method static $this findOneByJumpTo()
 * @method static $this findOneBySendViaEmail()
 * @method static $this findOneByRecipient()
 * @method static $this findOneBySubject()
 * @method static $this findOneByFormat()
 * @method static $this findOneBySkipEmpty()
 * @method static $this findOneByStoreValues()
 * @method static $this findOneByTargetTable()
 * @method static $this findOneByMethod()
 * @method static $this findOneByNovalidate()
 * @method static $this findOneByAttributes()
 * @method static $this findOneByFormID()
 * @method static $this findOneByTableless()
 * @method static $this findOneByAllowTags()
 * @method static \Model\Collection findByTstamp()
 * @method static \Model\Collection findByTitle()
 * @method static \Model\Collection findByAlias()
 * @method static \Model\Collection findByJumpTo()
 * @method static \Model\Collection findBySendViaEmail()
 * @method static \Model\Collection findByRecipient()
 * @method static \Model\Collection findBySubject()
 * @method static \Model\Collection findByFormat()
 * @method static \Model\Collection findBySkipEmpty()
 * @method static \Model\Collection findByStoreValues()
 * @method static \Model\Collection findByTargetTable()
 * @method static \Model\Collection findByMethod()
 * @method static \Model\Collection findByNovalidate()
 * @method static \Model\Collection findByAttributes()
 * @method static \Model\Collection findByFormID()
 * @method static \Model\Collection findByTableless()
 * @method static \Model\Collection findByAllowTags()
 * @method static integer countById()
 * @method static integer countByTstamp()
 * @method static integer countByTitle()
 * @method static integer countByAlias()
 * @method static integer countByJumpTo()
 * @method static integer countBySendViaEmail()
 * @method static integer countByRecipient()
 * @method static integer countBySubject()
 * @method static integer countByFormat()
 * @method static integer countBySkipEmpty()
 * @method static integer countByStoreValues()
 * @method static integer countByTargetTable()
 * @method static integer countByMethod()
 * @method static integer countByNovalidate()
 * @method static integer countByAttributes()
 * @method static integer countByFormID()
 * @method static integer countByTableless()
 * @method static integer countByAllowTags()
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
