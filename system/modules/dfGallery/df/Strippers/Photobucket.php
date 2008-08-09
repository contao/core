<?php
/**
 * @category   Image Stripper
 * @author     G.S.Navin Raj Kumar <midart@gmail.com>
 * @copyright  2006-2007 DezinerFolio Inc.
 * @license    http://dezinerfolio.com/dfgallery/license.txt
 * @since      1.0.0
 */

require_once "ImageFetcher.php";

/**
 *this class strips images from photobucket based on the url.
 * @since 13 June 2007
 * @version 1.0.0
 */
class Strippers_Photobucket extends ImageFetcher{

    /**
     * strips the rss of fotki album
     * @param string $url
     * @return Strippers_Photobucket
     */
    public function Strippers_Photobucket($url)
    {
        $doc   = new DOMDocument();
        $doc->load($url);
        $xpath = new DOMXPath($doc);
        $album = $xpath->query("/rss/channel/title/text()")->item(0)->nodeValue;
        $photos_list = $xpath->query("/rss/channel/item");
        for($i=0;$i<$photos_list->length;$i++)
        {
            $type = $xpath->query("media:group/media:thumbnail/@url",$photos_list->item($i))->item(0)->nodeValue;
            $type = substr($type,strlen($type)-3,strlen($type));
            if (strtolower($type)=="jpg") {
                $this->photos[$album][$i]["id"]    = "";
                $this->photos[$album][$i]["title"] = $xpath->query("title/text()",$photos_list->item($i))->item(0)->nodeValue;
                $this->photos[$album][$i]["image"] = $xpath->query("media:group/media:content/@url",$photos_list->item($i))->item(0)->nodeValue;
                $this->photos[$album][$i]["thumb"] = $xpath->query("media:group/media:thumbnail/@url",$photos_list->item($i))->item(0)->nodeValue;
                $this->photos[$album][$i]["date"] = $xpath->query("pubDate/text()",$photos_list->item($i))->item(0)->nodeValue;
                $this->photos[$album][$i]["link"] = $xpath->query("link/text()",$photos_list->item($i))->item(0)->nodeValue;
            }
        }
    }
}
?>