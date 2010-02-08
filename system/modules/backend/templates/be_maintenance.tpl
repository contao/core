
<div id="tl_buttons">
<a href="<?php echo $this->href; ?>" class="header_back" title="<?php echo $this->title; ?>"><?php echo $this->button; ?></a>
</div>

<div id="tl_maintenance_cache">

<h2 class="sub_headline"><?php echo $this->cacheHeadline; ?></h2>
<?php if ($this->cacheMessage): ?>

<div class="tl_message">
<?php echo $this->cacheMessage; ?>
</div>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_cache" />
<div class="tl_tbox block">
  <h3><label for="cache_tables"><?php echo $this->cacheLabel; ?></label></h3>
  <div id="cache_tables" class="tl_checkbox_container">
  <input type="checkbox" id="check_all" class="tl_checkbox" onclick="Backend.toggleCheckboxes(this, 'cache')" /> <label for="check_all" style="color:#a6a6a6;"><em><?php echo $this->selectAll; ?></em></label><br />
<?php foreach ($this->cacheTables as $arrTable): ?>
  <input type="checkbox" name="tables[]" id="<?php echo $arrTable['id']; ?>" class="tl_checkbox" value="<?php echo $arrTable['value']; ?>" onfocus="Backend.getScrollOffset();" /> <label for="<?php echo $arrTable['id']; ?>"><strong><?php echo $arrTable['name']; ?></strong> (<?php echo $arrTable['entries']; ?>)</label><br />
<?php endforeach; ?>
  <input type="checkbox" name="tables[]" id="cache_tmp" class="tl_checkbox" value="temp_folder" onfocus="Backend.getScrollOffset();" /> <label for="cache_tmp"><strong><?php echo $this->cacheTmp; ?></strong> (<?php echo $this->cacheEntries; ?>)</label><br />
  <input type="checkbox" name="tables[]" id="cache_html" class="tl_checkbox" value="html_folder" onfocus="Backend.getScrollOffset();" /> <label for="cache_html"><strong><?php echo $this->cacheHtml; ?></strong> (<?php echo $this->htmlEntries; ?>)</label><br />
  <input type="checkbox" name="tables[]" id="cache_css" class="tl_checkbox" value="css_files" onfocus="Backend.getScrollOffset();" /> <label for="cache_css"><strong><?php echo $this->cacheCss; ?></strong></label><br />
  <input type="checkbox" name="tables[]" id="cache_xml" class="tl_checkbox" value="xml_files" onfocus="Backend.getScrollOffset();" /> <label for="cache_xml"><strong><?php echo $this->cacheXml; ?></strong></label>
  </div>
<?php if ($this->cacheHelp): ?>
  <p class="tl_help"><?php echo $this->cacheHelp; ?></p>
<?php endif; ?>
</div>
<div class="tl_submit_container">
<input type="submit" name="clear" id="clear" class="tl_submit" value="<?php echo $this->cacheSubmit; ?>" /> 
</div>
</div>
</form>

</div>

<div id="tl_maintenance_update">

<h2 class="sub_headline_update"><?php echo $this->updateHeadline; ?></h2>

<div class="tl_message" id="lu_message">
<p class="<?php echo $this->updateClass; ?>"><?php echo $this->updateMessage; ?></p>
</div>

<form action="<?php echo $this->updateServer; ?>" class="tl_form" method="post" onsubmit="AjaxRequest.liveUpdate(this, 'ctrl_liveUpdate'); return false;">
<div class="tl_formbody_edit">
<input type="hidden" name="ver" value="<?php echo $this->version; ?>" />
<input type="hidden" name="ref" value="<?php echo $this->referer; ?>" />
<div class="tl_tbox block">
  <h3 style="padding-top:9px;"><label for="ctrl_liveUpdate"><?php echo $this->liveUpdateId; ?></label></h3>
  <input type="text" name="uid" id="ctrl_liveUpdate" value="<?php echo $this->uid; ?>" class="tl_text" onfocus="Backend.getScrollOffset();" />
  <div class="tl_checkbox_container" style="margin-top:0px;">
  <input type="checkbox" name="bup" id="ctrl_bup" value="1" class="tl_checkbox" onfocus="Backend.getScrollOffset();" checked="checked" /> <label for="ctrl_bup"><?php echo $this->backupFiles; ?></label><br />
  <input type="checkbox" name="toc" id="ctrl_toc" value="1" class="tl_checkbox" onfocus="Backend.getScrollOffset();" /> <label for="ctrl_toc"><?php echo $this->showToc; ?></label>
  </div>
</div>
<div class="tl_submit_container">
<input type="submit" class="tl_submit" value="<?php echo $this->runLiveUpdate; ?>" />
</div>

</div>
</form>

</div>

<div id="tl_maintenance_index">

<h2 class="sub_headline_index"><?php echo $this->indexHeadline; ?></h2>
<?php if ($this->indexMessage): ?>

<div class="tl_message">
<p class="tl_error"><?php echo $this->indexMessage; ?></p>
</div>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" class="tl_form" method="get">
<div class="tl_formbody_edit">
<input type="hidden" name="act" value="index" />
<input type="hidden" name="do" value="maintenance" />
<div class="tl_tbox block">
  <h3><label for="ctrl_user"><?php echo $this->indexLabel; ?></label></h3>
  <select name="user" id="ctrl_user" class="tl_select">
<?php foreach ($this->user as $id=>$name): ?>
    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
<?php endforeach; ?>
  </select>
<?php if ($this->indexHelp): ?>
  <p class="tl_help"><?php echo $this->indexHelp; ?></p>
<?php endif; ?>
  </div>
<div class="tl_submit_container">
<input type="submit" id="index" class="tl_submit" value="<?php echo $this->indexSubmit; ?>" /> 
</div>

</div>
</form>

</div>