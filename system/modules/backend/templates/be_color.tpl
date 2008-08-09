<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->language; ?>">
<!--

	This website was built with TYPOlight :: open source web content management system
	TYPOlight was developed by Leo Feyer (leo@typolight.org) :: released under GNU/GPL
	Visit project page http://www.typolight.org for more information

//-->
<head>
<base href="<?php echo $this->base; ?>" />
<title><?php echo $this->title; ?> :: TYPOlight webCMS <?php echo VERSION; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/basic.css" media="screen" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/color.css" media="screen" />
<?php if ($this->isMac): ?>
<link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/macfixes.css" media="screen" />
<?php endif; ?>
<!--[if IE]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/iefixes.css" media="screen" /><![endif]-->
<script type="text/javascript" src="plugins/mootools/mootools.js"></script>
<script type="text/javascript" src="typolight/typolight.js"></script>
<script type="text/javascript" src="system/themes/<?php echo $this->theme; ?>/hover.js"></script>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
var validator = false;
document.onLoad = self.focus();

function previewColor(c)
{
    if (c.length == 3 || c.length == 6)
    {
        $('color').value = c;
        $('preview').setStyle('background-color', '#' + c);
    }
}

function setColor(c)
{
    if (c.match(/[^0-9abcdef]/g))
    {
        alert('Invalid color');
        return;
    }

    self.opener.$(self.opener.Backend.currentId).value = c;
    self.close();
}

function enterColor(c)
{
    if (c.match(/[^0-9abcdef]/g))
    {
        var el = $('color');
        alert('Invalid character');
        el.value = el.value.substr(0, (el.value.length - 1));
	} 
    else
    {
        if (c.length >= 6 && validator)
        {
            setColor(c);
            return;
        }

        previewColor(c);
        validator = (c.length < 6) ? false : true;
    }
}
//--><!]]>
</script>
</head>
<body>

<div id="container">

<div id="main">

<table cellspacing="0" cellpadding="8" id="cp_header" summary="Table contains color preview">
  <tr>
    <td align="right"><div id="preview" style="background-color:#<?php echo $this->color ? $this->color : '000000'; ?>; height:20px; width:60px; border:1px solid #7f9db9;"></div></td>
    <td><input type="text" id="color" value="<?php echo $this->color ? $this->color : '000000'; ?>" maxlength="6" onkeyup="enterColor(this.value)" style="width:60px;" /></td>
  </tr>
</table>

