<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>TYPOlight Open Source CMS</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css" media="screen">
<!--/*--><![CDATA[/*><!--*/
a { text-decoration:none; }
div { width:520px; margin:64px auto; padding:18px 18px 9px 18px; background-color:#ffffcc; border:1px solid #ffcc00; font-family:Verdana, sans-serif; font-size:12px; }
h1 { font-size:18px; font-weight:normal; margin:0px 0px 18px 0px; }
p { line-height:1.5; }
/*]]>*/-->
</style>
</head>
<body>

<div>

<h1>Empty referer address!</h1>

<p>The current host address (<?php echo $self['host']; ?>) does not match the current referer host address (<?php echo $referer['host']; ?>).</p>
<p>This error occurres if your browser does not send the referer host address. Many anonymizer tools, security suites or browser tools (e.g. Google toolbar) provide an option to hide the referer address to prevent user tracking. Try to find the culprit on your local computer and disable the feature.</p>
<p>For more information, visit the <a href="http://www.typolight.org/faq.html" onclick="window.open(this.href); return false;">TYPOlight FAQ page</a> or search the <a href="http://www.typolight.org/forum.html" onclick="window.open(this.href); return false;">TYPOlight forum</a>.</p>

</div>

</body>
</html>