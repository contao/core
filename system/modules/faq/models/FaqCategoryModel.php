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
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByTitle()
 * @method static $this findOneByHeadline()
 * @method static $this findOneByJumpTo()
 * @method static $this findOneByAllowComments()
 * @method static $this findOneByNotify()
 * @method static $this findOneBySortOrder()
 * @method static $this findOneByPerPage()
 * @method static $this findOneByModerate()
 * @method static $this findOneByBbcode()
 * @method static $this findOneByRequireLogin()
 * @method static $this findOneByDisableCaptcha()
 *
 * @method static \Model\Collection|\FaqCategoryModel findByTstamp()
 * @method static \Model\Collection|\FaqCategoryModel findByTitle()
 * @method static \Model\Collection|\FaqCategoryModel findByHeadline()
 * @method static \Model\Collection|\FaqCategoryModel findByJumpTo()
 * @method static \Model\Collection|\FaqCategoryModel findByAllowComments()
 * @method static \Model\Collection|\FaqCategoryModel findByNotify()
 * @method static \Model\Collection|\FaqCategoryModel findBySortOrder()
 * @method static \Model\Collection|\FaqCategoryModel findByPerPage()
 * @method static \Model\Collection|\FaqCategoryModel findByModerate()
 * @method static \Model\Collection|\FaqCategoryModel findByBbcode()
 * @method static \Model\Collection|\FaqCategoryModel findByRequireLogin()
 * @method static \Model\Collection|\FaqCategoryModel findByDisableCaptcha()
 * @method static \Model\Collection|\FaqCategoryModel findMultipleByIds()
 * @method static \Model\Collection|\FaqCategoryModel findBy()
 * @method static \Model\Collection|\FaqCategoryModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByTstamp()
 * @method static integer countByTitle()
 * @method static integer countByHeadline()
 * @method static integer countByJumpTo()
 * @method static integer countByAllowComments()
 * @method static integer countByNotify()
 * @method static integer countBySortOrder()
 * @method static integer countByPerPage()
 * @method static integer countByModerate()
 * @method static integer countByBbcode()
 * @method static integer countByRequireLogin()
 * @method static integer countByDisableCaptcha()
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
