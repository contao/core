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
 * @method static \MemberModel findById($id, $opt=array())
 * @method static \MemberModel findByPk($id, $opt=array())
 * @method static \MemberModel findByIdOrAlias($val, $opt=array())
 * @method static \MemberModel findOneBy($col, $val, $opt=array())
 * @method static \MemberModel findByAutologin($val, $opt=array())
 * @method static \MemberModel findByUsername($val, $opt=array())
 * @method static \MemberModel findOneByTstamp($val, $opt=array())
 * @method static \MemberModel findOneByFirstname($val, $opt=array())
 * @method static \MemberModel findOneByLastname($val, $opt=array())
 * @method static \MemberModel findOneByDateOfBirth($val, $opt=array())
 * @method static \MemberModel findOneByGender($val, $opt=array())
 * @method static \MemberModel findOneByCompany($val, $opt=array())
 * @method static \MemberModel findOneByStreet($val, $opt=array())
 * @method static \MemberModel findOneByPostal($val, $opt=array())
 * @method static \MemberModel findOneByCity($val, $opt=array())
 * @method static \MemberModel findOneByState($val, $opt=array())
 * @method static \MemberModel findOneByCountry($val, $opt=array())
 * @method static \MemberModel findOneByPhone($val, $opt=array())
 * @method static \MemberModel findOneByMobile($val, $opt=array())
 * @method static \MemberModel findOneByFax($val, $opt=array())
 * @method static \MemberModel findOneByEmail($val, $opt=array())
 * @method static \MemberModel findOneByWebsite($val, $opt=array())
 * @method static \MemberModel findOneByLanguage($val, $opt=array())
 * @method static \MemberModel findOneByGroups($val, $opt=array())
 * @method static \MemberModel findOneByLogin($val, $opt=array())
 * @method static \MemberModel findOneByPassword($val, $opt=array())
 * @method static \MemberModel findOneByAssignDir($val, $opt=array())
 * @method static \MemberModel findOneByHomeDir($val, $opt=array())
 * @method static \MemberModel findOneByDisable($val, $opt=array())
 * @method static \MemberModel findOneByStart($val, $opt=array())
 * @method static \MemberModel findOneByStop($val, $opt=array())
 * @method static \MemberModel findOneByDateAdded($val, $opt=array())
 * @method static \MemberModel findOneByLastLogin($val, $opt=array())
 * @method static \MemberModel findOneByCurrentLogin($val, $opt=array())
 * @method static \MemberModel findOneByLoginCount($val, $opt=array())
 * @method static \MemberModel findOneByLocked($val, $opt=array())
 * @method static \MemberModel findOneBySession($val, $opt=array())
 * @method static \MemberModel findOneByCreatedOn($val, $opt=array())
 * @method static \MemberModel findOneByActivation($val, $opt=array())
 * @method static \MemberModel findOneByNewsletter($val, $opt=array())
 *
 * @method static \Model\Collection|\MemberModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByFirstname($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByLastname($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByDateOfBirth($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByGender($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByCompany($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByStreet($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByPostal($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByCity($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByState($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByCountry($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByPhone($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByMobile($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByFax($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByEmail($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByWebsite($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByLanguage($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByGroups($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByLogin($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByPassword($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByAssignDir($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByHomeDir($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByDisable($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByStart($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByStop($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByDateAdded($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByLastLogin($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByCurrentLogin($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByLoginCount($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByLocked($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findBySession($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByCreatedOn($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByActivation($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findByNewsletter($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\MemberModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\MemberModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByFirstname($val, $opt=array())
 * @method static integer countByLastname($val, $opt=array())
 * @method static integer countByDateOfBirth($val, $opt=array())
 * @method static integer countByGender($val, $opt=array())
 * @method static integer countByCompany($val, $opt=array())
 * @method static integer countByStreet($val, $opt=array())
 * @method static integer countByPostal($val, $opt=array())
 * @method static integer countByCity($val, $opt=array())
 * @method static integer countByState($val, $opt=array())
 * @method static integer countByCountry($val, $opt=array())
 * @method static integer countByPhone($val, $opt=array())
 * @method static integer countByMobile($val, $opt=array())
 * @method static integer countByFax($val, $opt=array())
 * @method static integer countByEmail($val, $opt=array())
 * @method static integer countByWebsite($val, $opt=array())
 * @method static integer countByLanguage($val, $opt=array())
 * @method static integer countByGroups($val, $opt=array())
 * @method static integer countByLogin($val, $opt=array())
 * @method static integer countByUsername($val, $opt=array())
 * @method static integer countByPassword($val, $opt=array())
 * @method static integer countByAssignDir($val, $opt=array())
 * @method static integer countByHomeDir($val, $opt=array())
 * @method static integer countByDisable($val, $opt=array())
 * @method static integer countByStart($val, $opt=array())
 * @method static integer countByStop($val, $opt=array())
 * @method static integer countByDateAdded($val, $opt=array())
 * @method static integer countByLastLogin($val, $opt=array())
 * @method static integer countByCurrentLogin($val, $opt=array())
 * @method static integer countByLoginCount($val, $opt=array())
 * @method static integer countByLocked($val, $opt=array())
 * @method static integer countBySession($val, $opt=array())
 * @method static integer countByAutologin($val, $opt=array())
 * @method static integer countByCreatedOn($val, $opt=array())
 * @method static integer countByActivation($val, $opt=array())
 * @method static integer countByNewsletter($val, $opt=array())
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
