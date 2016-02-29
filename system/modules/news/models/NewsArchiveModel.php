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
 * Reads and writes news archives
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $title
 * @property integer $jumpTo
 * @property boolean $protected
 * @property string  $groups
 * @property boolean $allowComments
 * @property string  $notify
 * @property string  $sortOrder
 * @property integer $perPage
 * @property boolean $moderate
 * @property boolean $bbcode
 * @property boolean $requireLogin
 * @property boolean $disableCaptcha
 *
 * @method static \NewsArchiveModel|null findById($id, $opt=array())
 * @method static \NewsArchiveModel|null findByPk($id, $opt=array())
 * @method static \NewsArchiveModel|null findByIdOrAlias($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneBy($col, $val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByTstamp($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByTitle($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByJumpTo($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByProtected($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByGroups($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByAllowComments($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByNotify($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneBySortOrder($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByPerPage($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByModerate($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByBbcode($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByRequireLogin($val, $opt=array())
 * @method static \NewsArchiveModel|null findOneByDisableCaptcha($val, $opt=array())
 *
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByTitle($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByProtected($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByGroups($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByAllowComments($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByNotify($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findBySortOrder($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByPerPage($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByModerate($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByBbcode($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByRequireLogin($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findByDisableCaptcha($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel[]|\NewsArchiveModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByTitle($val, $opt=array())
 * @method static integer countByJumpTo($val, $opt=array())
 * @method static integer countByProtected($val, $opt=array())
 * @method static integer countByGroups($val, $opt=array())
 * @method static integer countByAllowComments($val, $opt=array())
 * @method static integer countByNotify($val, $opt=array())
 * @method static integer countBySortOrder($val, $opt=array())
 * @method static integer countByPerPage($val, $opt=array())
 * @method static integer countByModerate($val, $opt=array())
 * @method static integer countByBbcode($val, $opt=array())
 * @method static integer countByRequireLogin($val, $opt=array())
 * @method static integer countByDisableCaptcha($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class NewsArchiveModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_news_archive';

}
