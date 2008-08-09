<?php
/**
 * @category    Image Stripper
 * @author      G.S.Navin Raj Kumar <midart@gmail.com>
 * @copyright   2006-2007 DezinerFolio Inc.
 * @license     http://dezinerfolio.com/dfgallery/license.txt
 * @since       1.0.0
 */

/**
 * This class contains methods that all the Image fetching classes must extend
 * to return a similar result.
 * @since 11 June 2007
 * @version 1.0.0
 */
abstract class ImageFetcher
{

    /**
     * stores all the photos of flickr user temporarily before generating the xml
     *
     * @var array
     */
    protected $photos=array();

    /**
     * returns an xml document containing all the photos.
     */
    public function getResult()
    {
        // create the document
        $doc = new DOMDocument;
        $root = $doc->createElement("gallery");
        $doc->appendChild($root);

        // iterate throught the albums
        foreach($this->photos as $albums_key => $albums_value)
        {
            $album = $doc->createElement("album");
            $album->setAttribute("title",$albums_key);

            // iterate through all photos of the album
            foreach ($albums_value as $photos)
            {
                $photo = $doc->createElement("photo");
                foreach ($photos as $key => $value)
                {
                    $photo->setAttribute($key,$value);
                }
                $album->appendChild($photo);
            }
            $root->appendChild($album);
        }
        return $doc;
    }
}
?>