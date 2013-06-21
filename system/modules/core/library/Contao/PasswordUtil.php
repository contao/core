<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * A class to verify passwords
 *
 * This class provides a wrapper around the PHP 5.5 password hashing API
 * as it also handles deprecated Contao password hashing methods and updates
 * them.
 * Moreover, it automatically calls password_needs_rehash() for you.
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @author    Yanick witschi <yanick.witschi@terminal42.ch>
 * @copyright Leo Feyer 2005-2013
 */
class PasswordUtil
{
	/**
	 * The algorithm
	 * @var integer
	 */
	protected $algo = 0;

    /**
	 * The options
	 * @var array
	 */
	protected $options = array();

    /**
     * The old algorithm
     * @var string
     */
    protected $oldAlgo = '';

    /**
     * The old salt
     * @var string
     */
    protected $oldSalt = '';

    /**
     * The updated password
     * @var string
     */
    protected $password = '';


    /**
     * Use the default hashing settings or pass your own settings
     *
     * @param integer Algorithm
     * @param array Options
     */
    public function __construct($algo=false, $options=false)
    {
        $this->algo     = ($algo) ? $algo : $GLOBALS['TL_PASSWORD']['algorithm'];
        $this->options  = ($options) ? $options : $GLOBALS['TL_PASSWORD']['options'];
    }

    /**
     * If you have an old Contao hashing algorithm, you can pass the password and salt
     * to this method. It will be used to check the password.
     * Don't forget to call getUpdatedPassword() afterwards, so you can store the password
     * generated using the latest algorithm
     *
     * @param string Algorithm (either "crypt" or "sha1")
     * @param string Salt
     */
    public function setOldHashingAlgorithm($algo, $salt)
    {
        if (!in_array($algo, array('crypt', 'sha1')))
        {
            throw new \InvalidArgumentException('The old hashing algorithm has to be either "crypt" or "sha1"');
        }

        $this->oldAlgo = $algo;
        $this->oldSalt = $salt;
    }

    /**
     * Creates a password hash
     *
     * @param string Password
     *
     * @see http://www.php.net/password-hash
     *
     * @return string
     */
    public function password_hash($password)
    {
        return password_hash($password, $this->algo, $this->options);
    }

    /**
     * Checks if the given hash matches the given options
     *
     * @param string Hash
     *
     * @see http://www.php.net/password-needs-rehash
     *
     * @return string
     */
    public function password_needs_rehash($hash)
    {
        return password_needs_rehash($hash, $this->algo, $this->options);
    }

    /**
     * Verifies a password
     *
     * @param string Password
     * @param string Password hash
     *
     * @see http://www.php.net/password-verify
     *
     * @return boolean
     */
    public function password_verify($password, $hash)
    {
        $originalPassword = $password;
        $blnOld = false;

        // Check password hashing algorithms of previous Contao versions
        if ($this->oldAlgo != '')
        {
            switch ($this->oldAlgo)
            {
                case 'crypt':
                    $password = crypt($password, $this->oldSalt);
                    break;

                case 'sha1':
                    if ($this->oldSalt == '')
                    {
                        $password = sha1($password);
                    }
                    else
                    {
                        $password = sha1($this->oldSalt . $password) . ':' . $this->oldSalt;
                    }
                    break;
            }

            $blnOld = true;
        }

        if (password_verify($password, $hash))
        {
            // Update password to the new format for old algorithms
            if ($blnOld)
            {
                $this->password = $this->password_hash($originalPassword);
            }

            if ($this->password_needs_rehash($this->password))
            {
                $this->password = $this->password_hash($originalPassword);
            }

            return true;
        }

        return false;
    }

    /**
     * This method returns the password generated using password_verify()
     * This is useless, if you don't use setOldHashingAlgorithm()
     *
     * @return string
     */
    public function getUpdatedPassword()
    {
        return $this->password;
    }
}
