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
 *this class strips images from flickr based on the url provided.
 * @since 11 June 2007
 * @version 1.0.0
 */
class Strippers_Flickr extends ImageFetcher{

    private $url;

    private $userid;

    /**
     * initializes the flickr fetcher with the url and the maximum per page.
     *
     * @param string $url
     * @param int $perpage
     * @return FlickrImageFetcher
     */
    public function Strippers_Flickr($url,$page=1)
    {
        $this->url = $url;
        $this->userid = $this->getUserNSID($this->url);
        $doc = new DOMDocument;
        $doc->preserveWhiteSpace = false;
        $doc->load( $this->getUrl("flickr.photos.search",  array("user_id"=>$this->userid,"per_page"=>500,"extras"=>"o_dims,original_format")));
        $xpath = new DOMXPath($doc);
        $total_pages = intval($xpath->query("/rsp/photos/@pages")->item(0)->nodeValue);
        $this->processPhotos($xpath->query("/rsp/photos/photo"));
        if ($total_pages>1) {
            for($i=2;i<$total_pages;$i++){
                $this->processPhotos($this->getPhotos($i));
            }
        }
    }


    public function getPhotos($page)
    {
        $doc = new DOMDocument;
        $doc->preserveWhiteSpace = false;
        $doc->load( $this->getUrl("flickr.photos.search",  array("user_id"=>$this->userid,"page"=>$page,"per_page"=>500,"extras"=>"o_dims,original_format")));
        $xpath = new DOMXPath($doc);
        return $xpath->query("/rsp/photos/photo");
    }

    public function processPhotos($photos_list){
        for ($i=0;$i<$photos_list->length;$i++){
            $id = $photos_list->item($i)->getAttribute("id");
            $secret = $photos_list->item($i)->getAttribute("secret");
            $server = $photos_list->item($i)->getAttribute("server");
            $farm = $photos_list->item($i)->getAttribute("farm");
            $title = $photos_list->item($i)->getAttribute("title");
            $this->photos["flickr"][$i]["id"]=$id;
            $this->photos["flickr"][$i]["title"]=$photos_list->item($i)->getAttribute("title");

            $width = intval($photos_list->item($i)->getAttribute("o_width"));
            $this->photos["flickr"][$i]["url"] ="http://farm$farm.static.flickr.com/$server/$id"."_".$secret;
            if ($width>500) {
                $this->photos["flickr"][$i]["image"] ="_b";
            }
        }
    }

    /**
     * returns the API url for flickr.
     *
     * @param string $method
     * @param array $array
     * @return string
     */
    private function getUrl($method,$array)
    {
        // this is the API key from Flickr slide show.
        // u can change it if you want to.
        $params["api_key"] = "dfe0ebf2f0293c02758e7f4d548749b4";
        $params["method"] = $method;
        $params = array_merge($params,$array);
        $encoded_params = array();

        foreach ($params as $k => $v)
        {
            $encoded_params[] = urlencode($k).'='.urlencode($v);
        }
        return "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);
    }

    /**
     * return the NSID of the flickr user based on the url.
     *
     * @param string $url
     * @return string
     */
    private function getUserNSID($url)
    {
        $doc = new DOMDocument;
        $doc->preserveWhiteSpace = false;
        $doc->load( $this->getUrl("flickr.urls.lookupUser",  array("url"=>$url)   ));
        $xpath = new DOMXPath($doc);
        return $xpath->query("/rsp/user/@id")->item(0)->nodeValue;
    }
}
?>