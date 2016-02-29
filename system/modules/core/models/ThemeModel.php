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
 * @method static \ThemeModel|null findById($id, $opt=array())
 * @method static \ThemeModel|null findByPk($id, $opt=array())
 * @method static \ThemeModel|null findByIdOrAlias($val, $opt=array())
 * @method static \ThemeModel|null findOneBy($col, $val, $opt=array())
 * @method static \ThemeModel|null findOneByTstamp($val, $opt=array())
 * @method static \ThemeModel|null findOneByName($val, $opt=array())
 * @method static \ThemeModel|null findOneByAuthor($val, $opt=array())
 * @method static \ThemeModel|null findOneByFolders($val, $opt=array())
 * @method static \ThemeModel|null findOneByScreenshot($val, $opt=array())
 * @method static \ThemeModel|null findOneByTemplates($val, $opt=array())
 * @method static \ThemeModel|null findOneByVars($val, $opt=array())
 *
 * @method static \Model\Collection|\ThemeModel[]|\ThemeModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel[]|\ThemeModel|null findByName($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel[]|\ThemeModel|null findByAuthor($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel[]|\ThemeModel|null findByFolders($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel[]|\ThemeModel|null findByScreenshot($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel[]|\ThemeModel|null findByTemplates($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel[]|\ThemeModel|null findByVars($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel[]|\ThemeModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\ThemeModel[]|\ThemeModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\ThemeModel[]|\ThemeModel|null findAll($opt=array())
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
