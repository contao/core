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
 * Reads and writes members
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $firstname
 * @property string  $lastname
 * @property string  $dateOfBirth
 * @property string  $gender
 * @property string  $company
 * @property string  $street
 * @property string  $postal
 * @property string  $city
 * @property string  $state
 * @property string  $country
 * @property string  $phone
 * @property string  $mobile
 * @property string  $fax
 * @property string  $email
 * @property string  $website
 * @property string  $language
 * @property string  $groups
 * @property boolean $login
 * @property string  $username
 * @property string  $password
 * @property boolean $assignDir
 * @property string  $homeDir
 * @property boolean $disable
 * @property string  $start
 * @property string  $stop
 * @property integer $dateAdded
 * @property integer $lastLogin
 * @property integer $currentLogin
 * @property integer $loginCount
 * @property integer $locked
 * @property string  $session
 * @property string  $autologin
 * @property integer $createdOn
 * @property string  $activation
 * @property string  $newsletter
 *
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findByAutologin()
 * @method static $this findByUsername()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByFirstname()
 * @method static $this findOneByLastname()
 * @method static $this findOneByDateOfBirth()
 * @method static $this findOneByGender()
 * @method static $this findOneByCompany()
 * @method static $this findOneByStreet()
 * @method static $this findOneByPostal()
 * @method static $this findOneByCity()
 * @method static $this findOneByState()
 * @method static $this findOneByCountry()
 * @method static $this findOneByPhone()
 * @method static $this findOneByMobile()
 * @method static $this findOneByFax()
 * @method static $this findOneByEmail()
 * @method static $this findOneByWebsite()
 * @method static $this findOneByLanguage()
 * @method static $this findOneByGroups()
 * @method static $this findOneByLogin()
 * @method static $this findOneByPassword()
 * @method static $this findOneByAssignDir()
 * @method static $this findOneByHomeDir()
 * @method static $this findOneByDisable()
 * @method static $this findOneByStart()
 * @method static $this findOneByStop()
 * @method static $this findOneByDateAdded()
 * @method static $this findOneByLastLogin()
 * @method static $this findOneByCurrentLogin()
 * @method static $this findOneByLoginCount()
 * @method static $this findOneByLocked()
 * @method static $this findOneBySession()
 * @method static $this findOneByCreatedOn()
 * @method static $this findOneByActivation()
 * @method static $this findOneByNewsletter()
 *
 * @method static \Model\Collection|\MemberModel findByTstamp()
 * @method static \Model\Collection|\MemberModel findByFirstname()
 * @method static \Model\Collection|\MemberModel findByLastname()
 * @method static \Model\Collection|\MemberModel findByDateOfBirth()
 * @method static \Model\Collection|\MemberModel findByGender()
 * @method static \Model\Collection|\MemberModel findByCompany()
 * @method static \Model\Collection|\MemberModel findByStreet()
 * @method static \Model\Collection|\MemberModel findByPostal()
 * @method static \Model\Collection|\MemberModel findByCity()
 * @method static \Model\Collection|\MemberModel findByState()
 * @method static \Model\Collection|\MemberModel findByCountry()
 * @method static \Model\Collection|\MemberModel findByPhone()
 * @method static \Model\Collection|\MemberModel findByMobile()
 * @method static \Model\Collection|\MemberModel findByFax()
 * @method static \Model\Collection|\MemberModel findByEmail()
 * @method static \Model\Collection|\MemberModel findByWebsite()
 * @method static \Model\Collection|\MemberModel findByLanguage()
 * @method static \Model\Collection|\MemberModel findByGroups()
 * @method static \Model\Collection|\MemberModel findByLogin()
 * @method static \Model\Collection|\MemberModel findByPassword()
 * @method static \Model\Collection|\MemberModel findByAssignDir()
 * @method static \Model\Collection|\MemberModel findByHomeDir()
 * @method static \Model\Collection|\MemberModel findByDisable()
 * @method static \Model\Collection|\MemberModel findByStart()
 * @method static \Model\Collection|\MemberModel findByStop()
 * @method static \Model\Collection|\MemberModel findByDateAdded()
 * @method static \Model\Collection|\MemberModel findByLastLogin()
 * @method static \Model\Collection|\MemberModel findByCurrentLogin()
 * @method static \Model\Collection|\MemberModel findByLoginCount()
 * @method static \Model\Collection|\MemberModel findByLocked()
 * @method static \Model\Collection|\MemberModel findBySession()
 * @method static \Model\Collection|\MemberModel findByCreatedOn()
 * @method static \Model\Collection|\MemberModel findByActivation()
 * @method static \Model\Collection|\MemberModel findByNewsletter()
 * @method static \Model\Collection|\MemberModel findMultipleByIds()
 * @method static \Model\Collection|\MemberModel findBy()
 * @method static \Model\Collection|\MemberModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByTstamp()
 * @method static integer countByFirstname()
 * @method static integer countByLastname()
 * @method static integer countByDateOfBirth()
 * @method static integer countByGender()
 * @method static integer countByCompany()
 * @method static integer countByStreet()
 * @method static integer countByPostal()
 * @method static integer countByCity()
 * @method static integer countByState()
 * @method static integer countByCountry()
 * @method static integer countByPhone()
 * @method static integer countByMobile()
 * @method static integer countByFax()
 * @method static integer countByEmail()
 * @method static integer countByWebsite()
 * @method static integer countByLanguage()
 * @method static integer countByGroups()
 * @method static integer countByLogin()
 * @method static integer countByUsername()
 * @method static integer countByPassword()
 * @method static integer countByAssignDir()
 * @method static integer countByHomeDir()
 * @method static integer countByDisable()
 * @method static integer countByStart()
 * @method static integer countByStop()
 * @method static integer countByDateAdded()
 * @method static integer countByLastLogin()
 * @method static integer countByCurrentLogin()
 * @method static integer countByLoginCount()
 * @method static integer countByLocked()
 * @method static integer countBySession()
 * @method static integer countByAutologin()
 * @method static integer countByCreatedOn()
 * @method static integer countByActivation()
 * @method static integer countByNewsletter()
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class MemberModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_member';


	/**
	 * Find an active member by his/her e-mail-address and username
	 *
	 * @param string $strEmail    The e-mail address
	 * @param string $strUsername The username
	 * @param array  $arrOptions  An optional options array
	 *
	 * @return static The model or null if there is no member
	 */
	public static function findActiveByEmailAndUsername($strEmail, $strUsername=null, array $arrOptions=array())
	{
		$t = static::$strTable;
		$time = \Date::floorToMinute();

		$arrColumns = array("$t.email=? AND $t.login='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.disable=''");

		if ($strUsername !== null)
		{
			$arrColumns[] = "$t.username=?";
		}

		return static::findOneBy($arrColumns, array($strEmail, $strUsername), $arrOptions);
	}
}
