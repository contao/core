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
 * Reads and writes form fields
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $sorting
 * @property integer $tstamp
 * @property boolean $invisible
 * @property string  $type
 * @property string  $name
 * @property string  $label
 * @property string  $text
 * @property string  $html
 * @property string  $options
 * @property boolean $mandatory
 * @property string  $rgxp
 * @property string  $placeholder
 * @property integer $minlength
 * @property integer $maxlength
 * @property string  $size
 * @property boolean $multiple
 * @property integer $mSize
 * @property string  $extensions
 * @property boolean $storeFile
 * @property string  $uploadFolder
 * @property boolean $useHomeDir
 * @property boolean $doNotOverwrite
 * @property string  $fsType
 * @property string  $class
 * @property string  $value
 * @property boolean $accesskey
 * @property integer $tabindex
 * @property integer $fSize
 * @property string  $customTpl
 * @property boolean $addSubmit
 * @property string  $slabel
 * @property boolean $imageSubmit
 * @property string  $singleSRC
 *
 * @method static $this findById()
 * @method static $this findOneByPid()
 * @method static $this findOneBySorting()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByInvisible()
 * @method static $this findOneByType()
 * @method static $this findOneByName()
 * @method static $this findOneByLabel()
 * @method static $this findOneByText()
 * @method static $this findOneByHtml()
 * @method static $this findOneByOptions()
 * @method static $this findOneByMandatory()
 * @method static $this findOneByRgxp()
 * @method static $this findOneByPlaceholder()
 * @method static $this findOneByMinlength()
 * @method static $this findOneByMaxlength()
 * @method static $this findOneBySize()
 * @method static $this findOneByMultiple()
 * @method static $this findOneByMSize()
 * @method static $this findOneByExtensions()
 * @method static $this findOneByStoreFile()
 * @method static $this findOneByUploadFolder()
 * @method static $this findOneByUseHomeDir()
 * @method static $this findOneByDoNotOverwrite()
 * @method static $this findOneByFsType()
 * @method static $this findOneByClass()
 * @method static $this findOneByValue()
 * @method static $this findOneByAccesskey()
 * @method static $this findOneByTabindex()
 * @method static $this findOneByFSize()
 * @method static $this findOneByCustomTpl()
 * @method static $this findOneByAddSubmit()
 * @method static $this findOneBySlabel()
 * @method static $this findOneByImageSubmit()
 * @method static $this findOneBySingleSRC()
 * @method static \FormFieldModel[]|\Model\Collection findByPid()
 * @method static \FormFieldModel[]|\Model\Collection findBySorting()
 * @method static \FormFieldModel[]|\Model\Collection findByTstamp()
 * @method static \FormFieldModel[]|\Model\Collection findByInvisible()
 * @method static \FormFieldModel[]|\Model\Collection findByType()
 * @method static \FormFieldModel[]|\Model\Collection findByName()
 * @method static \FormFieldModel[]|\Model\Collection findByLabel()
 * @method static \FormFieldModel[]|\Model\Collection findByText()
 * @method static \FormFieldModel[]|\Model\Collection findByHtml()
 * @method static \FormFieldModel[]|\Model\Collection findByOptions()
 * @method static \FormFieldModel[]|\Model\Collection findByMandatory()
 * @method static \FormFieldModel[]|\Model\Collection findByRgxp()
 * @method static \FormFieldModel[]|\Model\Collection findByPlaceholder()
 * @method static \FormFieldModel[]|\Model\Collection findByMinlength()
 * @method static \FormFieldModel[]|\Model\Collection findByMaxlength()
 * @method static \FormFieldModel[]|\Model\Collection findBySize()
 * @method static \FormFieldModel[]|\Model\Collection findByMultiple()
 * @method static \FormFieldModel[]|\Model\Collection findByMSize()
 * @method static \FormFieldModel[]|\Model\Collection findByExtensions()
 * @method static \FormFieldModel[]|\Model\Collection findByStoreFile()
 * @method static \FormFieldModel[]|\Model\Collection findByUploadFolder()
 * @method static \FormFieldModel[]|\Model\Collection findByUseHomeDir()
 * @method static \FormFieldModel[]|\Model\Collection findByDoNotOverwrite()
 * @method static \FormFieldModel[]|\Model\Collection findByFsType()
 * @method static \FormFieldModel[]|\Model\Collection findByClass()
 * @method static \FormFieldModel[]|\Model\Collection findByValue()
 * @method static \FormFieldModel[]|\Model\Collection findByAccesskey()
 * @method static \FormFieldModel[]|\Model\Collection findByTabindex()
 * @method static \FormFieldModel[]|\Model\Collection findByFSize()
 * @method static \FormFieldModel[]|\Model\Collection findByCustomTpl()
 * @method static \FormFieldModel[]|\Model\Collection findByAddSubmit()
 * @method static \FormFieldModel[]|\Model\Collection findBySlabel()
 * @method static \FormFieldModel[]|\Model\Collection findByImageSubmit()
 * @method static \FormFieldModel[]|\Model\Collection findBySingleSRC()
 * @method static integer countById()
 * @method static integer countByPid()
 * @method static integer countBySorting()
 * @method static integer countByTstamp()
 * @method static integer countByInvisible()
 * @method static integer countByType()
 * @method static integer countByName()
 * @method static integer countByLabel()
 * @method static integer countByText()
 * @method static integer countByHtml()
 * @method static integer countByOptions()
 * @method static integer countByMandatory()
 * @method static integer countByRgxp()
 * @method static integer countByPlaceholder()
 * @method static integer countByMinlength()
 * @method static integer countByMaxlength()
 * @method static integer countBySize()
 * @method static integer countByMultiple()
 * @method static integer countByMSize()
 * @method static integer countByExtensions()
 * @method static integer countByStoreFile()
 * @method static integer countByUploadFolder()
 * @method static integer countByUseHomeDir()
 * @method static integer countByDoNotOverwrite()
 * @method static integer countByFsType()
 * @method static integer countByClass()
 * @method static integer countByValue()
 * @method static integer countByAccesskey()
 * @method static integer countByTabindex()
 * @method static integer countByFSize()
 * @method static integer countByCustomTpl()
 * @method static integer countByAddSubmit()
 * @method static integer countBySlabel()
 * @method static integer countByImageSubmit()
 * @method static integer countBySingleSRC()
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormFieldModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_form_field';


	/**
	 * Find published form fields by their parent ID
	 *
	 * @param integer $intPid     The form ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return static[]|\Model\Collection|null A collection of models or null if there are no form fields
	 */
	public static function findPublishedByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.invisible=''";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}
}
