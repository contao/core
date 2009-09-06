<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>TYPOlight Open Source CMS</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css" media="screen">
<!--/*--><![CDATA[/*><!--*/
a {
  text-decoration:none;
}
div {
  width:520px;
  margin:64px auto;
  padding:24px;
  background-color:#ffc;
  border:1px solid #fc0;
  font-family:Verdana,sans-serif;
  font-size:12px;
}
h1 {
  font-size:18px;
  font-weight:normal;
  margin:0 0 18px 0;
}
pre {
  margin:9px;
  padding:0;
  font-size:14px;
}
p {
  margin:24px 0 0 0;
  padding:0;
  color:#666;
  font-size:11px;
}
ol {
  margin:0;
  padding:0 21px;
}
li {
  line-height:24px;
}
/*]]>*/-->
</style>
</head>
<body>

<div>

<h1>An error occurred while executing this script!</h1>

<ol>
  <li>If you are a TYPOlight user, contact the administrator of your website.</li>
  <li>Open file <em>system/logs/error.log</em> and search for a corresponding entry.</li>
  <li>Open file <em>system/config/localconfig.php</em> and add the following line:<pre>$GLOBALS['TL_CONFIG']['displayErrors'] = true;</pre></li>
  <li>Read the <a href="http://www.typolight.org/faq.html" onclick="window.open(this.href); return false;">TYPOlight FAQs</a> or visit the <a href="http://www.typolight.org/support.html" onclick="window.open(this.href); return false;">TYPOlight support page</a>.</li>
</ol>

<p>To customize this notice, create a custom template file named <em>templates/be_error.tpl</em>.</p>

</div>

</body>
</html>