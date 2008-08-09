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
 *this class strips images from fotki based on the url.
 * @since 12 June 2007
 * @version 1.0.0
 */
class Strippers_Fotki extends ImageFetcher{

    /**
     * strips the rss of fotki album
     * @param string $url
     * @return FotkiImageFetcher
     */
    public function Strippers_Fotki($url)
    {
        $doc   = new DOMDocument();
        $doc->load($url);
        $xpath = new DOMXPath($doc);
        $album = $xpath->query("/rss/channel/title/text()")->item(0)->nodeValue;
        $photos_list = $xpath->query("/rss/channel/item");
        for($i=0;$i<$photos_list->length;$i++)
        {
            $this->photos[$album][$i]["id"]    = "";
            $this->photos[$album][$i]["title"] = $xpath->query("title/text()",$photos_list->item($i))->item(0)->nodeValue;
            $this->photos[$album][$i]["thumb"] = $xpath->query("guid/text()",$photos_list->item($i))->item(0)->nodeValue;
            $this->photos[$album][$i]["image"] = substr($this->photos[$album][$i]["thumb"],0,strlen($this->photos[$album][$i]["thumb"])-7)."-vi.jpg";
            $this->photos[$album][$i]["date"] = $xpath->query("pubDate/text()",$photos_list->item($i))->item(0)->nodeValue;
            $this->photos[$album][$i]["link"] = $xpath->query("link/text()",$photos_list->item($i))->item(0)->nodeValue;
        }
    }
}
?>