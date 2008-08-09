
<div class="comment_default<?php echo $this->class; ?>" id="<?php echo $this->id; ?>">
<div class="info">
<?php echo $this->by; ?> <?php if ($this->website): ?><a href="<?php echo $this->website; ?>" onclick="window.open(this.href); return false;"><?php endif; echo $this->name; ?><?php if ($this->website): ?></a><?php endif; ?><span class="date"> | <?php echo $this->date; ?></span>
</div>
<div class="comment">
<?php echo $this->comment; ?> 
</div>
</div>
