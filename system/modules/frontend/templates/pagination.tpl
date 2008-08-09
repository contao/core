
<!-- indexer::stop -->
<div class="pagination block">

<p><?php echo $this->total; ?></p>
<ul><?php if ($this->hasFirst): ?> 
  <li><a href="<?php echo $this->first['href']; ?>" class="pagination_first" title="<?php echo $this->first['title']; ?>"><?php echo $this->first['link']; ?></a></li><?php endif; if ($this->hasPrevious): ?> 
  <li><a href="<?php echo $this->previous['href']; ?>" class="pagination_previous" title="<?php echo $this->previous['title']; ?>"><?php echo $this->previous['link']; ?></a></li><?php endif; ?> 
  <?php echo $this->items; ?><?php if ($this->hasNext): ?> 
  <li><a href="<?php echo $this->next['href']; ?>" class="pagination_next" title="<?php echo $this->next['title']; ?>"><?php echo $this->next['link']; ?></a></li><?php endif; if ($this->hasLast): ?> 
  <li><a href="<?php echo $this->last['href']; ?>" class="pagination_last" title="<?php echo $this->last['title']; ?>"><?php echo $this->last['link']; ?></a></li><?php endif; ?> 
</ul>

</div>
<!-- indexer::continue -->
