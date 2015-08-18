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
 * Reads and writes themes
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $name
 * @property string  $author
 * @property string  $folders
 * @property string  $screenshot
 * @property string  $templates
 * @property string  $vars
 *
 * @method static \ThemeModel findById($id, $opt=array())
 * @method static \ThemeModel findByPk($id, $opt=array())
 * @method static \ThemeModel findByIdOrAlias($val, $opt=array())
 * @method static \ThemeModel findOneBy($col, $val, $opt=array())
 * @method static \ThemeModel findOneByTstamp($val, $opt=array())
 * @method static \ThemeModel findOneByName($val, $opt=array())
 * @method static \ThemeModel findOneByAuthor($val, $opt=array())
 * @method static \ThemeModel findOneByFolders($val, $opt=array())
 * @method static \ThemeModel findOneByScreenshot($val, $opt=array())
 * @method static \ThemeModel findOneByTemplates($val, $opt=array())
 * @method static \ThemeModel findOneByVars($val, $opt=array())
 *
 * @method static \Model\Collection|\ThemeModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel findByName($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel findByAuthor($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel findByFolders($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel findByScreenshot($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel findByTemplates($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel findByVars($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\ThemeModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countByAuthor($val, $opt=array())
 * @method static integer countByFolders($val, $opt=array())
 * @method static integer countByScreenshot($val, $opt=array())
 * @method static integer countByTemplates($val, $opt=array())
 * @method static integer countByVars($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ThemeModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_theme';

}
