<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Repository
 * @author    Peter Koch, IBK Software AG
 * @license   See accompaning file LICENSE.txt
 * @copyright Peter Koch 2008-2010
 */


/**
 * Implements the back end theme
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @author Leo Feyer <https://github.com/leofeyer>
 * @package    Repository
 */
class RepositoryBackendTheme
{
	const themepath = 'system/modules/repository/themes/';

	/**
	 * Get a theme file.
	 * @param string $file The basename if the file (without extension).
	 * @return string The file path.
	 */
	public static function file($file)
	{
		$theme = Config::get('backendTheme');
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
		$theme = Config::get('backendTheme');
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
		return '<img'.((substr($img, -4) == '.png') ? ' class="pngfix"' : '').' src="'.$img.'" '.$size[3].' alt="'.specialchars($alt).'"'.(($attributes != '') ? ' '.$attributes : '').'>';
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
		$target = $popup ? ' target="_blank"' : '';
		$onclick = ($confirm!='') ? ' onclick="if(!confirm(\''.$confirm.'\'))return false"' : '';
		return '<a href="'.$link.'" title="'.$text.'"'.$target.$onclick.'>'.$this->createImage($file,$text).'</a>';
	} // createListButton

	public function createMainButton($file, $link, $text, $confirm='')
	{
		$onclick = ($confirm=='')
						? ''
						: ' onclick="if(!confirm(\''.$confirm.'\'))return false"';
		return '<a href="'.$link.'" title="'.$text.'"'.$onclick.'>'.$this->createImage($file,$text).' '.$text.'</a>';
	} // createMainButton

} // class RepositoryTheme
