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
 * @method static $this findById()
 * @method static $this findByUsername()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByName()
 * @method static $this findOneByEmail()
 * @method static $this findOneByLanguage()
 * @method static $this findOneByBackendTheme()
 * @method static $this findOneByUploader()
 * @method static $this findOneByShowHelp()
 * @method static $this findOneByThumbnails()
 * @method static $this findOneByUseRTE()
 * @method static $this findOneByUseCE()
 * @method static $this findOneByPassword()
 * @method static $this findOneByPwChange()
 * @method static $this findOneByAdmin()
 * @method static $this findOneByGroups()
 * @method static $this findOneByInherit()
 * @method static $this findOneByModules()
 * @method static $this findOneByThemes()
 * @method static $this findOneByPagemounts()
 * @method static $this findOneByAlpty()
 * @method static $this findOneByFilemounts()
 * @method static $this findOneByFop()
 * @method static $this findOneByForms()
 * @method static $this findOneByFormp()
 * @method static $this findOneByDisable()
 * @method static $this findOneByStart()
 * @method static $this findOneByStop()
 * @method static $this findOneBySession()
 * @method static $this findOneByDateAdded()
 * @method static $this findOneByLastLogin()
 * @method static $this findOneByCurrentLogin()
 * @method static $this findOneByLoginCount()
 * @method static $this findOneByLocked()
 * @method static $this findOneByCalendars()
 * @method static $this findOneByCalendarp()
 * @method static $this findOneByCalendarfeeds()
 * @method static $this findOneByCalendarfeedp()
 * @method static $this findOneByFaqs()
 * @method static $this findOneByFaqp()
 * @method static $this findOneByNews()
 * @method static $this findOneByNewp()
 * @method static $this findOneByNewsfeeds()
 * @method static $this findOneByNewsfeedp()
 * @method static $this findOneByNewsletters()
 * @method static $this findOneByNewsletterp()
 * @method static \Model\Collection findByTstamp()
 * @method static \Model\Collection findByName()
 * @method static \Model\Collection findByEmail()
 * @method static \Model\Collection findByLanguage()
 * @method static \Model\Collection findByBackendTheme()
 * @method static \Model\Collection findByUploader()
 * @method static \Model\Collection findByShowHelp()
 * @method static \Model\Collection findByThumbnails()
 * @method static \Model\Collection findByUseRTE()
 * @method static \Model\Collection findByUseCE()
 * @method static \Model\Collection findByPassword()
 * @method static \Model\Collection findByPwChange()
 * @method static \Model\Collection findByAdmin()
 * @method static \Model\Collection findByGroups()
 * @method static \Model\Collection findByInherit()
 * @method static \Model\Collection findByModules()
 * @method static \Model\Collection findByThemes()
 * @method static \Model\Collection findByPagemounts()
 * @method static \Model\Collection findByAlpty()
 * @method static \Model\Collection findByFilemounts()
 * @method static \Model\Collection findByFop()
 * @method static \Model\Collection findByForms()
 * @method static \Model\Collection findByFormp()
 * @method static \Model\Collection findByDisable()
 * @method static \Model\Collection findByStart()
 * @method static \Model\Collection findByStop()
 * @method static \Model\Collection findBySession()
 * @method static \Model\Collection findByDateAdded()
 * @method static \Model\Collection findByLastLogin()
 * @method static \Model\Collection findByCurrentLogin()
 * @method static \Model\Collection findByLoginCount()
 * @method static \Model\Collection findByLocked()
 * @method static \Model\Collection findByCalendars()
 * @method static \Model\Collection findByCalendarp()
 * @method static \Model\Collection findByCalendarfeeds()
 * @method static \Model\Collection findByCalendarfeedp()
 * @method static \Model\Collection findByFaqs()
 * @method static \Model\Collection findByFaqp()
 * @method static \Model\Collection findByNews()
 * @method static \Model\Collection findByNewp()
 * @method static \Model\Collection findByNewsfeeds()
 * @method static \Model\Collection findByNewsfeedp()
 * @method static \Model\Collection findByNewsletters()
 * @method static \Model\Collection findByNewsletterp()
 * @method static integer countById()
 * @method static integer countByTstamp()
 * @method static integer countByUsername()
 * @method static integer countByName()
 * @method static integer countByEmail()
 * @method static integer countByLanguage()
 * @method static integer countByBackendTheme()
 * @method static integer countByUploader()
 * @method static integer countByShowHelp()
 * @method static integer countByThumbnails()
 * @method static integer countByUseRTE()
 * @method static integer countByUseCE()
 * @method static integer countByPassword()
 * @method static integer countByPwChange()
 * @method static integer countByAdmin()
 * @method static integer countByGroups()
 * @method static integer countByInherit()
 * @method static integer countByModules()
 * @method static integer countByThemes()
 * @method static integer countByPagemounts()
 * @method static integer countByAlpty()
 * @method static integer countByFilemounts()
 * @method static integer countByFop()
 * @method static integer countByForms()
 * @method static integer countByFormp()
 * @method static integer countByDisable()
 * @method static integer countByStart()
 * @method static integer countByStop()
 * @method static integer countBySession()
 * @method static integer countByDateAdded()
 * @method static integer countByLastLogin()
 * @method static integer countByCurrentLogin()
 * @method static integer countByLoginCount()
 * @method static integer countByLocked()
 * @method static integer countByCalendars()
 * @method static integer countByCalendarp()
 * @method static integer countByCalendarfeeds()
 * @method static integer countByCalendarfeedp()
 * @method static integer countByFaqs()
 * @method static integer countByFaqp()
 * @method static integer countByNews()
 * @method static integer countByNewp()
 * @method static integer countByNewsfeeds()
 * @method static integer countByNewsfeedp()
 * @method static integer countByNewsletters()
 * @method static integer countByNewsletterp()
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
