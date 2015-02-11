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
 * @method static $this findByAutologin()
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
 * @method static $this findOneByUsername()
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
 * @method static \MemberModel[]|\Model\Collection findByTstamp()
 * @method static \MemberModel[]|\Model\Collection findByFirstname()
 * @method static \MemberModel[]|\Model\Collection findByLastname()
 * @method static \MemberModel[]|\Model\Collection findByDateOfBirth()
 * @method static \MemberModel[]|\Model\Collection findByGender()
 * @method static \MemberModel[]|\Model\Collection findByCompany()
 * @method static \MemberModel[]|\Model\Collection findByStreet()
 * @method static \MemberModel[]|\Model\Collection findByPostal()
 * @method static \MemberModel[]|\Model\Collection findByCity()
 * @method static \MemberModel[]|\Model\Collection findByState()
 * @method static \MemberModel[]|\Model\Collection findByCountry()
 * @method static \MemberModel[]|\Model\Collection findByPhone()
 * @method static \MemberModel[]|\Model\Collection findByMobile()
 * @method static \MemberModel[]|\Model\Collection findByFax()
 * @method static \MemberModel[]|\Model\Collection findByEmail()
 * @method static \MemberModel[]|\Model\Collection findByWebsite()
 * @method static \MemberModel[]|\Model\Collection findByLanguage()
 * @method static \MemberModel[]|\Model\Collection findByGroups()
 * @method static \MemberModel[]|\Model\Collection findByLogin()
 * @method static \MemberModel[]|\Model\Collection findByUsername()
 * @method static \MemberModel[]|\Model\Collection findByPassword()
 * @method static \MemberModel[]|\Model\Collection findByAssignDir()
 * @method static \MemberModel[]|\Model\Collection findByHomeDir()
 * @method static \MemberModel[]|\Model\Collection findByDisable()
 * @method static \MemberModel[]|\Model\Collection findByStart()
 * @method static \MemberModel[]|\Model\Collection findByStop()
 * @method static \MemberModel[]|\Model\Collection findByDateAdded()
 * @method static \MemberModel[]|\Model\Collection findByLastLogin()
 * @method static \MemberModel[]|\Model\Collection findByCurrentLogin()
 * @method static \MemberModel[]|\Model\Collection findByLoginCount()
 * @method static \MemberModel[]|\Model\Collection findByLocked()
 * @method static \MemberModel[]|\Model\Collection findBySession()
 * @method static \MemberModel[]|\Model\Collection findByCreatedOn()
 * @method static \MemberModel[]|\Model\Collection findByActivation()
 * @method static \MemberModel[]|\Model\Collection findByNewsletter()
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
		$time = time();
		$t = static::$strTable;

		$arrColumns = array("$t.email=? AND $t.login=1 AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.disable=''");

		if ($strUsername !== null)
		{
			$arrColumns[] = "$t.username=?";
		}

		return static::findOneBy($arrColumns, array($strEmail, $strUsername), $arrOptions);
	}
}
