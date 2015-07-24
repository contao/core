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
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByPid($val, $opt=array())
 * @method static $this findOneBySorting($val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneByInvisible($val, $opt=array())
 * @method static $this findOneByType($val, $opt=array())
 * @method static $this findOneByName($val, $opt=array())
 * @method static $this findOneByLabel($val, $opt=array())
 * @method static $this findOneByText($val, $opt=array())
 * @method static $this findOneByHtml($val, $opt=array())
 * @method static $this findOneByOptions($val, $opt=array())
 * @method static $this findOneByMandatory($val, $opt=array())
 * @method static $this findOneByRgxp($val, $opt=array())
 * @method static $this findOneByPlaceholder($val, $opt=array())
 * @method static $this findOneByMinlength($val, $opt=array())
 * @method static $this findOneByMaxlength($val, $opt=array())
 * @method static $this findOneBySize($val, $opt=array())
 * @method static $this findOneByMultiple($val, $opt=array())
 * @method static $this findOneByMSize($val, $opt=array())
 * @method static $this findOneByExtensions($val, $opt=array())
 * @method static $this findOneByStoreFile($val, $opt=array())
 * @method static $this findOneByUploadFolder($val, $opt=array())
 * @method static $this findOneByUseHomeDir($val, $opt=array())
 * @method static $this findOneByDoNotOverwrite($val, $opt=array())
 * @method static $this findOneByFsType($val, $opt=array())
 * @method static $this findOneByClass($val, $opt=array())
 * @method static $this findOneByValue($val, $opt=array())
 * @method static $this findOneByAccesskey($val, $opt=array())
 * @method static $this findOneByTabindex($val, $opt=array())
 * @method static $this findOneByFSize($val, $opt=array())
 * @method static $this findOneByCustomTpl($val, $opt=array())
 * @method static $this findOneByAddSubmit($val, $opt=array())
 * @method static $this findOneBySlabel($val, $opt=array())
 * @method static $this findOneByImageSubmit($val, $opt=array())
 * @method static $this findOneBySingleSRC($val, $opt=array())
 *
 * @method static \Model\Collection|\FormFieldModel findByPid($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findBySorting($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByInvisible($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByType($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByName($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByLabel($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByText($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByHtml($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByOptions($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByMandatory($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByRgxp($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByPlaceholder($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByMinlength($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByMaxlength($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findBySize($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByMultiple($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByMSize($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByExtensions($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByStoreFile($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByUploadFolder($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByUseHomeDir($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByDoNotOverwrite($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByFsType($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByClass($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByValue($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByAccesskey($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByTabindex($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByFSize($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByCustomTpl($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByAddSubmit($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findBySlabel($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findByImageSubmit($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findBySingleSRC($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countBySorting($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByInvisible($val, $opt=array())
 * @method static integer countByType($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countByLabel($val, $opt=array())
 * @method static integer countByText($val, $opt=array())
 * @method static integer countByHtml($val, $opt=array())
 * @method static integer countByOptions($val, $opt=array())
 * @method static integer countByMandatory($val, $opt=array())
 * @method static integer countByRgxp($val, $opt=array())
 * @method static integer countByPlaceholder($val, $opt=array())
 * @method static integer countByMinlength($val, $opt=array())
 * @method static integer countByMaxlength($val, $opt=array())
 * @method static integer countBySize($val, $opt=array())
 * @method static integer countByMultiple($val, $opt=array())
 * @method static integer countByMSize($val, $opt=array())
 * @method static integer countByExtensions($val, $opt=array())
 * @method static integer countByStoreFile($val, $opt=array())
 * @method static integer countByUploadFolder($val, $opt=array())
 * @method static integer countByUseHomeDir($val, $opt=array())
 * @method static integer countByDoNotOverwrite($val, $opt=array())
 * @method static integer countByFsType($val, $opt=array())
 * @method static integer countByClass($val, $opt=array())
 * @method static integer countByValue($val, $opt=array())
 * @method static integer countByAccesskey($val, $opt=array())
 * @method static integer countByTabindex($val, $opt=array())
 * @method static integer countByFSize($val, $opt=array())
 * @method static integer countByCustomTpl($val, $opt=array())
 * @method static integer countByAddSubmit($val, $opt=array())
 * @method static integer countBySlabel($val, $opt=array())
 * @method static integer countByImageSubmit($val, $opt=array())
 * @method static integer countBySingleSRC($val, $opt=array())
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
	 * @return \Model\Collection|\FormFieldModel|null A collection of models or null if there are no form fields
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
