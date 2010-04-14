<?php

/**
 * TYPOlight Repository :: Template to display a list of extensions
 *
 * @package    Repository
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @license    See accompaning file LICENSE.txt
 */

$rep = &$this->rep;
$theme = &$rep->theme;
$text = &$GLOBALS['TL_LANG']['tl_repository'];
$type_options = &$GLOBALS['TL_LANG']['tl_repository_type_options'];
$category_options = &$GLOBALS['TL_LANG']['tl_repository_category_options'];
$order_options = &$GLOBALS['TL_LANG']['tl_repository_order_options'];
$state_options = &$GLOBALS['TL_LANG']['tl_repository_state_options'];

?>

<div class="mod_repository block">

<form action="<?php echo $rep->f_link; ?>" id="repository_editform" method="post">
<div class="tl_formbody">
<input type="hidden" name="repository_action" value="<?php echo $rep->f_action; ?>" />

<div class="tl_panel">

<select name="repository_tag" id="repository_tag" class="tl_select<?php if ($rep->f_tag!='') echo ' active'; ?>" onchange="this.form.submit()">
  <option value=""><?php echo '-- '.$text['tag'].' --'; ?></option>
<?php foreach ($rep->tags as $tag): ?>
  <option value="<?php echo $tag->item; ?>"<?php if ($rep->f_tag==$tag->item) echo ' selected="selected"'; ?>><?php echo $tag->item; ?></option>
<?php endforeach; ?>
</select>
 
<select name="repository_type" id="repository_type" class="tl_select<?php if ($rep->f_type!='') echo ' active'; ?>" onchange="this.form.submit()">
  <option value=""><?php echo '-- '.$text['type'][0].' --'; ?></option>
<?php foreach (array_keys($type_options) as $tpe): ?>
  <option value="<?php echo $tpe; ?>"<?php if ($rep->f_type==$tpe) echo ' selected="selected"'; ?>><?php echo $type_options[$tpe]; ?></option>
<?php endforeach; ?>
</select>

<select name="repository_category" id="repository_category" class="tl_select<?php if ($rep->f_category!='') echo ' active'; ?>" onchange="this.form.submit()">
  <option value=""><?php echo '-- '.$text['category'][0].' --'; ?></option>
<?php foreach (array_keys($category_options) as $cat): ?>
<?php if ($cat != 'core'): ?>
  <option value="<?php echo $cat; ?>"<?php if ($rep->f_category==$cat): ?> selected="selected"<?php endif; ?>><?php echo $category_options[$cat]; ?></option>
<?php endif; ?>
<?php endforeach; ?>
</select>

<select name="repository_state" id="repository_state" class="tl_select<?php if ($rep->f_state!='') echo ' active'; ?>" onchange="this.form.submit()">
  <option value=""><?php echo '-- '.$text['state'].' --'; ?></option>
<?php foreach (array_keys($state_options) as $sta): ?>
  <option value="<?php echo $sta; ?>"<?php if ($rep->f_state==$sta) echo ' selected="selected"'; ?>><?php echo $state_options[$sta]; ?></option>
<?php endforeach; ?>
</select>

<select name="repository_author" id="repository_author" class="tl_select<?php if ($rep->f_author!='') echo ' active'; ?>" onchange="this.form.submit()">
  <option value=""><?php echo '-- '.$text['author'].' --'; ?></option>
<?php foreach ($rep->authors as $aut): ?>
  <option value="<?php echo $aut->item; ?>"<?php if ($rep->f_author==$aut->item) echo ' selected="selected"'; ?>><?php echo $aut->item; ?></option>
<?php endforeach; ?>
</select>
 
</div>

<div class="clear"></div>

<div class="tl_panel">

<select name="repository_page" id="repository_page" class="tl_select<?php if ($rep->f_page!='' && $rep->f_page!=1) echo ' active'; ?>" onchange="this.form.submit()">
<?php for ($page = 1; $page <= $rep->pages; $page++): ?>
  <option value="<?php echo $page; ?>"<?php if ($rep->f_page==$page) echo ' selected="selected"'; ?>><?php echo sprintf($text['pageof'], $page, $rep->pages); ?></option>
