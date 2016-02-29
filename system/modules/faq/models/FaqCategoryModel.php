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
 * Reads and writes FAQ categories
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $title
 * @property string  $headline
 * @property integer $jumpTo
 * @property boolean $allowComments
 * @property string  $notify
 * @property string  $sortOrder
 * @property integer $perPage
 * @property boolean $moderate
 * @property boolean $bbcode
 * @property boolean $requireLogin
 * @property boolean $disableCaptcha
 *
 * @method static \FaqCategoryModel|null findById($id, $opt=array())
 * @method static \FaqCategoryModel|null findByPk($id, $opt=array())
 * @method static \FaqCategoryModel|null findByIdOrAlias($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneBy($col, $val, $opt=array())
 * @method static \FaqCategoryModel|null findOneByTstamp($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneByTitle($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneByHeadline($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneByJumpTo($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneByAllowComments($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneByNotify($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneBySortOrder($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneByPerPage($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneByModerate($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneByBbcode($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneByRequireLogin($val, $opt=array())
 * @method static \FaqCategoryModel|null findOneByDisableCaptcha($val, $opt=array())
 *
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findByTitle($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findByHeadline($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findByAllowComments($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findByNotify($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findBySortOrder($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findByPerPage($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findByModerate($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findByBbcode($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findByRequireLogin($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findByDisableCaptcha($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\FaqCategoryModel[]|\FaqCategoryModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByTitle($val, $opt=array())
 * @method static integer countByHeadline($val, $opt=array())
 * @method static integer countByJumpTo($val, $opt=array())
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
class FaqCategoryModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_faq_category';

}
