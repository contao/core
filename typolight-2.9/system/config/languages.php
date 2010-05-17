<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Config
 * @license    LGPL
 * @filesource
 */


/**
 * Languages (thanks to Leo Unglaub)
 * @see http://www.sub.uni-goettingen.de/ssgfi/projekt/doku/sprachcode.html
 */
$languages = array
(
	'aa' => 'Afar',
	'ab' => 'Abkhazian',
	'af' => 'Afrikaans',
	'am' => 'Amharic',
	'ar' => 'Arabic',
	'as' => 'Assamese',
	'ay' => 'Aymara',
	'az' => 'Azerbaijani',
	'ba' => 'Bashkir',
	'be' => 'Byelorussian',
	'bg' => 'Bulgarian',
	'bh' => 'Bihari',
	'bi' => 'Bislama',
	'bn' => 'Bengali',
	'bo' => 'Tibetan',
	'br' => 'Breton',
	'bs' => 'Bosnian',
	'ca' => 'Catalan',
	'co' => 'Corsican',
	'cr' => 'Cree',
	'cs' => 'Czech',
	'cy' => 'Welsh',
	'da' => 'Danish',
	'de' => 'German',
	'dz' => 'Bhutani',
	'el' => 'Greek',
	'en' => 'English',
	'eo' => 'Esperanto',
	'es' => 'Spanish',
	'et' => 'Estonian',
	'eu' => 'Basque',
	'fa' => 'Persian',
	'fi' => 'Finnish',
	'fj' => 'Fiji',
	'fo' => 'Faroese',
	'fr' => 'French',
	'fy' => 'Frisian',
	'ga' => 'Irish',
	'gd' => 'Scots Gaelic',
	'gl' => 'Galician',
	'gn' => 'Guarani',
	'gu' => 'Gujarati',
	'ha' => 'Hausa',
	'he' => 'Hebrew',
	'hi' => 'Hindi',
	'hr' => 'Croatian',
	'hu' => 'Hungarian',
	'hy' => 'Armenian',
	'ia' => 'Interlingua',
	'id' => 'Indonesian',
	'ie' => 'Interlingue',
	'ik' => 'Inupiak',
	'is' => 'Icelandic',
	'it' => 'Italian',
	'iu' => 'Inuktitut (Eskimo)',
	'ja' => 'Japanese',
	'jv' => 'Javanese',
	'ka' => 'Georgian',
	'kk' => 'Kazakh',
	'kl' => 'Greenlandic',
	'km' => 'Cambodian',
	'kn' => 'Kannada',
	'ko' => 'Korean',
	'ks' => 'Kashmiri',
	'ku' => 'Kurdish',
	'ky' => 'Kirghiz',
	'la' => 'Latin',
	'ln' => 'Lingala',
	'lo' => 'Lao',
	'lt' => 'Lithuanian',
	'lv' => 'Latvian',
	'mg' => 'Malagasy',
	'mi' => 'Maori',
	'mk' => 'Macedonian',
	'ml' => 'Malayalam',
	'mn' => 'Mongolian',
	'mo' => 'Moldavian',
	'mr' => 'Marathi',
	'ms' => 'Malay',
	'mt' => 'Maltese',
	'my' => 'Burmese',
	'na' => 'Nauru',
	'ne' => 'Nepali',
	'nl' => 'Dutch',
	'no' => 'Norwegian',
	'oc' => 'Occitan',
	'om' => '(Afan) Oromo',
	'or' => 'Oriya',
	'pa' => 'Punjabi',
	'pl' => 'Polish',
	'ps' => 'Pashto, Pushto',
	'pt' => 'Portuguese',
	'qu' => 'Quechua',
	'rm' => 'Rhaeto-Romance',
	'rn' => 'Kirundi',
	'ro' => 'Romanian',
	'ru' => 'Russian',
	'rw' => 'Kinyarwanda',
	'sa' => 'Sanskrit',
	'sd' => 'Sindhi',
	'sg' => 'Sango',
	'sh' => 'Serbo-Croatian',
	'si' => 'Singhalese',
	'sk' => 'Slovak',
	'sl' => 'Slovenian',
	'sm' => 'Samoan',
	'sn' => 'Shona',
	'so' => 'Somali',
	'sq' => 'Albanian',
	'sr' => 'Serbian',
	'ss' => 'Siswati',
	'st' => 'Sesotho',
	'su' => 'Sudanese',
	'sv' => 'Swedish',
	'sw' => 'Swahili',
	'ta' => 'Tamil',
	'te' => 'Tegulu',
	'tg' => 'Tajik',
	'th' => 'Thai',
	'ti' => 'Tigrinya',
	'tk' => 'Turkmen',
	'tl' => 'Tagalog',
	'tn' => 'Setswana',
	'to' => 'Tonga',
	'tr' => 'Turkish',
	'ts' => 'Tsonga',
	'tt' => 'Tatar',
	'tw' => 'Twi',
	'ug' => 'Uigur',
	'uk' => 'Ukrainian',
	'ur' => 'Urdu',
	'uz' => 'Uzbek',
	'vi' => 'Vietnamese',
	'vo' => 'Volapuk',
	'wo' => 'Wolof',
	'xh' => 'Xhosa',
	'yi' => 'Yiddish',
	'yo' => 'Yoruba',
	'za' => 'Zhuang',
	'zh' => 'Chinese',
	'zu' => 'Zulu'
);

?>