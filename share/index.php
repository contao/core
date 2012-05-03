<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require '../system/initialize.php';


/**
 * Facebook
 */
if ($objInput->get('p') == 'facebook')
{
	$query  = '?u=' . rawurlencode($objInput->get('u'));
	$query .= '&t=' . rawurlencode($objInput->get('t'));
	$query .= '&display=popup';
	$query .= '&redirect_uri=http%3A%2F%2Fwww.facebook.com';
	header('Location: http://www.facebook.com/sharer/sharer.php' . $query);
	exit;
}


/**
 * Twitter
 */
elseif ($objInput->get('p') == 'twitter')
{
	$query  = '?url=' . rawurlencode($objInput->get('u'));
	$query .= '&text=' . rawurlencode($objInput->get('t'));
	header('Location: http://twitter.com/share' . $query);
	exit;
}


/**
 * Google Plus
 */
elseif ($objInput->get('p') == 'gplus')
{
	$query  = '?url=' . rawurlencode($objInput->get('u'));
	header('Location: https://plus.google.com/share' . $query);
	exit;
}


/**
 * Redirect if someone gets here
 */
header('HTTP/1.1 301 Moved Permanently');
header('Location: ../index.php');
exit;
