<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
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
if (Input::get('p') == 'facebook')
{
	$query  = '?u=' . rawurlencode(Input::get('u', true));
	$query .= '&t=' . rawurlencode(Input::get('t', true));
	$query .= '&display=popup';
	$query .= '&redirect_uri=http%3A%2F%2Fwww.facebook.com';
	header('Location: http://www.facebook.com/sharer/sharer.php' . $query);
	exit;
}


/**
 * Twitter
 */
elseif (Input::get('p') == 'twitter')
{
	$query  = '?url=' . rawurlencode(Input::get('u', true));
	$query .= '&text=' . rawurlencode(Input::get('t', true));
	header('Location: http://twitter.com/share' . $query);
	exit;
}


/**
 * Google Plus
 */
elseif (Input::get('p') == 'gplus')
{
	$query  = '?url=' . rawurlencode(Input::get('u', true));
	header('Location: https://plus.google.com/share' . $query);
	exit;
}


/**
 * Redirect if someone gets here
 */
header('HTTP/1.1 301 Moved Permanently');
header('Location: ../index.php');
exit;