<table cellspacing="1" cellpadding="0" id="cp_survey" summary="Table contains color survey">
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#003300;" onmouseover="previewColor('003300')" onclick="setColor('003300')"></td>
    <td style="background-color:#006600;" onmouseover="previewColor('006600')" onclick="setColor('006600')"></td>
    <td style="background-color:#009900;" onmouseover="previewColor('009900')" onclick="setColor('009900')"></td>
    <td style="background-color:#00cc00;" onmouseover="previewColor('00cc00')" onclick="setColor('00cc00')"></td>
    <td style="background-color:#00ff00;" onmouseover="previewColor('00ff00')" onclick="setColor('00ff00')"></td>
    <td style="background-color:#330000;" onmouseover="previewColor('330000')" onclick="setColor('330000')"></td>
    <td style="background-color:#333300;" onmouseover="previewColor('333300')" onclick="setColor('333300')"></td>
    <td style="background-color:#336600;" onmouseover="previewColor('336600')" onclick="setColor('336600')"></td>
    <td style="background-color:#339900;" onmouseover="previewColor('339900')" onclick="setColor('339900')"></td>
    <td style="background-color:#33cc00;" onmouseover="previewColor('33cc00')" onclick="setColor('33cc00')"></td>
    <td style="background-color:#33ff00;" onmouseover="previewColor('33ff00')" onclick="setColor('33ff00')"></td>
    <td style="background-color:#660000;" onmouseover="previewColor('660000')" onclick="setColor('660000')"></td>
    <td style="background-color:#663300;" onmouseover="previewColor('663300')" onclick="setColor('663300')"></td>
    <td style="background-color:#666600;" onmouseover="previewColor('666600')" onclick="setColor('666600')"></td>
    <td style="background-color:#669900;" onmouseover="previewColor('669900')" onclick="setColor('669900')"></td>
    <td style="background-color:#66cc00;" onmouseover="previewColor('66cc00')" onclick="setColor('66cc00')"></td>
    <td style="background-color:#66ff00;" onmouseover="previewColor('66ff00')" onclick="setColor('66ff00')"></td>
  </tr>
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#333333;" onmouseover="previewColor('333333')" onclick="setColor('333333')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#000033;" onmouseover="previewColor('000033')" onclick="setColor('000033')"></td>
    <td style="background-color:#003333;" onmouseover="previewColor('003333')" onclick="setColor('003333')"></td>
    <td style="background-color:#006633;" onmouseover="previewColor('006633')" onclick="setColor('006633')"></td>
    <td style="background-color:#009933;" onmouseover="previewColor('009933')" onclick="setColor('009933')"></td>
    <td style="background-color:#00cc33;" onmouseover="previewColor('00cc33')" onclick="setColor('00cc33')"></td>
    <td style="background-color:#00ff33;" onmouseover="previewColor('00ff33')" onclick="setColor('00ff33')"></td>
    <td style="background-color:#330033;" onmouseover="previewColor('330033')" onclick="setColor('330033')"></td>
    <td style="background-color:#333333;" onmouseover="previewColor('333333')" onclick="setColor('333333')"></td>
    <td style="background-color:#336633;" onmouseover="previewColor('336633')" onclick="setColor('336633')"></td>
    <td style="background-color:#339933;" onmouseover="previewColor('339933')" onclick="setColor('339933')"></td>
    <td style="background-color:#33cc33;" onmouseover="previewColor('33cc33')" onclick="setColor('33cc33')"></td>
    <td style="background-color:#33ff33;" onmouseover="previewColor('33ff33')" onclick="setColor('33ff33')"></td>
    <td style="background-color:#660033;" onmouseover="previewColor('660033')" onclick="setColor('660033')"></td>
    <td style="background-color:#663333;" onmouseover="previewColor('663333')" onclick="setColor('663333')"></td>
    <td style="background-color:#666633;" onmouseover="previewColor('666633')" onclick="setColor('666633')"></td>
    <td style="background-color:#669933;" onmouseover="previewColor('669933')" onclick="setColor('669933')"></td>
    <td style="background-color:#66cc33;" onmouseover="previewColor('66cc33')" onclick="setColor('66cc33')"></td>
    <td style="background-color:#66ff33;" onmouseover="previewColor('66ff33')" onclick="setColor('66ff33')"></td>
  </tr>
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#666666;" onmouseover="previewColor('666666')" onclick="setColor('666666')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#000066;" onmouseover="previewColor('000066')" onclick="setColor('000066')"></td>
    <td style="background-color:#003366;" onmouseover="previewColor('003366')" onclick="setColor('003366')"></td>
    <td style="background-color:#006666;" onmouseover="previewColor('006666')" onclick="setColor('006666')"></td>
    <td style="background-color:#009966;" onmouseover="previewColor('009966')" onclick="setColor('009966')"></td>
    <td style="background-color:#00cc66;" onmouseover="previewColor('00cc66')" onclick="setColor('00cc66')"></td>
    <td style="background-color:#00ff66;" onmouseover="previewColor('00ff66')" onclick="setColor('00ff66')"></td>
    <td style="background-color:#330066;" onmouseover="previewColor('330066')" onclick="setColor('330066')"></td>
    <td style="background-color:#333366;" onmouseover="previewColor('333366')" onclick="setColor('333366')"></td>
    <td style="background-color:#336666;" onmouseover="previewColor('336666')" onclick="setColor('336666')"></td>
    <td style="background-color:#339966;" onmouseover="previewColor('339966')" onclick="setColor('339966')"></td>
    <td style="background-color:#33cc66;" onmouseover="previewColor('33cc66')" onclick="setColor('33cc66')"></td>
    <td style="background-color:#33ff66;" onmouseover="previewColor('33ff66')" onclick="setColor('33ff66')"></td>
    <td style="background-color:#660066;" onmouseover="previewColor('660066')" onclick="setColor('660066')"></td>
    <td style="background-color:#663366;" onmouseover="previewColor('663366')" onclick="setColor('663366')"></td>
    <td style="background-color:#666666;" onmouseover="previewColor('666666')" onclick="setColor('666666')"></td>
    <td style="background-color:#669966;" onmouseover="previewColor('669966')" onclick="setColor('669966')"></td>
    <td style="background-color:#66cc66;" onmouseover="previewColor('66cc66')" onclick="setColor('66cc66')"></td>
    <td style="background-color:#66ff66;" onmouseover="previewColor('66ff66')" onclick="setColor('66ff66')"></td>
  </tr>
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#999999;" onmouseover="previewColor('999999')" onclick="setColor('999999')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#000099;" onmouseover="previewColor('000099')" onclick="setColor('000099')"></td>
    <td style="background-color:#003399;" onmouseover="previewColor('003399')" onclick="setColor('003399')"></td>
    <td style="background-color:#006699;" onmouseover="previewColor('006699')" onclick="setColor('006699')"></td>
    <td style="background-color:#009999;" onmouseover="previewColor('009999')" onclick="setColor('009999')"></td>
    <td style="background-color:#00cc99;" onmouseover="previewColor('00cc99')" onclick="setColor('00cc99')"></td>
    <td style="background-color:#00ff99;" onmouseover="previewColor('00ff99')" onclick="setColor('00ff99')"></td>
    <td style="background-color:#330099;" onmouseover="previewColor('330099')" onclick="setColor('330099')"></td>
    <td style="background-color:#333399;" onmouseover="previewColor('333399')" onclick="setColor('333399')"></td>
    <td style="background-color:#336699;" onmouseover="previewColor('336699')" onclick="setColor('336699')"></td>
    <td style="background-color:#339999;" onmouseover="previewColor('339999')" onclick="setColor('339999')"></td>
    <td style="background-color:#33cc99;" onmouseover="previewColor('33cc99')" onclick="setColor('33cc99')"></td>
    <td style="background-color:#33ff99;" onmouseover="previewColor('33ff99')" onclick="setColor('33ff99')"></td>
    <td style="background-color:#660099;" onmouseover="previewColor('660099')" onclick="setColor('660099')"></td>
    <td style="background-color:#663399;" onmouseover="previewColor('663399')" onclick="setColor('663399')"></td>
    <td style="background-color:#666699;" onmouseover="previewColor('666699')" onclick="setColor('666699')"></td>
    <td style="background-color:#669999;" onmouseover="previewColor('669999')" onclick="setColor('669999')"></td>
    <td style="background-color:#66cc99;" onmouseover="previewColor('66cc99')" onclick="setColor('66cc99')"></td>
    <td style="background-color:#66ff99;" onmouseover="previewColor('66ff99')" onclick="setColor('66ff99')"></td>
  </tr>
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#cccccc;" onmouseover="previewColor('cccccc')" onclick="setColor('cccccc')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#0000cc;" onmouseover="previewColor('0000cc')" onclick="setColor('0000cc')"></td>
    <td style="background-color:#0033cc;" onmouseover="previewColor('0033cc')" onclick="setColor('0033cc')"></td>
    <td style="background-color:#0066cc;" onmouseover="previewColor('0066cc')" onclick="setColor('0066cc')"></td>
    <td style="background-color:#0099cc;" onmouseover="previewColor('0099cc')" onclick="setColor('0099cc')"></td>
    <td style="background-color:#00cccc;" onmouseover="previewColor('00cccc')" onclick="setColor('00cccc')"></td>
    <td style="background-color:#00ffcc;" onmouseover="previewColor('00ffcc')" onclick="setColor('00ffcc')"></td>
    <td style="background-color:#3300cc;" onmouseover="previewColor('3300cc')" onclick="setColor('3300cc')"></td>
    <td style="background-color:#3333cc;" onmouseover="previewColor('3333cc')" onclick="setColor('3333cc')"></td>
    <td style="background-color:#3366cc;" onmouseover="previewColor('3366cc')" onclick="setColor('3366cc')"></td>
    <td style="background-color:#3399cc;" onmouseover="previewColor('3399cc')" onclick="setColor('3399cc')"></td>
    <td style="background-color:#33cccc;" onmouseover="previewColor('33cccc')" onclick="setColor('33cccc')"></td>
    <td style="background-color:#33ffcc;" onmouseover="previewColor('33ffcc')" onclick="setColor('33ffcc')"></td>
    <td style="background-color:#6600cc;" onmouseover="previewColor('6600cc')" onclick="setColor('6600cc')"></td>
    <td style="background-color:#6633cc;" onmouseover="previewColor('6633cc')" onclick="setColor('6633cc')"></td>
    <td style="background-color:#6666cc;" onmouseover="previewColor('6666cc')" onclick="setColor('6666cc')"></td>
    <td style="background-color:#6699cc;" onmouseover="previewColor('6699cc')" onclick="setColor('6699cc')"></td>
    <td style="background-color:#66cccc;" onmouseover="previewColor('66cccc')" onclick="setColor('66cccc')"></td>
    <td style="background-color:#66ffcc;" onmouseover="previewColor('66ffcc')" onclick="setColor('66ffcc')"></td>
  </tr>
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#ffffff;" onmouseover="previewColor('ffffff')" onclick="setColor('ffffff')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#0000ff;" onmouseover="previewColor('0000ff')" onclick="setColor('0000ff')"></td>
    <td style="background-color:#0033ff;" onmouseover="previewColor('0033ff')" onclick="setColor('0033ff')"></td>
    <td style="background-color:#0066ff;" onmouseover="previewColor('0066ff')" onclick="setColor('0066ff')"></td>
    <td style="background-color:#0099ff;" onmouseover="previewColor('0099ff')" onclick="setColor('0099ff')"></td>
    <td style="background-color:#00ccff;" onmouseover="previewColor('00ccff')" onclick="setColor('00ccff')"></td>
    <td style="background-color:#00ffff;" onmouseover="previewColor('00ffff')" onclick="setColor('00ffff')"></td>
    <td style="background-color:#3300ff;" onmouseover="previewColor('3300ff')" onclick="setColor('3300ff')"></td>
    <td style="background-color:#3333ff;" onmouseover="previewColor('3333ff')" onclick="setColor('3333ff')"></td>
    <td style="background-color:#3366ff;" onmouseover="previewColor('3366ff')" onclick="setColor('3366ff')"></td>
    <td style="background-color:#3399ff;" onmouseover="previewColor('3399ff')" onclick="setColor('3399ff')"></td>
    <td style="background-color:#33ccff;" onmouseover="previewColor('33ccff')" onclick="setColor('33ccff')"></td>
    <td style="background-color:#33ffff;" onmouseover="previewColor('33ffff')" onclick="setColor('33ffff')"></td>
    <td style="background-color:#6600ff;" onmouseover="previewColor('6600ff')" onclick="setColor('6600ff')"></td>
    <td style="background-color:#6633ff;" onmouseover="previewColor('6633ff')" onclick="setColor('6633ff')"></td>
    <td style="background-color:#6666ff;" onmouseover="previewColor('6666ff')" onclick="setColor('6666ff')"></td>
    <td style="background-color:#6699ff;" onmouseover="previewColor('6699ff')" onclick="setColor('6699ff')"></td>
    <td style="background-color:#66ccff;" onmouseover="previewColor('66ccff')" onclick="setColor('66ccff')"></td>
    <td style="background-color:#66ffff;" onmouseover="previewColor('66ffff')" onclick="setColor('66ffff')"></td>
  </tr>
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#ff0000;" onmouseover="previewColor('ff0000')" onclick="setColor('ff0000')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#990000;" onmouseover="previewColor('990000')" onclick="setColor('990000')"></td>
    <td style="background-color:#993300;" onmouseover="previewColor('993300')" onclick="setColor('993300')"></td>
    <td style="background-color:#996600;" onmouseover="previewColor('996600')" onclick="setColor('996600')"></td>
    <td style="background-color:#999900;" onmouseover="previewColor('999900')" onclick="setColor('999900')"></td>
    <td style="background-color:#99cc00;" onmouseover="previewColor('99cc00')" onclick="setColor('99cc00')"></td>
    <td style="background-color:#99ff00;" onmouseover="previewColor('99ff00')" onclick="setColor('99ff00')"></td>
    <td style="background-color:#cc0000;" onmouseover="previewColor('cc0000')" onclick="setColor('cc0000')"></td>
    <td style="background-color:#cc3300;" onmouseover="previewColor('cc3300')" onclick="setColor('cc3300')"></td>
    <td style="background-color:#cc6600;" onmouseover="previewColor('cc6600')" onclick="setColor('cc6600')"></td>
    <td style="background-color:#cc9900;" onmouseover="previewColor('cc9900')" onclick="setColor('cc9900')"></td>
    <td style="background-color:#cccc00;" onmouseover="previewColor('cccc00')" onclick="setColor('cccc00')"></td>
    <td style="background-color:#ccff00;" onmouseover="previewColor('ccff00')" onclick="setColor('ccff00')"></td>
    <td style="background-color:#ff0000;" onmouseover="previewColor('ff0000')" onclick="setColor('ff0000')"></td>
    <td style="background-color:#ff3300;" onmouseover="previewColor('ff3300')" onclick="setColor('ff3300')"></td>
    <td style="background-color:#ff6600;" onmouseover="previewColor('ff6600')" onclick="setColor('ff6600')"></td>
    <td style="background-color:#ff9900;" onmouseover="previewColor('ff9900')" onclick="setColor('ff9900')"></td>
    <td style="background-color:#ffcc00;" onmouseover="previewColor('ffcc00')" onclick="setColor('ffcc00')"></td>
    <td style="background-color:#ffff00;" onmouseover="previewColor('ffff00')" onclick="setColor('ffff00')"></td>
  </tr>
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#00ff00;" onmouseover="previewColor('00ff00')" onclick="setColor('00ff00')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#990033;" onmouseover="previewColor('990033')" onclick="setColor('990033')"></td>
    <td style="background-color:#993333;" onmouseover="previewColor('993333')" onclick="setColor('993333')"></td>
    <td style="background-color:#996633;" onmouseover="previewColor('996633')" onclick="setColor('996633')"></td>
    <td style="background-color:#999933;" onmouseover="previewColor('999933')" onclick="setColor('999933')"></td>
    <td style="background-color:#99cc33;" onmouseover="previewColor('99cc33')" onclick="setColor('99cc33')"></td>
    <td style="background-color:#99ff33;" onmouseover="previewColor('99ff33')" onclick="setColor('99ff33')"></td>
    <td style="background-color:#cc0033;" onmouseover="previewColor('cc0033')" onclick="setColor('cc0033')"></td>
    <td style="background-color:#cc3333;" onmouseover="previewColor('cc3333')" onclick="setColor('cc3333')"></td>
    <td style="background-color:#cc6633;" onmouseover="previewColor('cc6633')" onclick="setColor('cc6633')"></td>
    <td style="background-color:#cc9933;" onmouseover="previewColor('cc9933')" onclick="setColor('cc9933')"></td>
    <td style="background-color:#cccc33;" onmouseover="previewColor('cccc33')" onclick="setColor('cccc33')"></td>
    <td style="background-color:#ccff33;" onmouseover="previewColor('ccff33')" onclick="setColor('ccff33')"></td>
    <td style="background-color:#ff0033;" onmouseover="previewColor('ff0033')" onclick="setColor('ff0033')"></td>
    <td style="background-color:#ff3333;" onmouseover="previewColor('ff3333')" onclick="setColor('ff3333')"></td>
    <td style="background-color:#ff6633;" onmouseover="previewColor('ff6633')" onclick="setColor('ff6633')"></td>
    <td style="background-color:#ff9933;" onmouseover="previewColor('ff9933')" onclick="setColor('ff9933')"></td>
    <td style="background-color:#ffcc33;" onmouseover="previewColor('ffcc33')" onclick="setColor('ffcc33')"></td>
    <td style="background-color:#ffff33;" onmouseover="previewColor('ffff33')" onclick="setColor('ffff33')"></td>
  </tr>
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#0000ff;" onmouseover="previewColor('0000ff')" onclick="setColor('0000ff')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#990066;" onmouseover="previewColor('990066')" onclick="setColor('990066')"></td>
    <td style="background-color:#993366;" onmouseover="previewColor('993366')" onclick="setColor('993366')"></td>
    <td style="background-color:#996666;" onmouseover="previewColor('996666')" onclick="setColor('996666')"></td>
    <td style="background-color:#999966;" onmouseover="previewColor('999966')" onclick="setColor('999966')"></td>
    <td style="background-color:#99cc66;" onmouseover="previewColor('99cc66')" onclick="setColor('99cc66')"></td>
    <td style="background-color:#99ff66;" onmouseover="previewColor('99ff66')" onclick="setColor('99ff66')"></td>
    <td style="background-color:#cc0066;" onmouseover="previewColor('cc0066')" onclick="setColor('cc0066')"></td>
    <td style="background-color:#cc3366;" onmouseover="previewColor('cc3366')" onclick="setColor('cc3366')"></td>
    <td style="background-color:#cc6666;" onmouseover="previewColor('cc6666')" onclick="setColor('cc6666')"></td>
    <td style="background-color:#cc9966;" onmouseover="previewColor('cc9966')" onclick="setColor('cc9966')"></td>
    <td style="background-color:#cccc66;" onmouseover="previewColor('cccc66')" onclick="setColor('cccc66')"></td>
    <td style="background-color:#ccff66;" onmouseover="previewColor('ccff66')" onclick="setColor('ccff66')"></td>
    <td style="background-color:#ff0066;" onmouseover="previewColor('ff0066')" onclick="setColor('ff0066')"></td>
    <td style="background-color:#ff3366;" onmouseover="previewColor('ff3366')" onclick="setColor('ff3366')"></td>
    <td style="background-color:#ff6666;" onmouseover="previewColor('ff6666')" onclick="setColor('ff6666')"></td>
    <td style="background-color:#ff9966;" onmouseover="previewColor('ff9966')" onclick="setColor('ff9966')"></td>
    <td style="background-color:#ffcc66;" onmouseover="previewColor('ffcc66')" onclick="setColor('ffcc66')"></td>
    <td style="background-color:#ffff66;" onmouseover="previewColor('ffff66')" onclick="setColor('ffff66')"></td>
  </tr>
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#ffff00;" onmouseover="previewColor('ffff00')" onclick="setColor('ffff00')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#990099;" onmouseover="previewColor('990099')" onclick="setColor('990099')"></td>
    <td style="background-color:#993399;" onmouseover="previewColor('993399')" onclick="setColor('993399')"></td>
    <td style="background-color:#996699;" onmouseover="previewColor('996699')" onclick="setColor('996699')"></td>
    <td style="background-color:#999999;" onmouseover="previewColor('999999')" onclick="setColor('999999')"></td>
    <td style="background-color:#99cc99;" onmouseover="previewColor('99cc99')" onclick="setColor('99cc99')"></td>
    <td style="background-color:#99ff99;" onmouseover="previewColor('99ff99')" onclick="setColor('99ff99')"></td>
    <td style="background-color:#cc0099;" onmouseover="previewColor('cc0099')" onclick="setColor('cc0099')"></td>
    <td style="background-color:#cc3399;" onmouseover="previewColor('cc3399')" onclick="setColor('cc3399')"></td>
    <td style="background-color:#cc6699;" onmouseover="previewColor('cc6699')" onclick="setColor('cc6699')"></td>
    <td style="background-color:#cc9999;" onmouseover="previewColor('cc9999')" onclick="setColor('cc9999')"></td>
    <td style="background-color:#cccc99;" onmouseover="previewColor('cccc99')" onclick="setColor('cccc99')"></td>
    <td style="background-color:#ccff99;" onmouseover="previewColor('ccff99')" onclick="setColor('ccff99')"></td>
    <td style="background-color:#ff0099;" onmouseover="previewColor('ff0099')" onclick="setColor('ff0099')"></td>
    <td style="background-color:#ff3399;" onmouseover="previewColor('ff3399')" onclick="setColor('ff3399')"></td>
    <td style="background-color:#ff6699;" onmouseover="previewColor('ff6699')" onclick="setColor('ff6699')"></td>
    <td style="background-color:#ff9999;" onmouseover="previewColor('ff9999')" onclick="setColor('ff9999')"></td>
    <td style="background-color:#ffcc99;" onmouseover="previewColor('ffcc99')" onclick="setColor('ffcc99')"></td>
    <td style="background-color:#ffff99;" onmouseover="previewColor('ffff99')" onclick="setColor('ffff99')"></td>
  </tr>
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#00ffff;" onmouseover="previewColor('00ffff')" onclick="setColor('00ffff')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#9900cc;" onmouseover="previewColor('9900cc')" onclick="setColor('9900cc')"></td>
    <td style="background-color:#9933cc;" onmouseover="previewColor('9933cc')" onclick="setColor('9933cc')"></td>
    <td style="background-color:#9966cc;" onmouseover="previewColor('9966cc')" onclick="setColor('9966cc')"></td>
    <td style="background-color:#9999cc;" onmouseover="previewColor('9999cc')" onclick="setColor('9999cc')"></td>
    <td style="background-color:#99cccc;" onmouseover="previewColor('99cccc')" onclick="setColor('99cccc')"></td>
    <td style="background-color:#99ffcc;" onmouseover="previewColor('99ffcc')" onclick="setColor('99ffcc')"></td>
    <td style="background-color:#cc00cc;" onmouseover="previewColor('cc00cc')" onclick="setColor('cc00cc')"></td>
    <td style="background-color:#cc33cc;" onmouseover="previewColor('cc33cc')" onclick="setColor('cc33cc')"></td>
    <td style="background-color:#cc66cc;" onmouseover="previewColor('cc66cc')" onclick="setColor('cc66cc')"></td>
    <td style="background-color:#cc99cc;" onmouseover="previewColor('cc99cc')" onclick="setColor('cc99cc')"></td>
    <td style="background-color:#cccccc;" onmouseover="previewColor('cccccc')" onclick="setColor('cccccc')"></td>
    <td style="background-color:#ccffcc;" onmouseover="previewColor('ccffcc')" onclick="setColor('ccffcc')"></td>
    <td style="background-color:#ff00cc;" onmouseover="previewColor('ff00cc')" onclick="setColor('ff00cc')"></td>
    <td style="background-color:#ff33cc;" onmouseover="previewColor('ff33cc')" onclick="setColor('ff33cc')"></td>
    <td style="background-color:#ff66cc;" onmouseover="previewColor('ff66cc')" onclick="setColor('ff66cc')"></td>
    <td style="background-color:#ff99cc;" onmouseover="previewColor('ff99cc')" onclick="setColor('ff99cc')"></td>
    <td style="background-color:#ffcccc;" onmouseover="previewColor('ffcccc')" onclick="setColor('ffcccc')"></td>
    <td style="background-color:#ffffcc;" onmouseover="previewColor('ffffcc')" onclick="setColor('ffffcc')"></td>
  </tr>
  <tr>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#ff00ff;" onmouseover="previewColor('ff00ff')" onclick="setColor('ff00ff')"></td>
    <td style="background-color:#000000;" onmouseover="previewColor('000000')" onclick="setColor('000000')"></td>
    <td style="background-color:#9900ff;" onmouseover="previewColor('9900ff')" onclick="setColor('9900ff')"></td>
    <td style="background-color:#9933ff;" onmouseover="previewColor('9933ff')" onclick="setColor('9933ff')"></td>
    <td style="background-color:#9966ff;" onmouseover="previewColor('9966ff')" onclick="setColor('9966ff')"></td>
    <td style="background-color:#9999ff;" onmouseover="previewColor('9999ff')" onclick="setColor('9999ff')"></td>
    <td style="background-color:#99ccff;" onmouseover="previewColor('99ccff')" onclick="setColor('99ccff')"></td>
    <td style="background-color:#99ffff;" onmouseover="previewColor('99ffff')" onclick="setColor('99ffff')"></td>
    <td style="background-color:#cc00ff;" onmouseover="previewColor('cc00ff')" onclick="setColor('cc00ff')"></td>
    <td style="background-color:#cc33ff;" onmouseover="previewColor('cc33ff')" onclick="setColor('cc33ff')"></td>
    <td style="background-color:#cc66ff;" onmouseover="previewColor('cc66ff')" onclick="setColor('cc66ff')"></td>
    <td style="background-color:#cc99ff;" onmouseover="previewColor('cc99ff')" onclick="setColor('cc99ff')"></td>
    <td style="background-color:#ccccff;" onmouseover="previewColor('ccccff')" onclick="setColor('ccccff')"></td>
    <td style="background-color:#ccffff;" onmouseover="previewColor('ccffff')" onclick="setColor('ccffff')"></td>
    <td style="background-color:#ff00ff;" onmouseover="previewColor('ff00ff')" onclick="setColor('ff00ff')"></td>
    <td style="background-color:#ff33ff;" onmouseover="previewColor('ff33ff')" onclick="setColor('ff33ff')"></td>
    <td style="background-color:#ff66ff;" onmouseover="previewColor('ff66ff')" onclick="setColor('ff66ff')"></td>
    <td style="background-color:#ff99ff;" onmouseover="previewColor('ff99ff')" onclick="setColor('ff99ff')"></td>
    <td style="background-color:#ffccff;" onmouseover="previewColor('ffccff')" onclick="setColor('ffccff')"></td>
    <td style="background-color:#ffffff;" onmouseover="previewColor('ffffff')" onclick="setColor('ffffff')"></td>
  </tr>
</table>

</div>

</div>

</body>
</html>