<?php endfor; ?>
</select>

<select name="repository_order" id="repository_order" class="tl_select<?php if ($rep->f_order!='' && $rep->f_order!='reldate') echo ' active'; ?>" onchange="this.form.submit()">
<?php foreach (array_keys($order_options) as $oby): ?>
  <option value="<?php echo $oby; ?>"<?php if ($rep->f_order==$oby) echo ' selected="selected"'; ?>><?php echo sprintf($text['byorder'], $order_options[$oby]); ?></option>
<?php endforeach; ?>
</select>
 
<input type="submit" name="repository_submit" id="repository_submit" class="tl_submit" value="<?php echo $text['apply']; ?>" />

<script type="text/javascript">
<!--//--><![CDATA[//><!--
document.getElementById('repository_submit').style.display = 'none';
//--><!]]>
</script>

</div>

<div class="clear"></div>

</div>
</form>

<div class="extension_container">
<?php if (count($rep->extensions) < 1): ?>

<p><?php echo $text['noextensionsfound']; ?></p>
<?php else: ?>
<?php foreach ($rep->extensions as $ext): ?>

<table cellpadding="0" cellspacing="0" class="extension" summary="">
<tr class="title">
  <th colspan="4"><span class="leftblock">[<a href="<?php echo $ext->viewLink; ?>" title="<?php echo $text['showdetails']; ?>"><?php echo $ext->name; ?></a>] <?php echo $ext->title; ?></span> <span class="rightblock"><?php echo $theme->createListButton('info', $ext->viewLink, $text['showdetails']); ?></span></th>
</tr>
<?php if ($ext->teaser!=''): ?>
<tr class="description">
  <td colspan="4"><?php echo $ext->teaser; ?></td>
</tr>
<?php endif; ?>
<tr class="info">
  <th class="listcol1"><?php echo $text['version'][0]; ?></th>
  <td class="listcol2 status-<?php echo $ext->version % 10; ?>"><?php echo Repository::formatVersion($ext->version); ?></td>
  <th class="listcol3"><?php echo $text['type'][0]; ?></th>
  <td class="type-<?php echo $ext->type; ?>"><?php echo $type_options[$ext->type]; ?></td>
</tr>
<tr class="info">
  <th><?php echo $text['releasedate'][0]; ?></th>
  <td><?php echo $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $ext->releasedate); ?></td>
<?php if ($ext->type=='commercial'): ?> 
  <th><?php echo $text['demo'][0]; ?></th>
  <td><?php echo $ext->demo ? $text['yes'] : $text['no']; ?></td>
<?php else: ?>
  <th><?php echo $text['license'][0]; ?></th>
  <td><?php echo $ext->license; ?></td>
<?php endif; ?>
</tr>
<tr class="info">
  <th><?php echo $text['author']; ?></th>
  <td><?php echo $ext->author; ?></td>
  <th><?php echo $text['category'][0]; ?></th>
  <td><?php echo $category_options[$ext->category]; ?></td>
</tr>
<tr class="info">
  <th><?php echo property_exists($ext, 'votes') ? $text['totrating'] : '&nbsp;'; ?></th>
  <td class="nowrap"><?php if (property_exists($ext, 'votes')): ?><div class="ratebarframe"><div class="ratebar" style="width:<?php echo $ext->rating*10.0; ?>%"></div></div> <div class="ratebartext"><?php echo sprintf($text['ratingfmt'], $ext->rating, $ext->votes); ?></div><?php endif; ?></td>
  <th><?php echo $text['popularity'][0]; ?></th>
  <td class="nowrap"><?php echo sprintf($text['popularity'][1], (int)$ext->downloads, (int)$ext->installs); ?></td>
</tr>
</table>
<?php endforeach; ?>
<?php endif; ?>

</div>
</div>
<?php if ($rep->pageOffset): ?>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
function repositoryGetScrollOffset() {
  var top = this.pageYOffset || document.documentElement.scrollTop;
  document.cookie = "REPOSITORY_PAGE_OFFSET=" + top + "; path=/";
}
onload = self.scrollTo(0, <?php echo $rep->pageOffset; ?>);
//--><!]]>
</script>
<?php endif; ?>
