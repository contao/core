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
 * Share a page via a social network.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FrontendShare extends \Frontend
{

	/**
	 * Run the controller
	 */
	public function run()
	{
		switch (\Input::get('p'))
		{
			case 'facebook':
				$query  = '?u=' . rawurlencode(\Input::get('u', true));
				header('Location: https://www.facebook.com/sharer/sharer.php' . $query);
				exit; break;

			case 'twitter':
				$query  = '?url=' . rawurlencode(\Input::get('u', true));
				$query .= '&text=' . rawurlencode(\Input::get('t', true));
				header('Location: https://twitter.com/intent/tweet' . $query);
				exit; break;

			case 'gplus':
				$query  = '?url=' . rawurlencode(\Input::get('u', true));
				header('Location: https://plus.google.com/share' . $query);
				exit; break;

			default:
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: ../index.php');
				exit;
		}
	}
}
