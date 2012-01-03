<?php
/**
 * transcode wrapper functions
 * @package IDNA Convert
 * @subpackage charset transcoding
 * @author Matthias Sommerfeld, <mso@phlylabs.de>
 * @version 0.1.0
 */

/**
 * Convert a string from any of various encodings to UTF-8
 *
 * @param  string  String to encode
 *[@param  string  Encoding; Default: ISO-8859-1]
 *[@param  bool  Safe Mode: if set to TRUE, the original string is retunred on errors]
 * @return  string  The encoded string or false on failure
 * @since 0.0.1
 */
function encode_utf8($string = '', $encoding = 'iso-8859-1', $safe_mode = false)
{
    $safe = ($safe_mode) ? $string : false;
    if (strtoupper($encoding) == 'UTF-8' || strtoupper($encoding) == 'UTF8') {
        return $string;
    } elseif (strtoupper($encoding) == 'ISO-8859-1') {
        return utf8_encode($string);
    } elseif (strtoupper($encoding) == 'WINDOWS-1252') {
        return utf8_encode(map_w1252_iso8859_1($string));
    } elseif (strtoupper($encoding) == 'UNICODE-1-1-UTF-7') {
        $encoding = 'utf-7';
    }
    if (function_exists('mb_convert_encoding')) {
        $conv = @mb_convert_encoding($string, 'UTF-8', strtoupper($encoding));
        if ($conv) return $conv;
    }
    if (function_exists('iconv')) {
        $conv = @iconv(strtoupper($encoding), 'UTF-8', $string);
        if ($conv) return $conv;
    }
    if (function_exists('libiconv')) {
        $conv = @libiconv(strtoupper($encoding), 'UTF-8', $string);
        if ($conv) return $conv;
    }
    return $safe;
}

/**
 * Convert a string from UTF-8 to any of various encodings
 *
 * @param  string  String to decode
 *[@param  string  Encoding; Default: ISO-8859-1]
 *[@param  bool  Safe Mode: if set to TRUE, the original string is retunred on errors]
 * @return  string  The decoded string or false on failure
 * @since 0.0.1
 */
function decode_utf8($string = '', $encoding = 'iso-8859-1', $safe_mode = false)
{
    $safe = ($safe_mode) ? $string : false;
    if (!$encoding) $encoding = 'ISO-8859-1';
    if (strtoupper($encoding) == 'UTF-8' || strtoupper($encoding) == 'UTF8') {
        return $string;
    } elseif (strtoupper($encoding) == 'ISO-8859-1') {
        return utf8_decode($string);
    } elseif (strtoupper($encoding) == 'WINDOWS-1252') {
        return map_iso8859_1_w1252(utf8_decode($string));
    } elseif (strtoupper($encoding) == 'UNICODE-1-1-UTF-7') {
        $encoding = 'utf-7';
    }
    if (function_exists('mb_convert_encoding')) {
        $conv = @mb_convert_encoding($string, strtoupper($encoding), 'UTF-8');
        if ($conv) return $conv;
    }
    if (function_exists('iconv')) {
        $conv = @iconv('UTF-8', strtoupper($encoding), $string);
        if ($conv) return $conv;
    }
    if (function_exists('libiconv')) {
        $conv = @libiconv('UTF-8', strtoupper($encoding), $string);
        if ($conv) return $conv;
    }
    return $safe;
}

/**
 * Special treatment for our guys in Redmond
 * Windows-1252 is basically ISO-8859-1 -- with some exceptions, which get accounted for here
 * @param  string  Your input in Win1252
 * @param  string  The resulting ISO-8859-1 string
 * @since 3.0.8
 */
function map_w1252_iso8859_1($string = '')
{
    if ($string == '') return '';
    $return = '';
    for ($i = 0; $i < strlen($string); ++$i) {
        $c = ord($string{$i});
        switch ($c) {
            case 129: $return .= chr(252); break;
            case 132: $return .= chr(228); break;
            case 142: $return .= chr(196); break;
            case 148: $return .= chr(246); break;
            case 153: $return .= chr(214); break;
            case 154: $return .= chr(220); break;
            case 225: $return .= chr(223); break;
            default: $return .= chr($c); break;
        }
    }
    return $return;
}

/**
 * Special treatment for our guys in Redmond
 * Windows-1252 is basically ISO-8859-1 -- with some exceptions, which get accounted for here
 * @param  string  Your input in ISO-8859-1
 * @param  string  The resulting Win1252 string
 * @since 3.0.8
 */
function map_iso8859_1_w1252($string = '')
{
    if ($string == '') return '';
    $return = '';
    for ($i = 0; $i < strlen($string); ++$i) {
        $c = ord($string{$i});
        switch ($c) {
            case 196: $return .= chr(142); break;
            case 214: $return .= chr(153); break;
            case 220: $return .= chr(154); break;
            case 223: $return .= chr(225); break;
            case 228: $return .= chr(132); break;
            case 246: $return .= chr(148); break;
            case 252: $return .= chr(129); break;
            default: $return .= chr($c); break;
        }
    }
    return $return;
}

?>