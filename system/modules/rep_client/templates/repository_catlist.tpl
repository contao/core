<?php
/**
 * TYPOlight Repository :: Template to display a list of extensions
 *
 * NOTE: this file was edited with tabs set to 4.
 * @package Repository
 * @copyright Copyright (C) 2008 by Peter Koch, IBK Software AG
 * @license See accompaning file LICENSE.txt
 */
?>
<?php 
	$rep = &$this->rep;
	$theme = &$rep->theme;
	$text = &$GLOBALS['TL_LANG']['tl_repository'];
?>

<div class="mod_repository block">

<form action="<?php echo $rep->f_link; ?>" id="repository_editform" method="post">
<div class="tl_formbody">
<input type="hidden" name="repository_action" value="<?php echo $rep->f_action; ?>" />

<div class="tl_panel">

<!-- tags -->
<select name="repository_tag" id="repository_tag" class="tl_select<?php if ($rep->f_tag!='') echo ' active'; ?>" onchange="this.form.submit()">
  <option value=""><?php echo '-- '.$text['tag'].' --'; ?></option>
<?php foreach ($rep->tags as $tag) { ?>
  <option value="<?php echo $tag->item; ?>"<?php if ($rep->f_tag==$tag->item) echo ' selected="selected"'; ?>><?php echo $tag->item; ?></option>
<?php } // foreach tags ?>
</select>
 
<!-- types -->
<select name="repository_type" id="repository_type" class="tl_select<?php if ($rep->f_type!='') echo ' active'; ?>" onchange="this.form.submit()">
  <option value=""><?php echo '-- '.$text['type'][0].' --'; ?></option>
<?php foreach (array_keys($text['type_options']) as $tpe) { ?>
  <option value="<?php echo $tpe; ?>"<?php if ($rep->f_type==$tpe) echo ' selected="selected"'; ?>><?php echo $text['type_options'][$tpe]; ?></option>
<?php } // foreach types ?>
</select>

<!-- categories -->
<select name="repository_category" id="repository_category" class="tl_select<?php if ($rep->f_category!='') echo ' active'; ?>" onchange="this.form.submit()">
  <option value=""><?php echo '-- '.$text['category'][0].' --'; ?></option>
<?php foreach (array_keys($text['category_options']) as $cat) if ($cat != 'core') { ?>
  <option value="<?php echo $cat; ?>"<?php if ($rep->f_category==$cat) echo ' selected="selected"'; ?>><?php echo $text['category_options'][$cat]; ?></option>
<?php } // foreach types ?>
</select>

<!-- states -->
<select name="repository_state" id="repository_state" class="tl_select<?php if ($rep->f_state!='') echo ' active'; ?>" onchange="this.form.submit()">
  <option value=""><?php echo '-- '.$text['state'].' --'; ?></option>
<?php foreach (array_keys($text['state_options']) as $sta) { ?>
  <option value="<?php echo $sta; ?>"<?php if ($rep->f_state==$sta) echo ' selected="selected"'; ?>><?php echo $text['state_options'][$sta]; ?></option>
<?php } // foreach states ?>
</select>

<!-- authors -->
<select name="repository_author" id="repository_author" class="tl_select<?php if ($rep->f_author!='') echo ' active'; ?>" onchange="this.form.submit()">
  <option value=""><?php echo '-- '.$text['author'].' --'; ?></option>
<?php foreach ($rep->authors as $aut) { ?>
  <option value="<?php echo $aut->item; ?>"<?php if ($rep->f_author==$aut->item) echo ' selected="selected"'; ?>><?php echo $aut->item; ?></option>
<?php } // foreach authors ?>
</select>
 
</div><!-- panel -->
<div class="clear"></div>
<div class="tl_panel">

<!-- pages -->
<select name="repository_page" id="repository_page" class="tl_select<?php if ($rep->f_page!='' && $rep->f_page!=1) echo ' active'; ?>" onchange="this.form.submit()">
<?php for ($page = 1; $page <= $rep->pages; $page++) { ?>
  <option value="<?php echo $page; ?>"<?php if ($rep->f_page==$page) echo ' selected="selected"'; ?>><?php echo sprintf($text['pageof'], $page, $rep->pages); ?></option>
<?php } // foreach pages ?>
</select>
 
