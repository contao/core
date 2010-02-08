
<div id="tl_buttons">
<a href="<?php echo $this->href; ?>" class="header_back" title="<?php echo $this->title; ?>"><?php echo $this->button; ?></a>
</div>

<h2 class="sub_headline_index"><?php echo $this->indexHeadline; ?></h2>

<div id="tl_rebuild_index">

<p id="index_note"><?php echo $this->note; ?></p>
<p id="index_loading" style="display:none;"><?php echo $this->loading; ?></p>
<p id="index_complete" style="display:none;"><?php echo $this->complete; ?></p>

<?php echo $this->content; ?>

</div>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
window.addEvent('domready', function()
{
	$('index_note').setStyle('display', 'none');
	$$('h2.sub_headline_index').setStyle('display', 'none');
	$('index_loading').setStyle('display', 'block');
});
window.addEvent('load', function()
{
	$('index_loading').setStyle('display', 'none');
	$('index_complete').setStyle('display', 'block');
});
//--><!]]>
</script>

<form action="<?php echo $this->href; ?>" class="tl_form" method="get">
<div class="tl_submit_container">
<input type="hidden" name="do" value="maintenance" />
<input type="submit" id="index" class="tl_submit" value="<?php echo $this->indexContinue; ?>" /> 
</div>
</form>
