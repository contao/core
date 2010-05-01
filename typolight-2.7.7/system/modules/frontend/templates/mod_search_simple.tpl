
<form action="<?php echo $this->action; ?>" method="get">
<div class="formbody"><?php if ($this->id): ?> 
<input type="hidden" name="id" value="<?php echo $this->id; ?>" /><?php endif; ?> 
<input type="text" name="keywords" id="keywords" class="text" value="<?php echo $this->keyword; ?>" />
<input type="submit" id="submit" class="submit" value="<?php echo $this->search; ?>" />
</div>
</form>
