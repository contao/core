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
 * @method static \UserGroupModel|null findById($id, $opt=array())
 * @method static \UserGroupModel|null findByPk($id, $opt=array())
 * @method static \UserGroupModel|null findByIdOrAlias($val, $opt=array())
 * @method static \UserGroupModel|null findOneBy($col, $val, $opt=array())
 * @method static \UserGroupModel|null findOneByTstamp($val, $opt=array())
 * @method static \UserGroupModel|null findOneByName($val, $opt=array())
 * @method static \UserGroupModel|null findOneByModules($val, $opt=array())
 * @method static \UserGroupModel|null findOneByThemes($val, $opt=array())
 * @method static \UserGroupModel|null findOneByPagemounts($val, $opt=array())
 * @method static \UserGroupModel|null findOneByAlpty($val, $opt=array())
 * @method static \UserGroupModel|null findOneByFilemounts($val, $opt=array())
 * @method static \UserGroupModel|null findOneByFop($val, $opt=array())
 * @method static \UserGroupModel|null findOneByForms($val, $opt=array())
 * @method static \UserGroupModel|null findOneByFormp($val, $opt=array())
 * @method static \UserGroupModel|null findOneByAlexf($val, $opt=array())
 * @method static \UserGroupModel|null findOneByDisable($val, $opt=array())
 * @method static \UserGroupModel|null findOneByStart($val, $opt=array())
 * @method static \UserGroupModel|null findOneByStop($val, $opt=array())
 * @method static \UserGroupModel|null findOneByCalendars($val, $opt=array())
 * @method static \UserGroupModel|null findOneByCalendarp($val, $opt=array())
 * @method static \UserGroupModel|null findOneByCalendarfeeds($val, $opt=array())
 * @method static \UserGroupModel|null findOneByCalendarfeedp($val, $opt=array())
 * @method static \UserGroupModel|null findOneByFaqs($val, $opt=array())
 * @method static \UserGroupModel|null findOneByFaqp($val, $opt=array())
 * @method static \UserGroupModel|null findOneByNews($val, $opt=array())
 * @method static \UserGroupModel|null findOneByNewp($val, $opt=array())
 * @method static \UserGroupModel|null findOneByNewsfeeds($val, $opt=array())
 * @method static \UserGroupModel|null findOneByNewsfeedp($val, $opt=array())
 * @method static \UserGroupModel|null findOneByNewsletters($val, $opt=array())
 * @method static \UserGroupModel|null findOneByNewsletterp($val, $opt=array())
 *
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByName($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByModules($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByThemes($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByPagemounts($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByAlpty($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByFilemounts($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByFop($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByForms($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByFormp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByAlexf($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByDisable($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByStart($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByStop($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByCalendars($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByCalendarp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByCalendarfeeds($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByCalendarfeedp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByFaqs($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByFaqp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByNews($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByNewp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByNewsfeeds($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByNewsfeedp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByNewsletters($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findByNewsletterp($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\UserGroupModel[]|\UserGroupModel|null findAll($opt=array())
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
