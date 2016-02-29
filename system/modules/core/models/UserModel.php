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
 * @method static \UserModel|null findById($id, $opt=array())
 * @method static \UserModel|null findByPk($id, $opt=array())
 * @method static \UserModel|null findByIdOrAlias($val, $opt=array())
 * @method static \UserModel|null findByUsername($val, $opt=array())
 * @method static \UserModel|null findOneBy($col, $val, $opt=array())
 * @method static \UserModel|null findOneByTstamp($val, $opt=array())
 * @method static \UserModel|null findOneByName($val, $opt=array())
 * @method static \UserModel|null findOneByEmail($val, $opt=array())
 * @method static \UserModel|null findOneByLanguage($val, $opt=array())
 * @method static \UserModel|null findOneByBackendTheme($val, $opt=array())
 * @method static \UserModel|null findOneByUploader($val, $opt=array())
 * @method static \UserModel|null findOneByShowHelp($val, $opt=array())
 * @method static \UserModel|null findOneByThumbnails($val, $opt=array())
 * @method static \UserModel|null findOneByUseRTE($val, $opt=array())
 * @method static \UserModel|null findOneByUseCE($val, $opt=array())
 * @method static \UserModel|null findOneByPassword($val, $opt=array())
 * @method static \UserModel|null findOneByPwChange($val, $opt=array())
 * @method static \UserModel|null findOneByAdmin($val, $opt=array())
 * @method static \UserModel|null findOneByGroups($val, $opt=array())
 * @method static \UserModel|null findOneByInherit($val, $opt=array())
 * @method static \UserModel|null findOneByModules($val, $opt=array())
 * @method static \UserModel|null findOneByThemes($val, $opt=array())
 * @method static \UserModel|null findOneByPagemounts($val, $opt=array())
 * @method static \UserModel|null findOneByAlpty($val, $opt=array())
 * @method static \UserModel|null findOneByFilemounts($val, $opt=array())
 * @method static \UserModel|null findOneByFop($val, $opt=array())
 * @method static \UserModel|null findOneByForms($val, $opt=array())
 * @method static \UserModel|null findOneByFormp($val, $opt=array())
 * @method static \UserModel|null findOneByDisable($val, $opt=array())
 * @method static \UserModel|null findOneByStart($val, $opt=array())
 * @method static \UserModel|null findOneByStop($val, $opt=array())
 * @method static \UserModel|null findOneBySession($val, $opt=array())
 * @method static \UserModel|null findOneByDateAdded($val, $opt=array())
 * @method static \UserModel|null findOneByLastLogin($val, $opt=array())
 * @method static \UserModel|null findOneByCurrentLogin($val, $opt=array())
 * @method static \UserModel|null findOneByLoginCount($val, $opt=array())
 * @method static \UserModel|null findOneByLocked($val, $opt=array())
 * @method static \UserModel|null findOneByCalendars($val, $opt=array())
 * @method static \UserModel|null findOneByCalendarp($val, $opt=array())
 * @method static \UserModel|null findOneByCalendarfeeds($val, $opt=array())
 * @method static \UserModel|null findOneByCalendarfeedp($val, $opt=array())
 * @method static \UserModel|null findOneByFaqs($val, $opt=array())
 * @method static \UserModel|null findOneByFaqp($val, $opt=array())
 * @method static \UserModel|null findOneByNews($val, $opt=array())
 * @method static \UserModel|null findOneByNewp($val, $opt=array())
 * @method static \UserModel|null findOneByNewsfeeds($val, $opt=array())
 * @method static \UserModel|null findOneByNewsfeedp($val, $opt=array())
 * @method static \UserModel|null findOneByNewsletters($val, $opt=array())
 * @method static \UserModel|null findOneByNewsletterp($val, $opt=array())
 *
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByName($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByEmail($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByLanguage($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByBackendTheme($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByUploader($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByShowHelp($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByThumbnails($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByUseRTE($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByUseCE($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByPassword($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByPwChange($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByAdmin($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByGroups($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByInherit($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByModules($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByThemes($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByPagemounts($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByAlpty($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByFilemounts($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByFop($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByForms($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByFormp($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByDisable($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByStart($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByStop($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findBySession($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByDateAdded($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByLastLogin($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByCurrentLogin($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByLoginCount($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByLocked($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByCalendars($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByCalendarp($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByCalendarfeeds($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByCalendarfeedp($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByFaqs($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByFaqp($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByNews($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByNewp($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByNewsfeeds($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByNewsfeedp($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByNewsletters($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findByNewsletterp($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\UserModel[]|\UserModel|null findAll($opt=array())
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
