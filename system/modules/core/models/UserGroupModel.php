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
 * @method static $this findById($id, $opt=array())
 * @method static $this findByPk($id, $opt=array())
 * @method static $this findByIdOrAlias($val, $opt=array())
 * @method static $this findOneBy($col, $val, $opt=array())
 * @method static $this findOneByTstamp($val, $opt=array())
 * @method static $this findOneByName($val, $opt=array())
 * @method static $this findOneByModules($val, $opt=array())
 * @method static $this findOneByThemes($val, $opt=array())
 * @method static $this findOneByPagemounts($val, $opt=array())
 * @method static $this findOneByAlpty($val, $opt=array())
 * @method static $this findOneByFilemounts($val, $opt=array())
 * @method static $this findOneByFop($val, $opt=array())
 * @method static $this findOneByForms($val, $opt=array())
 * @method static $this findOneByFormp($val, $opt=array())
 * @method static $this findOneByAlexf($val, $opt=array())
 * @method static $this findOneByDisable($val, $opt=array())
 * @method static $this findOneByStart($val, $opt=array())
 * @method static $this findOneByStop($val, $opt=array())
 * @method static $this findOneByCalendars($val, $opt=array())
 * @method static $this findOneByCalendarp($val, $opt=array())
 * @method static $this findOneByCalendarfeeds($val, $opt=array())
 * @method static $this findOneByCalendarfeedp($val, $opt=array())
 * @method static $this findOneByFaqs($val, $opt=array())
 * @method static $this findOneByFaqp($val, $opt=array())
 * @method static $this findOneByNews($val, $opt=array())
 * @method static $this findOneByNewp($val, $opt=array())
 * @method static $this findOneByNewsfeeds($val, $opt=array())
 * @method static $this findOneByNewsfeedp($val, $opt=array())
 * @method static $this findOneByNewsletters($val, $opt=array())
 * @method static $this findOneByNewsletterp($val, $opt=array())
 *
 * @method static \Model\Collection|\UserGroupModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByName($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByModules($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByThemes($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByPagemounts($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByAlpty($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByFilemounts($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByFop($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByForms($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByFormp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByAlexf($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByDisable($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByStart($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByStop($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByCalendars($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByCalendarp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByCalendarfeeds($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByCalendarfeedp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByFaqs($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByFaqp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByNews($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByNewp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByNewsfeeds($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByNewsfeedp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByNewsletters($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findByNewsletterp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countByModules($val, $opt=array())
 * @method static integer countByThemes($val, $opt=array())
 * @method static integer countByPagemounts($val, $opt=array())
 * @method static integer countByAlpty($val, $opt=array())
 * @method static integer countByFilemounts($val, $opt=array())
 * @method static integer countByFop($val, $opt=array())
 * @method static integer countByForms($val, $opt=array())
 * @method static integer countByFormp($val, $opt=array())
 * @method static integer countByAlexf($val, $opt=array())
 * @method static integer countByDisable($val, $opt=array())
 * @method static integer countByStart($val, $opt=array())
 * @method static integer countByStop($val, $opt=array())
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
class UserGroupModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_user_group';

}
