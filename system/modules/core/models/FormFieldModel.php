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
 * @method static \FormFieldModel|null findById($id, $opt=array())
 * @method static \FormFieldModel|null findByPk($id, $opt=array())
 * @method static \FormFieldModel|null findByIdOrAlias($val, $opt=array())
 * @method static \FormFieldModel|null findOneBy($col, $val, $opt=array())
 * @method static \FormFieldModel|null findOneByPid($val, $opt=array())
 * @method static \FormFieldModel|null findOneBySorting($val, $opt=array())
 * @method static \FormFieldModel|null findOneByTstamp($val, $opt=array())
 * @method static \FormFieldModel|null findOneByInvisible($val, $opt=array())
 * @method static \FormFieldModel|null findOneByType($val, $opt=array())
 * @method static \FormFieldModel|null findOneByName($val, $opt=array())
 * @method static \FormFieldModel|null findOneByLabel($val, $opt=array())
 * @method static \FormFieldModel|null findOneByText($val, $opt=array())
 * @method static \FormFieldModel|null findOneByHtml($val, $opt=array())
 * @method static \FormFieldModel|null findOneByOptions($val, $opt=array())
 * @method static \FormFieldModel|null findOneByMandatory($val, $opt=array())
 * @method static \FormFieldModel|null findOneByRgxp($val, $opt=array())
 * @method static \FormFieldModel|null findOneByPlaceholder($val, $opt=array())
 * @method static \FormFieldModel|null findOneByMinlength($val, $opt=array())
 * @method static \FormFieldModel|null findOneByMaxlength($val, $opt=array())
 * @method static \FormFieldModel|null findOneBySize($val, $opt=array())
 * @method static \FormFieldModel|null findOneByMultiple($val, $opt=array())
 * @method static \FormFieldModel|null findOneByMSize($val, $opt=array())
 * @method static \FormFieldModel|null findOneByExtensions($val, $opt=array())
 * @method static \FormFieldModel|null findOneByStoreFile($val, $opt=array())
 * @method static \FormFieldModel|null findOneByUploadFolder($val, $opt=array())
 * @method static \FormFieldModel|null findOneByUseHomeDir($val, $opt=array())
 * @method static \FormFieldModel|null findOneByDoNotOverwrite($val, $opt=array())
 * @method static \FormFieldModel|null findOneByFsType($val, $opt=array())
 * @method static \FormFieldModel|null findOneByClass($val, $opt=array())
 * @method static \FormFieldModel|null findOneByValue($val, $opt=array())
 * @method static \FormFieldModel|null findOneByAccesskey($val, $opt=array())
 * @method static \FormFieldModel|null findOneByTabindex($val, $opt=array())
 * @method static \FormFieldModel|null findOneByFSize($val, $opt=array())
 * @method static \FormFieldModel|null findOneByCustomTpl($val, $opt=array())
 * @method static \FormFieldModel|null findOneByAddSubmit($val, $opt=array())
 * @method static \FormFieldModel|null findOneBySlabel($val, $opt=array())
 * @method static \FormFieldModel|null findOneByImageSubmit($val, $opt=array())
 * @method static \FormFieldModel|null findOneBySingleSRC($val, $opt=array())
 *
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findBySorting($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByInvisible($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByType($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByName($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByLabel($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByText($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByHtml($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByOptions($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByMandatory($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByRgxp($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByPlaceholder($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByMinlength($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByMaxlength($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findBySize($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByMultiple($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByMSize($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByExtensions($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByStoreFile($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByUploadFolder($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByUseHomeDir($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByDoNotOverwrite($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByFsType($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByClass($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByValue($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByAccesskey($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByTabindex($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByFSize($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByCustomTpl($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByAddSubmit($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findBySlabel($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findByImageSubmit($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findBySingleSRC($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\FormFieldModel[]|\FormFieldModel|null findAll($opt=array())
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
	 * @return \Model\Collection|\FormFieldModel[]|\FormFieldModel|null A collection of models or null if there are no form fields
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
