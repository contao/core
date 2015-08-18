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
 * @method static \NewsArchiveModel findById($id, $opt=array())
 * @method static \NewsArchiveModel findByPk($id, $opt=array())
 * @method static \NewsArchiveModel findByIdOrAlias($val, $opt=array())
 * @method static \NewsArchiveModel findOneBy($col, $val, $opt=array())
 * @method static \NewsArchiveModel findOneByTstamp($val, $opt=array())
 * @method static \NewsArchiveModel findOneByTitle($val, $opt=array())
 * @method static \NewsArchiveModel findOneByJumpTo($val, $opt=array())
 * @method static \NewsArchiveModel findOneByProtected($val, $opt=array())
 * @method static \NewsArchiveModel findOneByGroups($val, $opt=array())
 * @method static \NewsArchiveModel findOneByAllowComments($val, $opt=array())
 * @method static \NewsArchiveModel findOneByNotify($val, $opt=array())
 * @method static \NewsArchiveModel findOneBySortOrder($val, $opt=array())
 * @method static \NewsArchiveModel findOneByPerPage($val, $opt=array())
 * @method static \NewsArchiveModel findOneByModerate($val, $opt=array())
 * @method static \NewsArchiveModel findOneByBbcode($val, $opt=array())
 * @method static \NewsArchiveModel findOneByRequireLogin($val, $opt=array())
 * @method static \NewsArchiveModel findOneByDisableCaptcha($val, $opt=array())
 *
 * @method static \Model\Collection|\NewsArchiveModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findByTitle($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findByProtected($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findByGroups($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findByAllowComments($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findByNotify($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findBySortOrder($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findByPerPage($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findByModerate($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findByBbcode($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findByRequireLogin($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findByDisableCaptcha($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\NewsArchiveModel findAll($opt=array())
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
