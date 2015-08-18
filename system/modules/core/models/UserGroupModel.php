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
 * @method static \UserGroupModel findById($id, $opt=array())
 * @method static \UserGroupModel findByPk($id, $opt=array())
 * @method static \UserGroupModel findByIdOrAlias($val, $opt=array())
 * @method static \UserGroupModel findOneBy($col, $val, $opt=array())
 * @method static \UserGroupModel findOneByTstamp($val, $opt=array())
 * @method static \UserGroupModel findOneByName($val, $opt=array())
 * @method static \UserGroupModel findOneByModules($val, $opt=array())
 * @method static \UserGroupModel findOneByThemes($val, $opt=array())
 * @method static \UserGroupModel findOneByPagemounts($val, $opt=array())
 * @method static \UserGroupModel findOneByAlpty($val, $opt=array())
 * @method static \UserGroupModel findOneByFilemounts($val, $opt=array())
 * @method static \UserGroupModel findOneByFop($val, $opt=array())
 * @method static \UserGroupModel findOneByForms($val, $opt=array())
 * @method static \UserGroupModel findOneByFormp($val, $opt=array())
 * @method static \UserGroupModel findOneByAlexf($val, $opt=array())
 * @method static \UserGroupModel findOneByDisable($val, $opt=array())
 * @method static \UserGroupModel findOneByStart($val, $opt=array())
 * @method static \UserGroupModel findOneByStop($val, $opt=array())
 * @method static \UserGroupModel findOneByCalendars($val, $opt=array())
 * @method static \UserGroupModel findOneByCalendarp($val, $opt=array())
 * @method static \UserGroupModel findOneByCalendarfeeds($val, $opt=array())
 * @method static \UserGroupModel findOneByCalendarfeedp($val, $opt=array())
 * @method static \UserGroupModel findOneByFaqs($val, $opt=array())
 * @method static \UserGroupModel findOneByFaqp($val, $opt=array())
 * @method static \UserGroupModel findOneByNews($val, $opt=array())
 * @method static \UserGroupModel findOneByNewp($val, $opt=array())
 * @method static \UserGroupModel findOneByNewsfeeds($val, $opt=array())
 * @method static \UserGroupModel findOneByNewsfeedp($val, $opt=array())
 * @method static \UserGroupModel findOneByNewsletters($val, $opt=array())
 * @method static \UserGroupModel findOneByNewsletterp($val, $opt=array())
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
