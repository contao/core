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
 * Reads and writes users
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $username
 * @property string  $name
 * @property string  $email
 * @property string  $language
 * @property string  $backendTheme
 * @property string  $uploader
 * @property boolean $showHelp
 * @property boolean $thumbnails
 * @property boolean $useRTE
 * @property boolean $useCE
 * @property string  $password
 * @property boolean $pwChange
 * @property boolean $admin
 * @property string  $groups
 * @property string  $inherit
 * @property string  $modules
 * @property string  $themes
 * @property string  $pagemounts
 * @property string  $alpty
 * @property string  $filemounts
 * @property string  $fop
 * @property string  $forms
 * @property string  $formp
 * @property boolean $disable
 * @property string  $start
 * @property string  $stop
 * @property string  $session
 * @property integer $dateAdded
 * @property integer $lastLogin
 * @property integer $currentLogin
 * @property integer $loginCount
 * @property integer $locked
 * @property string  $calendars
 * @property string  $calendarp
 * @property string  $calendarfeeds
 * @property string  $calendarfeedp
 * @property string  $faqs
 * @property string  $faqp
 * @property string  $news
 * @property string  $newp
 * @property string  $newsfeeds
 * @property string  $newsfeedp
 * @property string  $newsletters
 * @property string  $newsletterp
 *
 * @method static \UserModel findById($id, $opt=array())
 * @method static \UserModel findByPk($id, $opt=array())
 * @method static \UserModel findByIdOrAlias($val, $opt=array())
 * @method static \UserModel findByUsername($val, $opt=array())
 * @method static \UserModel findOneBy($col, $val, $opt=array())
 * @method static \UserModel findOneByTstamp($val, $opt=array())
 * @method static \UserModel findOneByName($val, $opt=array())
 * @method static \UserModel findOneByEmail($val, $opt=array())
 * @method static \UserModel findOneByLanguage($val, $opt=array())
 * @method static \UserModel findOneByBackendTheme($val, $opt=array())
 * @method static \UserModel findOneByUploader($val, $opt=array())
 * @method static \UserModel findOneByShowHelp($val, $opt=array())
 * @method static \UserModel findOneByThumbnails($val, $opt=array())
 * @method static \UserModel findOneByUseRTE($val, $opt=array())
 * @method static \UserModel findOneByUseCE($val, $opt=array())
 * @method static \UserModel findOneByPassword($val, $opt=array())
 * @method static \UserModel findOneByPwChange($val, $opt=array())
 * @method static \UserModel findOneByAdmin($val, $opt=array())
 * @method static \UserModel findOneByGroups($val, $opt=array())
 * @method static \UserModel findOneByInherit($val, $opt=array())
 * @method static \UserModel findOneByModules($val, $opt=array())
 * @method static \UserModel findOneByThemes($val, $opt=array())
 * @method static \UserModel findOneByPagemounts($val, $opt=array())
 * @method static \UserModel findOneByAlpty($val, $opt=array())
 * @method static \UserModel findOneByFilemounts($val, $opt=array())
 * @method static \UserModel findOneByFop($val, $opt=array())
 * @method static \UserModel findOneByForms($val, $opt=array())
 * @method static \UserModel findOneByFormp($val, $opt=array())
 * @method static \UserModel findOneByDisable($val, $opt=array())
 * @method static \UserModel findOneByStart($val, $opt=array())
 * @method static \UserModel findOneByStop($val, $opt=array())
 * @method static \UserModel findOneBySession($val, $opt=array())
 * @method static \UserModel findOneByDateAdded($val, $opt=array())
 * @method static \UserModel findOneByLastLogin($val, $opt=array())
 * @method static \UserModel findOneByCurrentLogin($val, $opt=array())
 * @method static \UserModel findOneByLoginCount($val, $opt=array())
 * @method static \UserModel findOneByLocked($val, $opt=array())
 * @method static \UserModel findOneByCalendars($val, $opt=array())
 * @method static \UserModel findOneByCalendarp($val, $opt=array())
 * @method static \UserModel findOneByCalendarfeeds($val, $opt=array())
 * @method static \UserModel findOneByCalendarfeedp($val, $opt=array())
 * @method static \UserModel findOneByFaqs($val, $opt=array())
 * @method static \UserModel findOneByFaqp($val, $opt=array())
 * @method static \UserModel findOneByNews($val, $opt=array())
 * @method static \UserModel findOneByNewp($val, $opt=array())
 * @method static \UserModel findOneByNewsfeeds($val, $opt=array())
 * @method static \UserModel findOneByNewsfeedp($val, $opt=array())
 * @method static \UserModel findOneByNewsletters($val, $opt=array())
 * @method static \UserModel findOneByNewsletterp($val, $opt=array())
 *
 * @method static \Model\Collection|\UserModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByName($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByEmail($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByLanguage($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByBackendTheme($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByUploader($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByShowHelp($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByThumbnails($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByUseRTE($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByUseCE($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByPassword($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByPwChange($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByAdmin($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByGroups($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByInherit($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByModules($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByThemes($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByPagemounts($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByAlpty($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByFilemounts($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByFop($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByForms($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByFormp($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByDisable($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByStart($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByStop($val, $opt=array())
 * @method static \Model\Collection|\UserModel findBySession($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByDateAdded($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByLastLogin($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByCurrentLogin($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByLoginCount($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByLocked($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByCalendars($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByCalendarp($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByCalendarfeeds($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByCalendarfeedp($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByFaqs($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByFaqp($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByNews($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByNewp($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByNewsfeeds($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByNewsfeedp($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByNewsletters($val, $opt=array())
 * @method static \Model\Collection|\UserModel findByNewsletterp($val, $opt=array())
 * @method static \Model\Collection|\UserModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\UserModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\UserModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByUsername($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countByEmail($val, $opt=array())
 * @method static integer countByLanguage($val, $opt=array())
 * @method static integer countByBackendTheme($val, $opt=array())
 * @method static integer countByUploader($val, $opt=array())
 * @method static integer countByShowHelp($val, $opt=array())
 * @method static integer countByThumbnails($val, $opt=array())
 * @method static integer countByUseRTE($val, $opt=array())
 * @method static integer countByUseCE($val, $opt=array())
 * @method static integer countByPassword($val, $opt=array())
 * @method static integer countByPwChange($val, $opt=array())
 * @method static integer countByAdmin($val, $opt=array())
 * @method static integer countByGroups($val, $opt=array())
 * @method static integer countByInherit($val, $opt=array())
 * @method static integer countByModules($val, $opt=array())
 * @method static integer countByThemes($val, $opt=array())
 * @method static integer countByPagemounts($val, $opt=array())
 * @method static integer countByAlpty($val, $opt=array())
 * @method static integer countByFilemounts($val, $opt=array())
 * @method static integer countByFop($val, $opt=array())
 * @method static integer countByForms($val, $opt=array())
 * @method static integer countByFormp($val, $opt=array())
 * @method static integer countByDisable($val, $opt=array())
 * @method static integer countByStart($val, $opt=array())
 * @method static integer countByStop($val, $opt=array())
 * @method static integer countBySession($val, $opt=array())
 * @method static integer countByDateAdded($val, $opt=array())
 * @method static integer countByLastLogin($val, $opt=array())
 * @method static integer countByCurrentLogin($val, $opt=array())
 * @method static integer countByLoginCount($val, $opt=array())
 * @method static integer countByLocked($val, $opt=array())
 * @method static integer countByCalendars($val, $opt=array())
 * @method static integer countByCalendarp($val, $opt=array())
 * @method static integer countByCalendarfeeds($val, $opt=array())
 * @method static integer countByCalendarfeedp($val, $opt=array())
 * @method static integer countByFaqs($val, $opt=array())
 * @method static integer countByFaqp($val, $opt=array())
 * @method static integer countByNews($val, $opt=array())
 * @method static integer countByNewp($val, $opt=array())
 * @method static integer countByNewsfeeds($val, $opt=array())
 * @method static integer countByNewsfeedp($val, $opt=array())
 * @method static integer countByNewsletters($val, $opt=array())
 * @method static integer countByNewsletterp($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class UserModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_user';

}
