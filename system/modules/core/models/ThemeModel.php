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
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByName()
 * @method static $this findOneByAuthor()
 * @method static $this findOneByFolders()
 * @method static $this findOneByScreenshot()
 * @method static $this findOneByTemplates()
 * @method static $this findOneByVars()
 *
 * @method static \Model\Collection|\ThemeModel findByTstamp()
 * @method static \Model\Collection|\ThemeModel findByName()
 * @method static \Model\Collection|\ThemeModel findByAuthor()
 * @method static \Model\Collection|\ThemeModel findByFolders()
 * @method static \Model\Collection|\ThemeModel findByScreenshot()
 * @method static \Model\Collection|\ThemeModel findByTemplates()
 * @method static \Model\Collection|\ThemeModel findByVars()
 * @method static \Model\Collection|\ThemeModel findMultipleByIds()
 * @method static \Model\Collection|\ThemeModel findBy()
 * @method static \Model\Collection|\ThemeModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByTstamp()
 * @method static integer countByName()
 * @method static integer countByAuthor()
 * @method static integer countByFolders()
 * @method static integer countByScreenshot()
 * @method static integer countByTemplates()
 * @method static integer countByVars()
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
