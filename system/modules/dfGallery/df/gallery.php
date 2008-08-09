<?php
/**
 * selects the right fetcher based on the request and renders an xml file.
 * @category   Image Stripper
 * @author     G.S.Navin Raj Kumar <midart@gmail.com>
 * @copyright  2006-2007 DezinerFolio Inc.
 * @license    http://dezinerfolio.com/dfgallery/license.txt
 * @since      1.0.0
 */
if (isset($_REQUEST["type"]) && isset($_REQUEST["url"]))
{
	$type = $_REQUEST["type"];
	$url = $_REQUEST["url"];

	@set_time_limit(300);
	$type = strtolower($type);

	switch ($type)
	{
		case "flickr":
			include_once("Strippers/Flickr.php");
			$fetcher = new Strippers_Flickr($url);
			break;

		case "picasa":
			include_once("Strippers/Picasa.php");
			$fetcher = new Strippers_Picasa($url);
			break;

		case "fotki":
			include_once("Strippers/Fotki.php");
			$fetcher = new Strippers_Fotki($url);
			break;

		case "photobucket":
		   include_once("Strippers/Photobucket.php");
			$fetcher = new Strippers_Photobucket($url);
			break;

		case "typolight":
		   include_once("Strippers/TYPOlight.php");
			$fetcher = new Strippers_TYPOlight($url);
			break;
	}

	if (isset($fetcher))
	{
		header("Content-Type: text/xml");
		echo $fetcher->getResult()->saveXML();
	}
}
?>