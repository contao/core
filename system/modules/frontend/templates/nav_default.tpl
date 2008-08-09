
<ul class="<?php echo $this->level; ?>">
<?php foreach ($this->items as $item): ?>
<?php if ($item['isActive']): ?>
<li class="active<?php if ($item['class']): ?> <?php echo $item['class']; endif; ?>"><p class="active<?php if ($item['class']): ?> <?php echo $item['class']; endif; ?>"><?php echo $item['link']; ?></p><?php echo $item['subitems']; ?></li>
<?php else: ?>
<li<?php if ($item['class']): ?> class="<?php echo $item['class']; ?>"<?php endif; ?>><a href="<?php echo $item['href']; ?>" title="<?php echo $item['pageTitle'] ? $item['pageTitle'] : $item['title']; ?>"<?php if ($item['class']): ?> class="<?php echo $item['class']; ?>"<?php endif; ?><?php if ($item['accesskey'] != ''): ?> accesskey="<?php echo $item['accesskey']; ?>"<?php endif; if ($item['tabindex']): ?> tabindex="<?php echo $item['tabindex']; ?>"<?php endif; ?> onclick="this.blur();<?php echo $item['target']; ?>"><?php echo $item['link']; ?></a><?php echo $item['subitems']; ?></li>
<?php endif; endforeach; ?>
</ul>