<!-- order -->
<select name="repository_order" id="repository_order" class="tl_select<?php if ($rep->f_order!='' && $rep->f_order!='reldate') echo ' active'; ?>" onchange="this.form.submit()">
<?php foreach (array_keys($text['order_options']) as $oby) { ?>
  <option value="<?php echo $oby; ?>"<?php if ($rep->f_order==$oby) echo ' selected="selected"'; ?>><?php echo sprintf($text['byorder'], $text['order_options'][$oby]); ?></option>
<?php } // foreach pages ?>
</select>
 
<input type="submit" name="repository_submit" id="repository_submit" class="tl_submit" alt="<?php echo $text['apply']; ?>" value="<?php echo $text['apply']; ?>" />
<script type="text/javascript">
document.getElementById('repository_submit').style.display = 'none';
</script>

</div><!-- panel -->
<div class="clear"></div>
</div><!-- formbody -->
</form>

<div class="extension_container">
<?php if (count($rep->extensions)>0) { ?>
<?php foreach ($rep->extensions as $ext) { ?>
<table cellpadding="0" cellspacing="0" class="extension" summary="">
<tr class="title">
  <th colspan="4">
    <span class="leftblock">[<a href="<?php echo $ext->viewLink; ?>" title="<?php echo $text['showdetails']; ?>"><?php echo $ext->name; ?></a>] <?php echo $ext->title; ?></span>
	<span class="rightblock"><?php echo $theme->createListButton('info', $ext->viewLink, $text['showdetails']); ?></span>
  </th>
</tr>
<?php if ($ext->teaser!='') { ?>
<tr class="description">
  <td colspan="4"><?php echo $ext->teaser; ?></td>
</tr>
<?php } // if ext teaser ?>
<tr class="info">
  <th class="listcol1"><?php echo $text['version'][0]; ?></th>
  <td class="listcol2 status-<?php echo $ext->version % 10; ?>"><?php echo Repository::formatVersion($ext->version); ?></td>
  <th class="listcol3"><?php echo $text['type'][0]; ?></th>
  <td class="type-<?php echo $ext->type; ?>"><?php echo $text['type_options'][$ext->type]; ?></td>
</tr>
<tr class="info">
  <th><?php echo $text['releasedate'][0]; ?></th>
  <td><?php echo date($GLOBALS['TL_CONFIG']['dateFormat'], $ext->releasedate); ?></td>
<?php if ($ext->type=='commercial') { ?> 
  <th><?php echo $text['demo'][0]; ?></th>
  <td><?php echo $ext->demo ? $text['yes'] : $text['no']; ?></td>
<?php } else { ?>
  <th><?php echo $text['license'][0]; ?></th>
  <td><?php echo $ext->license; ?></td>
<?php } // if free ?>
</tr>
<tr class="info">
  <th><?php echo $text['author']; ?></th>
  <td><?php echo $ext->author; ?></td>
  <th><?php echo $text['category'][0]; ?></th>
  <td><?php echo $text['category_options'][$ext->category]; ?></td>
</tr>
<?php if (true || property_exists($ext, 'votes') || property_exists($ext, 'downloads')  || property_exists($ext, 'installs')) { ?>
<tr class="info">
  <th><?php echo property_exists($ext, 'votes') ? $text['totrating'] : '&nbsp;'; ?></th>
  <td class="nowrap">
<?php if (property_exists($ext, 'votes')) { ?>
    <div class="ratebarframe"><div class="ratebar" style="width:<?php echo $ext->rating*10.0; ?>%"></div></div>
	<div class="ratebartext"><?php echo sprintf($text['ratingfmt'], $ext->rating, $ext->votes); ?></div>
<?php } // if votes ?>
  </td>
  <th><?php echo $text['popularity'][0]; ?></th>
  <td class="nowrap">
	<?php echo sprintf($text['popularity'][1], (int)$ext->downloads, (int)$ext->installs); ?>
  </td>
</tr>
<?php } // if totvotes ?>
</table>
<?php } // foreach rep->extensions ?>
<?php } else { ?>
<p><?php echo $text['noextensionsfound']; ?></p>
<?php } // if count rep->extensions ?>

</div>
</div>

<script type="text/javascript">
function repositoryGetScrollOffset()
{
	var top = this.pageYOffset || document.documentElement.scrollTop;
	document.cookie = "REPOSITORY_PAGE_OFFSET=" + top + "; path=/";
}
<?php if ($rep->pageOffset >= 0) { ?>  
onload = self.scrollTo(0, <?php echo $rep->pageOffset; ?>);
<?php } // if pageOffset ?>
</script>
