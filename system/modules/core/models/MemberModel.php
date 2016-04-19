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
 * @method static \MemberModel|null findById($id, $opt=array())
 * @method static \MemberModel|null findByPk($id, $opt=array())
 * @method static \MemberModel|null findByIdOrAlias($val, $opt=array())
 * @method static \MemberModel|null findOneBy($col, $val, $opt=array())
 * @method static \MemberModel|null findByAutologin($val, $opt=array())
 * @method static \MemberModel|null findByUsername($val, $opt=array())
 * @method static \MemberModel|null findOneByTstamp($val, $opt=array())
 * @method static \MemberModel|null findOneByFirstname($val, $opt=array())
 * @method static \MemberModel|null findOneByLastname($val, $opt=array())
 * @method static \MemberModel|null findOneByDateOfBirth($val, $opt=array())
 * @method static \MemberModel|null findOneByGender($val, $opt=array())
 * @method static \MemberModel|null findOneByCompany($val, $opt=array())
 * @method static \MemberModel|null findOneByStreet($val, $opt=array())
 * @method static \MemberModel|null findOneByPostal($val, $opt=array())
 * @method static \MemberModel|null findOneByCity($val, $opt=array())
 * @method static \MemberModel|null findOneByState($val, $opt=array())
 * @method static \MemberModel|null findOneByCountry($val, $opt=array())
 * @method static \MemberModel|null findOneByPhone($val, $opt=array())
 * @method static \MemberModel|null findOneByMobile($val, $opt=array())
 * @method static \MemberModel|null findOneByFax($val, $opt=array())
 * @method static \MemberModel|null findOneByEmail($val, $opt=array())
 * @method static \MemberModel|null findOneByWebsite($val, $opt=array())
 * @method static \MemberModel|null findOneByLanguage($val, $opt=array())
 * @method static \MemberModel|null findOneByGroups($val, $opt=array())
 * @method static \MemberModel|null findOneByLogin($val, $opt=array())
 * @method static \MemberModel|null findOneByPassword($val, $opt=array())
 * @method static \MemberModel|null findOneByAssignDir($val, $opt=array())
 * @method static \MemberModel|null findOneByHomeDir($val, $opt=array())
 * @method static \MemberModel|null findOneByDisable($val, $opt=array())
 * @method static \MemberModel|null findOneByStart($val, $opt=array())
 * @method static \MemberModel|null findOneByStop($val, $opt=array())
 * @method static \MemberModel|null findOneByDateAdded($val, $opt=array())
 * @method static \MemberModel|null findOneByLastLogin($val, $opt=array())
 * @method static \MemberModel|null findOneByCurrentLogin($val, $opt=array())
 * @method static \MemberModel|null findOneByLoginCount($val, $opt=array())
 * @method static \MemberModel|null findOneByLocked($val, $opt=array())
 * @method static \MemberModel|null findOneBySession($val, $opt=array())
 * @method static \MemberModel|null findOneByCreatedOn($val, $opt=array())
 * @method static \MemberModel|null findOneByActivation($val, $opt=array())
 * @method static \MemberModel|null findOneByNewsletter($val, $opt=array())
 *
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByFirstname($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByLastname($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByDateOfBirth($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByGender($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByCompany($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByStreet($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByPostal($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByCity($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByState($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByCountry($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByPhone($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByMobile($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByFax($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByEmail($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByWebsite($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByLanguage($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByGroups($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByLogin($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByPassword($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByAssignDir($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByHomeDir($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByDisable($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByStart($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByStop($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByDateAdded($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByLastLogin($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByCurrentLogin($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByLoginCount($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByLocked($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findBySession($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByCreatedOn($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByActivation($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findByNewsletter($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\MemberModel[]|\MemberModel|null findAll($opt=array())
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
	 * @return \MemberModel|null The model or null if there is no member
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
