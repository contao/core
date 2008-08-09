
<div id="tl_buttons">
<a href="<?php echo $this->href; ?>" class="header_back" title="<?php echo $this->title; ?>"><?php echo $this->button; ?></a>
</div>

<h2 class="sub_headline_index"><?php echo $this->indexHeadline; ?></h2>

<div id="tl_rebuild_index">

<p><?php echo $this->note; ?></p>

<?php echo $this->content; ?>

</div>

<form action="<?php echo $this->href; ?>" class="tl_form" method="get">
<div class="tl_submit_container">
<input type="hidden" name="do" value="maintenance" />
<input type="submit" id="index" class="tl_submit" alt="<?php echo $this->indexContinue; ?>" value="<?php echo $this->indexContinue; ?>" /> 
</div>
</form>
