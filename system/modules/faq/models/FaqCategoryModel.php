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
 * @method static \FaqCategoryModel[]|\Model\Collection findByTstamp()
 * @method static \FaqCategoryModel[]|\Model\Collection findByTitle()
 * @method static \FaqCategoryModel[]|\Model\Collection findByHeadline()
 * @method static \FaqCategoryModel[]|\Model\Collection findByJumpTo()
 * @method static \FaqCategoryModel[]|\Model\Collection findByAllowComments()
 * @method static \FaqCategoryModel[]|\Model\Collection findByNotify()
 * @method static \FaqCategoryModel[]|\Model\Collection findBySortOrder()
 * @method static \FaqCategoryModel[]|\Model\Collection findByPerPage()
 * @method static \FaqCategoryModel[]|\Model\Collection findByModerate()
 * @method static \FaqCategoryModel[]|\Model\Collection findByBbcode()
 * @method static \FaqCategoryModel[]|\Model\Collection findByRequireLogin()
 * @method static \FaqCategoryModel[]|\Model\Collection findByDisableCaptcha()
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
