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
 * Reads and writes user groups
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $name
 * @property string  $modules
 * @property string  $themes
 * @property string  $pagemounts
 * @property string  $alpty
 * @property string  $filemounts
 * @property string  $fop
 * @property string  $forms
 * @property string  $formp
 * @property string  $alexf
 * @property boolean $disable
 * @property string  $start
 * @property string  $stop
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
 * @method static $this findOneByTstamp()
 * @method static $this findOneByName()
 * @method static $this findOneByModules()
 * @method static $this findOneByThemes()
 * @method static $this findOneByPagemounts()
 * @method static $this findOneByAlpty()
 * @method static $this findOneByFilemounts()
 * @method static $this findOneByFop()
 * @method static $this findOneByForms()
 * @method static $this findOneByFormp()
 * @method static $this findOneByAlexf()
 * @method static $this findOneByDisable()
 * @method static $this findOneByStart()
 * @method static $this findOneByStop()
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
 * @method static \UserGroupModel[]|\Model\Collection findByTstamp()
 * @method static \UserGroupModel[]|\Model\Collection findByName()
 * @method static \UserGroupModel[]|\Model\Collection findByModules()
 * @method static \UserGroupModel[]|\Model\Collection findByThemes()
 * @method static \UserGroupModel[]|\Model\Collection findByPagemounts()
 * @method static \UserGroupModel[]|\Model\Collection findByAlpty()
 * @method static \UserGroupModel[]|\Model\Collection findByFilemounts()
 * @method static \UserGroupModel[]|\Model\Collection findByFop()
 * @method static \UserGroupModel[]|\Model\Collection findByForms()
 * @method static \UserGroupModel[]|\Model\Collection findByFormp()
 * @method static \UserGroupModel[]|\Model\Collection findByAlexf()
 * @method static \UserGroupModel[]|\Model\Collection findByDisable()
 * @method static \UserGroupModel[]|\Model\Collection findByStart()
 * @method static \UserGroupModel[]|\Model\Collection findByStop()
 * @method static \UserGroupModel[]|\Model\Collection findByCalendars()
 * @method static \UserGroupModel[]|\Model\Collection findByCalendarp()
 * @method static \UserGroupModel[]|\Model\Collection findByCalendarfeeds()
 * @method static \UserGroupModel[]|\Model\Collection findByCalendarfeedp()
 * @method static \UserGroupModel[]|\Model\Collection findByFaqs()
 * @method static \UserGroupModel[]|\Model\Collection findByFaqp()
 * @method static \UserGroupModel[]|\Model\Collection findByNews()
 * @method static \UserGroupModel[]|\Model\Collection findByNewp()
 * @method static \UserGroupModel[]|\Model\Collection findByNewsfeeds()
 * @method static \UserGroupModel[]|\Model\Collection findByNewsfeedp()
 * @method static \UserGroupModel[]|\Model\Collection findByNewsletters()
 * @method static \UserGroupModel[]|\Model\Collection findByNewsletterp()
 * @method static integer countById()
 * @method static integer countByTstamp()
 * @method static integer countByName()
 * @method static integer countByModules()
 * @method static integer countByThemes()
 * @method static integer countByPagemounts()
 * @method static integer countByAlpty()
 * @method static integer countByFilemounts()
 * @method static integer countByFop()
 * @method static integer countByForms()
 * @method static integer countByFormp()
 * @method static integer countByAlexf()
 * @method static integer countByDisable()
 * @method static integer countByStart()
 * @method static integer countByStop()
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
class UserGroupModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_user_group';

}
