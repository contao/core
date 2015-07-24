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
 * Reads and writes calendars
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
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneByTitle($val, $opt=array())
 * @method static $this findOneByJumpTo($val, $opt=array())
 * @method static $this findOneByProtected($val, $opt=array())
 * @method static $this findOneByGroups($val, $opt=array())
 * @method static $this findOneByAllowComments($val, $opt=array())
 * @method static $this findOneByNotify($val, $opt=array())
 * @method static $this findOneBySortOrder($val, $opt=array())
 * @method static $this findOneByPerPage($val, $opt=array())
 * @method static $this findOneByModerate($val, $opt=array())
 * @method static $this findOneByBbcode($val, $opt=array())
 * @method static $this findOneByRequireLogin($val, $opt=array())
 * @method static $this findOneByDisableCaptcha($val, $opt=array())
 *
 * @method static \Model\Collection|\CalendarModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findByTitle($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findByProtected($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findByGroups($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findByAllowComments($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findByNotify($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findBySortOrder($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findByPerPage($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findByModerate($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findByBbcode($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findByRequireLogin($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findByDisableCaptcha($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\CalendarModel findAll($opt=array())
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
class CalendarModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_calendar';

}
