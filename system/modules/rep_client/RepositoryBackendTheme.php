<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Repository
 * @license    LGPL
 * @filesource
 */


/**
 * Contao Repository :: Class RepositoryBackendTheme
 *
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @license    See accompaning file LICENSE.txt
 */


/**
 * Implements the backend theme
 */
class RepositoryBackendTheme
{
	const themepath = 'system/modules/rep_client/themes/';

	/**
	 * Get a theme file.
	 * @param string $file The basename if the file (without extension).
	 * @return string The file path.
	 */
	public static function file($file)
	{
		$theme = $GLOBALS['TL_CONFIG']['backendTheme'];
		if (strlen($theme) && $theme!='default') {
			$f = self::themepath.$theme.'/'.$file;
			if (is_file(TL_ROOT.'/'.$f)) return $f;
		} // if
		return self::themepath.'default/'. $file;
	} // file

	/**
	 * Get image url from the theme.
	 * @param string $file The basename if the image (without extension).
	 * @return string The image path.
	 */
	public static function image($file)
	{
		$theme = $GLOBALS['TL_CONFIG']['backendTheme'];
		if (strlen($theme) && $theme!='default') {
			$url = self::themepath.$theme.'/images/';
			if (is_file(TL_ROOT.'/'.$url.$file.'.png')) return $url.$file.'.png';
			if (is_file(TL_ROOT.'/'.$url.$file.'.gif')) return $url.$file.'.gif';
		} // if
		$url = self::themepath.'default/images/';
		if (is_file(TL_ROOT.'/'.$url.$file.'.png')) return $url.$file.'.png';
		if (is_file(TL_ROOT.'/'.$url.$file.'.gif')) return $url.$file.'.gif';
		return $url.'default.png';
	} // image

	/**
	 * Create a 'img' tag from theme icons.
	 * @param string $file The basename if the image (without extension).
	 * @param string $alt The 'alt' text.
	 * @param string $attributes Additional tag attributes.
	 * @return string The html code.
	 */
	public static function createImage($file, $alt='', $attributes='')
	{
		if ($alt=='') $alt = 'icon';
		$img = self::image($file);
		$size = getimagesize(TL_ROOT.'/'.$img);
		return '<img'.((substr($img, -4) == '.png') ? ' class="pngfix"' : '').' src="'.$img.'" '.$size[3].' alt="'.specialchars($alt).'"'.(strlen($attributes) ? ' '.$attributes : '').'>';
	} // createImage

	/**
	 * Create a list button (link button)
	 * @param string $file The basename if the image (without extension).
	 * @param string $link The URL of the link to create.
	 * @param string $text The alt/title text.
	 * @param string $confirm Optional confirmation text before redirecting to the link.
	 * @param boolean $popup Open the target in a new window.
	 * @return string The html code.
	 */
	public function createListButton($file, $link, $text, $confirm='', $popup=false)
	{
		$onclick = '';
		if ($confirm!='') {
			$onclick .= 'if (!confirm(\''.$confirm.'\')) return false; ';
		}
		if ($popup) {
			$onclick .= 'window.open(this.href); return false; ';
		}
		if ($onclick!=''){
			$onclick = ' onclick="' . trim($onclick) . '"';
		}
		return '<a href="'.$link.'" title="'.$text.'"'.$onclick.'>'.$this->createImage($file,$text,'title="'.$text.'"').'</a>';
	} // createListButton

	public function createMainButton($file, $link, $text, $confirm='')
	{
		$onclick = ($confirm=='')
						? ''
						: ' onclick="if (!confirm(\''.$confirm.'\')) return false;"';
		return '<a href="'.$link.'" title="'.$text.'"'.$onclick.'>'.$this->createImage($file,$text,'title="'.$text.'"').' '.$text.'</a>';
	} // createMainButton

} // class RepositoryTheme

?>