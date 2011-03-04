<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Contao Open Source CMS</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css" media="screen">
<!--/*--><![CDATA[/*><!--*/
a {
  text-decoration:none;
}
div {
  width:520px;
  margin:64px auto;
  padding:18px 18px 9px 18px;
  background-color:#ffc;
  border:1px solid #fc0;
  font-family:Verdana, sans-serif;
  font-size:12px;
}
h1 {
  font-size:18px;
  font-weight:normal;
  margin:0 0 18px 0;
}
p {
  line-height:1.5;
}
/*]]>*/-->
</style>
</head>
<body>

<div>

<h1>Empty referer address!</h1>

<p>The current host address (<?php echo $self['host']; ?>) does not match the current referer host address (<?php echo $referer['host']; ?>).</p>
<p>This error occurres if your browser does not send the referer host address. Many anonymizer tools, security suites or browser tools (e.g. Google toolbar) provide an option to hide the referer address to prevent user tracking. Try to find the culprit on your local computer and disable the feature.</p>
<p>For more information, visit the <a href="http://www.contao.org/faq.html" onclick="window.open(this.href); return false;">Contao FAQ page</a> or search the <a href="http://www.contao.org/forum.html" onclick="window.open(this.href); return false;">Contao forum</a>.</p>

</div>

</body>
</html>