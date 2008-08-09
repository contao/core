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
 *this class strips images from picasa based on the url.
 * @since 12 June 2007
 * @version 1.0.0
 */
class Strippers_Picasa extends ImageFetcher{

    /**
     * initializes the picasa fetcher with the url.
     *
     * @param string $url
     * @return PicasaImageFetcher
     */
    public function Strippers_Picasa($url)
    {
        if(preg_match("/albumid/",$url)==0)
        {
            $this->getAllAlbums($url);
        }else{
            $this->fetchAlbumRSS($url);
        }
    }

    /**
     * process all the albums of the user
     *
     * @param string $rss_url
     */
    private function getAllAlbums($rss_url)
    {
        $doc = new DOMDocument;
        $doc->preserveWhiteSpace = false;
        $rss_url = split("\?",$rss_url);
        $rss_url = $rss_url[0]."?alt=rss&hl=en_US";
        $doc->load( $rss_url );
        $xpath  = new DOMXPath($doc);
        $albums = $xpath->query("/rss/channel/item/guid/text()");

        for($i=0;$i<$albums->length;$i++)
        {
            $url = $albums->item($i)->nodeValue;
            $url = str_replace("/entry/","/feed/",$url);
            $this->fetchAlbumRSS($url);
        }
    }

    /**
     * process single albums.
     *
     * @param string $rss_url
     */
    private function fetchAlbumRSS($rss_url)
    {
        $rss_url = split("\?",$rss_url);
        $rss_url = $rss_url[0]."?alt=rss&hl=en_US";
        $doc = new DOMDocument;
        $doc->preserveWhiteSpace = false;
        $doc->load($rss_url);
        $xpath  = new DOMXPath($doc);
        $album  = $xpath->query("/rss/channel/title")->item(0)->nodeValue;
        $photos_list = $xpath->query("/rss/channel/item");

        for($i=0;$i<$photos_list->length;$i++)
        {
            $this->photos[$album][$i]["id"]    = "";
            $this->photos[$album][$i]["title"] = $xpath->query("title/text()",$photos_list->item($i))->item(0)->nodeValue;
            $this->photos[$album][$i]["thumb"] = $xpath->query("enclosure/@url",$photos_list->item($i))->item(0)->nodeValue."?imgmax=72";
            $this->photos[$album][$i]["image"] = $xpath->query("enclosure/@url",$photos_list->item($i))->item(0)->nodeValue."?imgmax=512";
            $this->photos[$album][$i]["date"] = $xpath->query("pubDate/text()",$photos_list->item($i))->item(0)->nodeValue;
            $this->photos[$album][$i]["link"] = $xpath->query("link/text()",$photos_list->item($i))->item(0)->nodeValue;
        }
    }
}
?>